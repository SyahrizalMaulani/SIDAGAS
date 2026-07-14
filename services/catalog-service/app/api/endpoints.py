from fastapi import APIRouter, Depends, HTTPException, status
from sqlmodel import Session, select
import uuid
from typing import List, Optional
from pydantic import BaseModel

from ..database import get_db
from ..models import ProductCategory, Product

router = APIRouter()

# --- Pydantic Schemas ---
class CategoryCreateRequest(BaseModel):
    name: str
    slug: str
    description: Optional[str] = None

class CategoryResponse(BaseModel):
    id: uuid.UUID
    name: str
    slug: str
    description: Optional[str]

class ProductCreateRequest(BaseModel):
    category_id: uuid.UUID
    name: str
    sku: str
    slug: str
    description: Optional[str] = None
    image_url: Optional[str] = None
    price: float
    min_order: Optional[int] = 1
    max_order: Optional[int] = 999
    stock_type: str = "gallon"

class ProductUpdateRequest(BaseModel):
    name: Optional[str] = None
    price: Optional[float] = None
    description: Optional[str] = None
    image_url: Optional[str] = None
    min_order: Optional[int] = None
    max_order: Optional[int] = None
    is_active: Optional[bool] = None
    stock_type: Optional[str] = None

class ProductResponse(BaseModel):
    id: uuid.UUID
    category_id: uuid.UUID
    name: str
    sku: str
    slug: str
    description: Optional[str]
    image_url: Optional[str]
    price: float
    min_order: int
    max_order: int
    is_active: bool
    stock_type: str

# --- Endpoints ---

# --- Categories ---
@router.get("/catalog/categories", response_model=List[CategoryResponse])
def get_categories(db: Session = Depends(get_db)):
    categories = db.exec(select(ProductCategory)).all()
    return [
        CategoryResponse(
            id=c.id,
            name=c.name,
            slug=c.slug,
            description=c.description
        ) for c in categories
    ]

@router.post("/catalog/categories", response_model=CategoryResponse, status_code=status.HTTP_201_CREATED)
def create_category(req: CategoryCreateRequest, db: Session = Depends(get_db)):
    # Check if category exists
    existing = db.exec(select(ProductCategory).where(ProductCategory.slug == req.slug)).first()
    if existing:
        raise HTTPException(status_code=400, detail="Category slug already exists.")

    new_cat = ProductCategory(
        name=req.name,
        slug=req.slug,
        description=req.description
    )
    db.add(new_cat)
    db.commit()
    db.refresh(new_cat)
    return CategoryResponse(
        id=new_cat.id,
        name=new_cat.name,
        slug=new_cat.slug,
        description=new_cat.description
    )

# --- Products ---
@router.get("/catalog/products", response_model=List[ProductResponse])
def get_products(category_id: Optional[uuid.UUID] = None, active_only: bool = True, db: Session = Depends(get_db)):
    query = select(Product)
    if active_only:
        query = query.where(Product.is_active == True)
    if category_id:
        query = query.where(Product.category_id == category_id)
        
    products = db.exec(query).all()
    return [
        ProductResponse(
            id=p.id,
            category_id=p.category_id,
            name=p.name,
            sku=p.sku,
            slug=p.slug,
            description=p.description,
            image_url=p.image_url,
            price=p.price,
            min_order=p.min_order,
            max_order=p.max_order,
            is_active=p.is_active,
            stock_type=p.stock_type
        ) for p in products
    ]

@router.post("/catalog/products", response_model=ProductResponse, status_code=status.HTTP_201_CREATED)
def create_product(req: ProductCreateRequest, db: Session = Depends(get_db)):
    # Verify category exists
    cat = db.get(ProductCategory, req.category_id)
    if not cat:
        raise HTTPException(status_code=400, detail="Category not found.")

    # Check unique sku
    existing_sku = db.exec(select(Product).where(Product.sku == req.sku)).first()
    if existing_sku:
        raise HTTPException(status_code=400, detail="Product SKU already exists.")

    new_prod = Product(
        category_id=req.category_id,
        name=req.name,
        sku=req.sku,
        slug=req.slug,
        description=req.description,
        image_url=req.image_url,
        price=req.price,
        min_order=req.min_order,
        max_order=req.max_order,
        stock_type=req.stock_type,
        is_active=True
    )
    db.add(new_prod)
    db.commit()
    db.refresh(new_prod)

    return ProductResponse(
        id=new_prod.id,
        category_id=new_prod.category_id,
        name=new_prod.name,
        sku=new_prod.sku,
        slug=new_prod.slug,
        description=new_prod.description,
        image_url=new_prod.image_url,
        price=new_prod.price,
        min_order=new_prod.min_order,
        max_order=new_prod.max_order,
        is_active=new_prod.is_active,
        stock_type=new_prod.stock_type
    )

@router.get("/catalog/products/{id}", response_model=ProductResponse)
def get_product(id: uuid.UUID, db: Session = Depends(get_db)):
    p = db.get(Product, id)
    if not p:
        raise HTTPException(status_code=404, detail="Product not found.")
    
    return ProductResponse(
        id=p.id,
        category_id=p.category_id,
        name=p.name,
        sku=p.sku,
        slug=p.slug,
        description=p.description,
        image_url=p.image_url,
        price=p.price,
        min_order=p.min_order,
        max_order=p.max_order,
        is_active=p.is_active,
        stock_type=p.stock_type
    )

@router.put("/catalog/products/{id}", response_model=ProductResponse)
def update_product(id: uuid.UUID, req: ProductUpdateRequest, db: Session = Depends(get_db)):
    p = db.get(Product, id)
    if not p:
        raise HTTPException(status_code=404, detail="Product not found.")

    if req.name is not None:
        p.name = req.name
    if req.price is not None:
        p.price = req.price
    if req.description is not None:
        p.description = req.description
    if req.image_url is not None:
        p.image_url = req.image_url
    if req.min_order is not None:
        p.min_order = req.min_order
    if req.max_order is not None:
        p.max_order = req.max_order
    if req.is_active is not None:
        p.is_active = req.is_active
    if req.stock_type is not None:
        p.stock_type = req.stock_type

    db.add(p)
    db.commit()
    db.refresh(p)

    return ProductResponse(
        id=p.id,
        category_id=p.category_id,
        name=p.name,
        sku=p.sku,
        slug=p.slug,
        description=p.description,
        image_url=p.image_url,
        price=p.price,
        min_order=p.min_order,
        max_order=p.max_order,
        is_active=p.is_active,
        stock_type=p.stock_type
    )
