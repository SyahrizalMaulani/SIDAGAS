from datetime import datetime
from typing import Optional, List
from sqlmodel import SQLModel, Field, Relationship
import uuid

class ProductCategory(SQLModel, table=True):
    __tablename__ = "product_categories"
    __table_args__ = {"schema": "catalog"}

    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    name: str = Field(unique=True, index=True)
    slug: str = Field(unique=True, index=True)
    description: Optional[str] = None
    created_at: datetime = Field(default_factory=datetime.utcnow)
    updated_at: datetime = Field(default_factory=datetime.utcnow)

    # Relationships
    products: List["Product"] = Relationship(back_populates="category")

class Product(SQLModel, table=True):
    __tablename__ = "products"
    __table_args__ = {"schema": "catalog"}

    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    category_id: uuid.UUID = Field(foreign_key="catalog.product_categories.id")
    name: str
    sku: str = Field(unique=True, index=True)
    slug: str = Field(unique=True, index=True)
    description: Optional[str] = None
    image_url: Optional[str] = None
    price: float = Field(default=0.0)
    min_order: int = Field(default=1)
    max_order: int = Field(default=999)
    is_active: bool = Field(default=True)
    stock_type: str = Field(default="gallon", description="gallon / lpg / other")
    created_at: datetime = Field(default_factory=datetime.utcnow)
    updated_at: datetime = Field(default_factory=datetime.utcnow)

    # Relationships
    category: Optional[ProductCategory] = Relationship(back_populates="products")
