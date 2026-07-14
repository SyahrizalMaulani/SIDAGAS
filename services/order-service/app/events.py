import pika
import json
import threading
import time
from .config import settings
from .database import engine
from sqlmodel import Session, select
from .models import Order, OrderStatusHistory
from datetime import datetime

def get_pika_connection():
    params = pika.URLParameters(settings.RABBITMQ_URL)
    params.heartbeat = 60
    return pika.BlockingConnection(params)

def publish_event(routing_key: str, payload: dict):
    try:
        connection = get_pika_connection()
        channel = connection.channel()
        channel.basic_publish(
            exchange="sidagas.events",
            routing_key=routing_key,
            body=json.dumps(payload),
            properties=pika.BasicProperties(
                content_type="application/json",
                delivery_mode=2  # persistent
            )
        )
        connection.close()
    except Exception as e:
        print(f"[Order Service] Failed to publish event {routing_key}: {e}", flush=True)

def handle_message(ch, method, properties, body):
    try:
        event = json.loads(body.decode("utf-8"))
        routing_key = method.routing_key
        print(f"[Order Service] Received event {routing_key}: {event}", flush=True)
        
        with Session(engine) as session:
            order_id = event.get("order_id")
            if not order_id:
                ch.basic_ack(delivery_tag=method.delivery_tag)
                return
                
            db_order = session.exec(select(Order).where(Order.id == order_id)).first()
            if not db_order:
                ch.basic_ack(delivery_tag=method.delivery_tag)
                return

            if routing_key == "inventory.reserved":
                if db_order.status == "order_received":
                    history = OrderStatusHistory(
                        order_id=db_order.id,
                        status=db_order.status,
                        notes="Inventory successfully reserved."
                    )
                    session.add(history)
                    session.commit()
            
            elif routing_key == "inventory.insufficient":
                if db_order.status != "cancelled":
                    db_order.status = "cancelled"
                    db_order.updated_at = datetime.utcnow()
                    session.add(db_order)
                    
                    history = OrderStatusHistory(
                        order_id=db_order.id,
                        status="cancelled",
                        notes="Cancelled automatically: Insufficient inventory stock."
                    )
                    session.add(history)
                    session.commit()
                    
            elif routing_key == "payment.confirmed":
                db_order.payment_status = "paid"
                if db_order.order_type == "galon":
                    db_order.status = "gallon_received"
                elif db_order.order_type == "gas":
                    db_order.status = "empty_gas_received"
                db_order.updated_at = datetime.utcnow()
                session.add(db_order)
                
                history = OrderStatusHistory(
                    order_id=db_order.id,
                    status=db_order.status,
                    notes="Payment confirmed by admin. Processing order."
                )
                session.add(history)
                session.commit()
                    
        ch.basic_ack(delivery_tag=method.delivery_tag)
    except Exception as e:
        print(f"[Order Service] Error processing message: {e}", flush=True)
        ch.basic_nack(delivery_tag=method.delivery_tag, requeue=False)

def start_consumer():
    while True:
        try:
            connection = get_pika_connection()
            channel = connection.channel()
            
            # Bind the queue to needed events dynamically
            channel.queue_bind(exchange="sidagas.events", queue="order-events", routing_key="inventory.reserved")
            channel.queue_bind(exchange="sidagas.events", queue="order-events", routing_key="inventory.insufficient")
            channel.queue_bind(exchange="sidagas.events", queue="order-events", routing_key="payment.confirmed")
            
            channel.basic_consume(
                queue="order-events",
                on_message_callback=handle_message,
                auto_ack=False
            )
            print("[Order Service] RabbitMQ consumer started on queue 'order-events'...", flush=True)
            channel.start_consuming()
        except Exception as e:
            print(f"[Order Service] Consumer connection lost, retrying in 5s: {e}", flush=True)
            time.sleep(5)

def run_consumer_background():
    thread = threading.Thread(target=start_consumer, daemon=True)
    thread.start()
