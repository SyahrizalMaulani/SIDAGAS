from sqlmodel import SQLModel, Field
from typing import Optional
from datetime import datetime
import uuid

class Warehouse(SQLModel, table=True):
    __tablename__ = "warehouses"
    __table_args__ = {"schema": "inventory"}
    
    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    code: str = Field(unique=True, index=True)
    name: str = Field()
    description: Optional[str] = Field(default=None, nullable=True)
    is_active: bool = Field(default=True)

class InventoryItem(SQLModel, table=True):
    __tablename__ = "inventory_items"
    __table_args__ = {"schema": "inventory"}
    
    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    sku: str = Field(unique=True, index=True)
    name: str = Field()
    category: str = Field(index=True)  # air, galon, gas, kemasan, material
    unit: str = Field()  # galon, tabung, pcs, liter
    minimum_stock: float = Field(default=0.0)
    is_stock_tracked: bool = Field(default=True)
    is_active: bool = Field(default=True)

class StockBalance(SQLModel, table=True):
    __tablename__ = "stock_balances"
    __table_args__ = {"schema": "inventory"}
    
    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    warehouse_id: uuid.UUID = Field(foreign_key="inventory.warehouses.id")
    inventory_item_id: uuid.UUID = Field(foreign_key="inventory.inventory_items.id")
    qty_available: float = Field(default=0.0)
    qty_reserved: float = Field(default=0.0)
    qty_damaged: float = Field(default=0.0)
    last_movement_at: datetime = Field(default_factory=datetime.utcnow)

class StockMovement(SQLModel, table=True):
    __tablename__ = "stock_movements"
    __table_args__ = {"schema": "inventory"}
    
    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    movement_number: str = Field(unique=True, index=True)
    warehouse_id: uuid.UUID = Field(foreign_key="inventory.warehouses.id")
    inventory_item_id: uuid.UUID = Field(foreign_key="inventory.inventory_items.id")
    movement_type: str = Field(index=True)  # purchase_in, sales_out, reserved, release_reserved, production_in, damage_out
    qty: float = Field()
    before_qty: float = Field()
    after_qty: float = Field()
    reference_type: Optional[str] = Field(default=None, nullable=True)  # orders, deliveries, procurement
    reference_id: Optional[uuid.UUID] = Field(default=None, nullable=True)
    created_by: Optional[uuid.UUID] = Field(default=None, nullable=True)
    notes: Optional[str] = Field(default=None, nullable=True)
    created_at: datetime = Field(default_factory=datetime.utcnow)
