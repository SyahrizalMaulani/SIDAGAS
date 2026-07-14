import pika
import json
import threading
import time
import random
import uuid
from .config import settings
from .database import engine
from sqlmodel import Session, select
from .models import Warehouse, InventoryItem, StockBalance, StockMovement
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
        print(f"[Inventory Service] Failed to publish event {routing_key}: {e}", flush=True)

def handle_message(ch, method, properties, body):
    try:
        event = json.loads(body.decode("utf-8"))
        routing_key = method.routing_key
        print(f"[Inventory Service] Received event {routing_key}: {event}", flush=True)
        
        with Session(engine) as session:
            utm_warehouse = session.exec(select(Warehouse).where(Warehouse.code == "GD-UTM")).first()
            if not utm_warehouse:
                print("[Inventory Service] Main warehouse GD-UTM not found!", flush=True)
                ch.basic_ack(delivery_tag=method.delivery_tag)
                return
                
            if routing_key == "order.created":
                order_id = event.get("order_id")
                items = event.get("items", [])
                
                # Check availability
                has_enough_stock = True
                reserve_candidates = []
                
                for item in items:
                    sku = item.get("sku")
                    qty = float(item.get("qty", 0))
                    
                    inv_item = session.exec(select(InventoryItem).where(InventoryItem.sku == sku)).first()
                    if not inv_item or not inv_item.is_stock_tracked:
                        continue
                        
                    balance = session.exec(select(StockBalance).where(
                        StockBalance.warehouse_id == utm_warehouse.id,
                        StockBalance.inventory_item_id == inv_item.id
                    )).first()
                    
                    if not balance or balance.qty_available < qty:
                        has_enough_stock = False
                        break
                    
                    reserve_candidates.append((balance, qty, inv_item))
                    
                if has_enough_stock:
                    # Reserve
                    for balance, qty, inv_item in reserve_candidates:
                        before_qty = balance.qty_available
                        
                        balance.qty_available -= qty
                        balance.qty_reserved += qty
                        balance.last_movement_at = datetime.utcnow()
                        session.add(balance)
                        
                        date_str = datetime.utcnow().strftime("%y%m%d")
                        movement_number = f"STK-{date_str}-{random.randint(1000, 9999)}"
                        
                        movement = StockMovement(
                            movement_number=movement_number,
                            warehouse_id=utm_warehouse.id,
                            inventory_item_id=inv_item.id,
                            movement_type="reserved",
                            qty=qty,
                            before_qty=before_qty,
                            after_qty=balance.qty_available,
                            reference_type="orders",
                            reference_id=uuid.UUID(order_id)
                        )
                        session.add(movement)
                        
                    session.commit()
                    publish_event("inventory.reserved", {"order_id": order_id})
                    print(f"[Inventory Service] Stock reserved for order {order_id}", flush=True)
                else:
                    publish_event("inventory.insufficient", {"order_id": order_id})
                    print(f"[Inventory Service] Insufficient stock for order {order_id}", flush=True)
                    
            elif routing_key == "order.cancelled":
                order_id = event.get("order_id")
                movements = session.exec(select(StockMovement).where(
                    StockMovement.reference_type == "orders",
                    StockMovement.reference_id == uuid.UUID(order_id),
                    StockMovement.movement_type == "reserved"
                )).all()
                
                for mov in movements:
                    balance = session.exec(select(StockBalance).where(
                        StockBalance.warehouse_id == mov.warehouse_id,
                        StockBalance.inventory_item_id == mov.inventory_item_id
                    )).first()
                    
                    if balance and balance.qty_reserved >= mov.qty:
                        before_qty = balance.qty_available
                        
                        balance.qty_reserved -= mov.qty
                        balance.qty_available += mov.qty
                        balance.last_movement_at = datetime.utcnow()
                        session.add(balance)
                        
                        date_str = datetime.utcnow().strftime("%y%m%d")
                        movement_number = f"STK-{date_str}-{random.randint(1000, 9999)}"
                        
                        release_mov = StockMovement(
                            movement_number=movement_number,
                            warehouse_id=mov.warehouse_id,
                            inventory_item_id=mov.inventory_item_id,
                            movement_type="release_reserved",
                            qty=mov.qty,
                            before_qty=before_qty,
                            after_qty=balance.qty_available,
                            reference_type="orders",
                            reference_id=uuid.UUID(order_id)
                        )
                        session.add(release_mov)
                            
                session.commit()
                publish_event("inventory.released", {"order_id": order_id})
                print(f"[Inventory Service] Stock released for cancelled order {order_id}", flush=True)
                
            elif routing_key == "order.completed":
                order_id = event.get("order_id")
                movements = session.exec(select(StockMovement).where(
                    StockMovement.reference_type == "orders",
                    StockMovement.reference_id == uuid.UUID(order_id),
                    StockMovement.movement_type == "reserved"
                )).all()
                
                for mov in movements:
                    balance = session.exec(select(StockBalance).where(
                        StockBalance.warehouse_id == mov.warehouse_id,
                        StockBalance.inventory_item_id == mov.inventory_item_id
                    )).first()
                    
                    if balance and balance.qty_reserved >= mov.qty:
                        balance.qty_reserved -= mov.qty
                        balance.last_movement_at = datetime.utcnow()
                        session.add(balance)
                        
                        date_str = datetime.utcnow().strftime("%y%m%d")
                        movement_number = f"STK-{date_str}-{random.randint(1000, 9999)}"
                        
                        sales_mov = StockMovement(
                            movement_number=movement_number,
                            warehouse_id=mov.warehouse_id,
                            inventory_item_id=mov.inventory_item_id,
                            movement_type="sales_out",
                            qty=mov.qty,
                            before_qty=balance.qty_available + mov.qty,
                            after_qty=balance.qty_available,
                            reference_type="orders",
                            reference_id=uuid.UUID(order_id)
                        )
                        session.add(sales_mov)
                            
                session.commit()
                print(f"[Inventory Service] Stock finalized as sales_out for order {order_id}", flush=True)
                
        ch.basic_ack(delivery_tag=method.delivery_tag)
    except Exception as e:
        print(f"[Inventory Service] Error processing message: {e}", flush=True)
        ch.basic_nack(delivery_tag=method.delivery_tag, requeue=False)

def start_consumer():
    while True:
        try:
            connection = get_pika_connection()
            channel = connection.channel()
            
            # Bind the queue to needed events dynamically
            channel.queue_bind(exchange="sidagas.events", queue="inventory-events", routing_key="order.created")
            channel.queue_bind(exchange="sidagas.events", queue="inventory-events", routing_key="order.cancelled")
            channel.queue_bind(exchange="sidagas.events", queue="inventory-events", routing_key="order.completed")
            
            channel.basic_consume(
                queue="inventory-events",
                on_message_callback=handle_message,
                auto_ack=False
            )
            print("[Inventory Service] RabbitMQ consumer started on queue 'inventory-events'...", flush=True)
            channel.start_consuming()
        except Exception as e:
            print(f"[Inventory Service] Consumer connection lost, retrying in 5s: {e}", flush=True)
            time.sleep(5)

def run_consumer_background():
    thread = threading.Thread(target=start_consumer, daemon=True)
    thread.start()
