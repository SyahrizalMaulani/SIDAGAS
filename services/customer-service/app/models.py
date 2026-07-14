from datetime import datetime
from typing import Optional, List
from sqlmodel import SQLModel, Field, Relationship
import uuid

class Customer(SQLModel, table=True):
    __tablename__ = "customers"
    __table_args__ = {"schema": "customer"}

    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    name: str
    phone: str = Field(index=True)
    email: Optional[str] = Field(default=None, index=True)
    is_guest: bool = Field(default=False)
    status: str = Field(default="active", description="active / inactive / suspended")
    created_at: datetime = Field(default_factory=datetime.utcnow)
    updated_at: datetime = Field(default_factory=datetime.utcnow)

    # Relationships
    addresses: List["CustomerAddress"] = Relationship(back_populates="customer")

class CustomerAddress(SQLModel, table=True):
    __tablename__ = "customer_addresses"
    __table_args__ = {"schema": "customer"}

    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    customer_id: uuid.UUID = Field(foreign_key="customer.customers.id")
    label: str = Field(default="Home", description="e.g. Home, Office, Store")
    recipient_name: str
    recipient_phone: str
    address_text: str
    latitude: Optional[float] = None
    longitude: Optional[float] = None
    is_default: bool = Field(default=False)
    created_at: datetime = Field(default_factory=datetime.utcnow)
    updated_at: datetime = Field(default_factory=datetime.utcnow)

    # Relationships
    customer: Optional[Customer] = Relationship(back_populates="addresses")
