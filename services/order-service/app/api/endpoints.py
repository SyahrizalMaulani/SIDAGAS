from fastapi import APIRouter, Depends, HTTPException, status
from sqlmodel import Session, select
from typing import List, Optional
from datetime import datetime
import uuid
import random

from ..database import get_session
from ..models import Order, OrderItem, OrderStatusHistory
from ..events import publish_event
from pydantic import BaseModel

router = APIRouter()

class OrderItemCreate(BaseModel):
    product_id: uuid.UUID
    product_name: str
    sku: str
    qty: int
    price: float

class OrderCreate(BaseModel):
    customer_id: uuid.UUID
    order_type: str  # galon, gas, campuran, pos
    order_source: str  # website, pelanggan_app, admin, pos, whatsapp
    delivery_fee: float = 0.0
    discount: float = 0.0
    items: List[OrderItemCreate]
    created_by: Optional[uuid.UUID] = None

class StatusUpdate(BaseModel):
    status: str
    delivery_status: Optional[str] = None
    driver_id: Optional[uuid.UUID] = None
    notes: Optional[str] = None
    changed_by: Optional[uuid.UUID] = None

@router.post("", response_model=Order, status_code=status.HTTP_201_CREATED)
def create_order(payload: OrderCreate, session: Session = Depends(get_session)):
    if not payload.items:
        raise HTTPException(status_code=400, detail="Order must contain at least one item")
        
    # Check for active orders for the customer
    active_order = session.exec(
        select(Order)
        .where(Order.customer_id == payload.customer_id)
        .where(~Order.status.in_(["completed", "cancelled", "rejected", "expired"]))
    ).first()
    if active_order:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Pelanggan masih memiliki pesanan aktif. Selesaikan atau batalkan pesanan tersebut terlebih dahulu."
        )
    
    # 1. Generate Order Number & Track ID
    date_str = datetime.utcnow().strftime("%y%m%d")
    rand_suffix = f"{random.randint(1000, 9999)}"
    
    prefix = "GLN" if payload.order_type == "galon" else "GAS"
    order_number = f"{prefix}-{date_str}-{rand_suffix}"
    track_id = f"TRX-{prefix}-{date_str}-{rand_suffix}"
    
    # 2. Calculate Subtotal & Grand Total
    subtotal = sum(item.qty * item.price for item in payload.items)
    grand_total = subtotal + payload.delivery_fee - payload.discount
    
    # 3. Create Order Header
    db_order = Order(
        order_number=order_number,
        track_id=track_id,
        customer_id=payload.customer_id,
        order_type=payload.order_type,
        order_source=payload.order_source,
        status="order_received",
        payment_status="unpaid",
        delivery_status="waiting_assignment",
        subtotal=subtotal,
        delivery_fee=payload.delivery_fee,
        discount=payload.discount,
        grand_total=grand_total,
        created_by=payload.created_by
    )
    session.add(db_order)
    session.commit()
    session.refresh(db_order)
    
    # 4. Create Order Items
    db_items = []
    for item in payload.items:
        db_item = OrderItem(
            order_id=db_order.id,
            product_id=item.product_id,
            product_name=item.product_name,
            sku=item.sku,
            qty=item.qty,
            price=item.price,
            subtotal=item.qty * item.price
        )
        session.add(db_item)
        db_items.append(db_item)
        
    # 5. Create Status History
    history = OrderStatusHistory(
        order_id=db_order.id,
        status="order_received",
        notes="Order placed successfully.",
        changed_by=payload.created_by
    )
    session.add(history)
    session.commit()
    
    # 6. Publish Event order.created
    event_payload = {
        "order_id": str(db_order.id),
        "customer_id": str(db_order.customer_id),
        "grand_total": db_order.grand_total,
        "items": [
            {
                "product_id": str(item.product_id),
                "sku": item.sku,
                "qty": item.qty
            }
            for item in db_items
        ]
    }
    publish_event("order.created", event_payload)
    
    return db_order

@router.get("", response_model=List[Order])
def list_orders(session: Session = Depends(get_session)):
    return session.exec(select(Order).order_by(Order.created_at.desc())).all()

@router.get("/{id_or_number}", response_model=dict)
def get_order(id_or_number: str, session: Session = Depends(get_session)):
    # Try parsing UUID
    db_order = None
    try:
        order_uuid = uuid.UUID(id_or_number)
        db_order = session.exec(select(Order).where(Order.id == order_uuid)).first()
    except ValueError:
        db_order = session.exec(select(Order).where(Order.order_number == id_or_number)).first()
        
    if not db_order:
        raise HTTPException(status_code=404, detail="Order not found")
        
    items = session.exec(select(OrderItem).where(OrderItem.order_id == db_order.id)).all()
    histories = session.exec(select(OrderStatusHistory).where(OrderStatusHistory.order_id == db_order.id).order_by(OrderStatusHistory.created_at.asc())).all()
    
    return {
        "order": db_order,
        "items": items,
        "histories": histories
    }

@router.put("/{order_id}/status", response_model=Order)
def update_order_status(order_id: uuid.UUID, payload: StatusUpdate, session: Session = Depends(get_session)):
    db_order = session.get(Order, order_id)
    if not db_order:
        raise HTTPException(status_code=404, detail="Order not found")
        
    old_status = db_order.status
    new_status = payload.status
    
    db_order.status = new_status
    if payload.delivery_status is not None:
        db_order.delivery_status = payload.delivery_status
    if payload.driver_id is not None:
        db_order.assigned_driver_id = payload.driver_id
        
    db_order.updated_at = datetime.utcnow()
    session.add(db_order)
    
    history = OrderStatusHistory(
        order_id=db_order.id,
        status=new_status,
        changed_by=payload.changed_by,
        notes=payload.notes or f"Status updated to {new_status}."
    )
    session.add(history)
    session.commit()
    session.refresh(db_order)
    
    # Publish specific event based on final statuses
    event_payload = {"order_id": str(db_order.id), "status": new_status}
    if new_status == "completed":
        publish_event("order.completed", event_payload)
    elif new_status == "cancelled":
        publish_event("order.cancelled", event_payload)
        
    return db_order

@router.get("/track/{track_id}", response_model=dict)
def track_order(track_id: str, session: Session = Depends(get_session)):
    db_order = session.exec(select(Order).where(Order.track_id == track_id)).first()
    if not db_order:
        raise HTTPException(status_code=404, detail="Tracking ID not found")
        
    histories = session.exec(select(OrderStatusHistory).where(OrderStatusHistory.order_id == db_order.id).order_by(OrderStatusHistory.created_at.desc())).all()
    
    return {
        "order_number": db_order.order_number,
        "status": db_order.status,
        "payment_status": db_order.payment_status,
        "delivery_status": db_order.delivery_status,
        "updated_at": db_order.updated_at,
        "histories": histories
    }
