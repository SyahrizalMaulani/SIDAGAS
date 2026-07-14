import pika
import json
import threading
import time
import random
import uuid
from .config import settings
from .database import engine
from sqlmodel import Session, select
from .models import Payment
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
        print(f"[Payment Service] Failed to publish event {routing_key}: {e}", flush=True)

def handle_message(ch, method, properties, body):
    try:
        event = json.loads(body.decode("utf-8"))
        routing_key = method.routing_key
        print(f"[Payment Service] Received event {routing_key}: {event}", flush=True)
        
        with Session(engine) as session:
            if routing_key == "order.created":
                order_id = event.get("order_id")
                customer_id = event.get("customer_id")
                grand_total = float(event.get("grand_total", 0))
                
                existing = session.exec(select(Payment).where(Payment.order_id == uuid.UUID(order_id))).first()
                if not existing:
                    date_str = datetime.utcnow().strftime("%y%m%d")
                    payment_number = f"PAY-{date_str}-{random.randint(1000, 9999)}"
                    
                    new_payment = Payment(
                        payment_number=payment_number,
                        order_id=uuid.UUID(order_id),
                        customer_id=uuid.UUID(customer_id),
                        payment_method="transfer",
                        status="unpaid",
                        amount=grand_total
                    )
                    session.add(new_payment)
                    session.commit()
                    print(f"[Payment Service] Created unpaid invoice for order {order_id}", flush=True)
                      
        ch.basic_ack(delivery_tag=method.delivery_tag)
    except Exception as e:
        print(f"[Payment Service] Error processing message: {e}", flush=True)
        ch.basic_nack(delivery_tag=method.delivery_tag, requeue=False)

def start_consumer():
    while True:
        try:
            connection = get_pika_connection()
            channel = connection.channel()
            
            # Bind the queue to needed events dynamically
            channel.queue_bind(exchange="sidagas.events", queue="payment-events", routing_key="order.created")
            
            channel.basic_consume(
                queue="payment-events",
                on_message_callback=handle_message,
                auto_ack=False
            )
            print("[Payment Service] RabbitMQ consumer started on queue 'payment-events'...", flush=True)
            channel.start_consuming()
        except Exception as e:
            print(f"[Payment Service] Consumer connection lost, retrying in 5s: {e}", flush=True)
            time.sleep(5)

def run_consumer_background():
    thread = threading.Thread(target=start_consumer, daemon=True)
    thread.start()
