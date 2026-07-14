from sqlmodel import SQLModel, Field
from typing import Optional
from datetime import datetime
import uuid

class Payment(SQLModel, table=True):
    __tablename__ = "payments"
    __table_args__ = {"schema": "payment"}
    
    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    payment_number: str = Field(unique=True, index=True)
    order_id: Optional[uuid.UUID] = Field(default=None, index=True, nullable=True)
    pos_sale_id: Optional[uuid.UUID] = Field(default=None, index=True, nullable=True)
    customer_id: uuid.UUID = Field(index=True)
    payment_method: str = Field(index=True)  # qris, transfer, tunai, pending_hutang
    status: str = Field(index=True, default="unpaid")  # unpaid, waiting_confirmation, paid, debt, failed, refund
    amount: float = Field()
    paid_at: Optional[datetime] = Field(default=None, nullable=True)
    confirmed_by: Optional[uuid.UUID] = Field(default=None, nullable=True)
    notes: Optional[str] = Field(default=None, nullable=True)
    created_at: datetime = Field(default_factory=datetime.utcnow)

class PaymentConfirmation(SQLModel, table=True):
    __tablename__ = "payment_confirmations"
    __table_args__ = {"schema": "payment"}
    
    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    payment_id: uuid.UUID = Field(foreign_key="payment.payments.id")
    proof_image_url: Optional[str] = Field(default=None, nullable=True)
    submitted_at: datetime = Field(default_factory=datetime.utcnow)
    verified_at: Optional[datetime] = Field(default=None, nullable=True)
    verified_by: Optional[uuid.UUID] = Field(default=None, nullable=True)
    notes: Optional[str] = Field(default=None, nullable=True)
