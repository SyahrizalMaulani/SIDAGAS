from sqlmodel import SQLModel, Field
from typing import Optional
from datetime import datetime
import uuid

class Order(SQLModel, table=True):
    __tablename__ = "orders"
    __table_args__ = {"schema": "orders"}
    
    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    order_number: str = Field(unique=True, index=True)
    track_id: str = Field(unique=True, index=True)
    customer_id: uuid.UUID = Field(index=True)
    order_type: str = Field(index=True)  # galon, gas, campuran, pos
    order_source: str = Field(index=True)  # website, pelanggan_app, admin, pos, whatsapp
    status: str = Field(index=True, default="order_received")
    payment_status: str = Field(index=True, default="unpaid")
    delivery_status: str = Field(index=True, default="waiting_assignment")
    subtotal: float = Field(default=0.0)
    delivery_fee: float = Field(default=0.0)
    discount: float = Field(default=0.0)
    grand_total: float = Field(default=0.0)
    assigned_driver_id: Optional[uuid.UUID] = Field(default=None, nullable=True)
    created_by: Optional[uuid.UUID] = Field(default=None, nullable=True)
    created_at: datetime = Field(default_factory=datetime.utcnow)
    updated_at: datetime = Field(default_factory=datetime.utcnow)

class OrderItem(SQLModel, table=True):
    __tablename__ = "order_items"
    __table_args__ = {"schema": "orders"}
    
    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    order_id: uuid.UUID = Field(foreign_key="orders.orders.id")
    product_id: uuid.UUID = Field()
    product_name: str = Field()
    sku: str = Field()
    qty: int = Field()
    price: float = Field()
    subtotal: float = Field()

class OrderStatusHistory(SQLModel, table=True):
    __tablename__ = "order_status_histories"
    __table_args__ = {"schema": "orders"}
    
    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    order_id: uuid.UUID = Field(foreign_key="orders.orders.id")
    status: str = Field()
    changed_by: Optional[uuid.UUID] = Field(default=None, nullable=True)
    notes: Optional[str] = Field(default=None, nullable=True)
    created_at: datetime = Field(default_factory=datetime.utcnow)
