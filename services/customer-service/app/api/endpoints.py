from fastapi import APIRouter, Depends, HTTPException, status
from sqlmodel import Session, select
import uuid
from typing import List, Optional
from pydantic import BaseModel, EmailStr

from ..database import get_db
from ..models import Customer, CustomerAddress
from ..events import publish_event

router = APIRouter()

# --- Pydantic Schemas ---
class CustomerCreateRequest(BaseModel):
    name: str
    phone: str
    email: Optional[EmailStr] = None
    is_guest: bool = False

class CustomerUpdateRequest(BaseModel):
    name: Optional[str] = None
    phone: Optional[str] = None
    email: Optional[EmailStr] = None
    status: Optional[str] = None

class AddressCreateRequest(BaseModel):
    label: str = "Home"
    recipient_name: str
    recipient_phone: str
    address_text: str
    latitude: Optional[float] = None
    longitude: Optional[float] = None
    is_default: bool = False

class AddressResponse(BaseModel):
    id: uuid.UUID
    label: str
    recipient_name: str
    recipient_phone: str
    address_text: str
    latitude: Optional[float]
    longitude: Optional[float]
    is_default: bool

class CustomerResponse(BaseModel):
    id: uuid.UUID
    name: str
    phone: str
    email: Optional[str]
    is_guest: bool
    status: str
    addresses: List[AddressResponse]

# --- Endpoints ---

@router.get("/customers", response_model=List[CustomerResponse])
def get_customers(
    search: Optional[str] = None, 
    is_guest: Optional[bool] = None, 
    db: Session = Depends(get_db)
):
    query = select(Customer)
    if is_guest is not None:
        query = query.where(Customer.is_guest == is_guest)
    if search:
        query = query.where(Customer.name.like(f"%{search}%") | Customer.phone.like(f"%{search}%"))
    
    customers = db.exec(query).all()
    
    res = []
    for c in customers:
        addr_list = [
            AddressResponse(
                id=a.id,
                label=a.label,
                recipient_name=a.recipient_name,
                recipient_phone=a.recipient_phone,
                address_text=a.address_text,
                latitude=a.latitude,
                longitude=a.longitude,
                is_default=a.is_default
            ) for a in c.addresses
        ]
        res.append(CustomerResponse(
            id=c.id,
            name=c.name,
            phone=c.phone,
            email=c.email,
            is_guest=c.is_guest,
            status=c.status,
            addresses=addr_list
        ))
    return res

@router.post("/customers", response_model=CustomerResponse, status_code=status.HTTP_201_CREATED)
def create_customer(req: CustomerCreateRequest, db: Session = Depends(get_db)):
    # Check if customer already exists by phone
    existing = db.exec(select(Customer).where(Customer.phone == req.phone)).first()
    if existing:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail=f"Customer with phone {req.phone} already exists."
        )

    new_customer = Customer(
        name=req.name,
        phone=req.phone,
        email=req.email,
        is_guest=req.is_guest,
        status="active"
    )
    db.add(new_customer)
    db.commit()
    db.refresh(new_customer)

    # Publish event to RabbitMQ
    event_data = {
        "id": str(new_customer.id),
        "name": new_customer.name,
        "phone": new_customer.phone,
        "email": new_customer.email,
        "is_guest": new_customer.is_guest,
        "created_at": new_customer.created_at.isoformat()
    }
    publish_event("customer.registered", event_data)

    return CustomerResponse(
        id=new_customer.id,
        name=new_customer.name,
        phone=new_customer.phone,
        email=new_customer.email,
        is_guest=new_customer.is_guest,
        status=new_customer.status,
        addresses=[]
    )

@router.get("/customers/{id}", response_model=CustomerResponse)
def get_customer(id: uuid.UUID, db: Session = Depends(get_db)):
    c = db.get(Customer, id)
    if not c:
        raise HTTPException(status_code=404, detail="Customer not found")
    
    addr_list = [
        AddressResponse(
            id=a.id,
            label=a.label,
            recipient_name=a.recipient_name,
            recipient_phone=a.recipient_phone,
            address_text=a.address_text,
            latitude=a.latitude,
            longitude=a.longitude,
            is_default=a.is_default
        ) for a in c.addresses
    ]
    return CustomerResponse(
        id=c.id,
        name=c.name,
        phone=c.phone,
        email=c.email,
        is_guest=c.is_guest,
        status=c.status,
        addresses=addr_list
    )

@router.put("/customers/{id}", response_model=CustomerResponse)
def update_customer(id: uuid.UUID, req: CustomerUpdateRequest, db: Session = Depends(get_db)):
    c = db.get(Customer, id)
    if not c:
        raise HTTPException(status_code=404, detail="Customer not found")

    if req.name is not None:
        c.name = req.name
    if req.phone is not None:
        c.phone = req.phone
    if req.email is not None:
        c.email = req.email
    if req.status is not None:
        c.status = req.status

    db.add(c)
    db.commit()
    db.refresh(c)

    addr_list = [
        AddressResponse(
            id=a.id,
            label=a.label,
            recipient_name=a.recipient_name,
            recipient_phone=a.recipient_phone,
            address_text=a.address_text,
            latitude=a.latitude,
            longitude=a.longitude,
            is_default=a.is_default
        ) for a in c.addresses
    ]
    return CustomerResponse(
        id=c.id,
        name=c.name,
        phone=c.phone,
        email=c.email,
        is_guest=c.is_guest,
        status=c.status,
        addresses=addr_list
    )

# --- Addresses CRUD ---

@router.post("/customers/{id}/addresses", response_model=AddressResponse)
def add_address(id: uuid.UUID, req: AddressCreateRequest, db: Session = Depends(get_db)):
    c = db.get(Customer, id)
    if not c:
        raise HTTPException(status_code=404, detail="Customer not found")

    # If is_default is True, unset existing default addresses for this customer
    if req.is_default:
        existing_defaults = db.exec(
            select(CustomerAddress)
            .where(CustomerAddress.customer_id == c.id)
            .where(CustomerAddress.is_default == True)
        ).all()
        for addr in existing_defaults:
            addr.is_default = False
            db.add(addr)

    new_addr = CustomerAddress(
        customer_id=c.id,
        label=req.label,
        recipient_name=req.recipient_name,
        recipient_phone=req.recipient_phone,
        address_text=req.address_text,
        latitude=req.latitude,
        longitude=req.longitude,
        is_default=req.is_default
    )
    db.add(new_addr)
    db.commit()
    db.refresh(new_addr)

    return AddressResponse(
        id=new_addr.id,
        label=new_addr.label,
        recipient_name=new_addr.recipient_name,
        recipient_phone=new_addr.recipient_phone,
        address_text=new_addr.address_text,
        latitude=new_addr.latitude,
        longitude=new_addr.longitude,
        is_default=new_addr.is_default
    )

@router.get("/customers/{id}/addresses", response_model=List[AddressResponse])
def get_addresses(id: uuid.UUID, db: Session = Depends(get_db)):
    c = db.get(Customer, id)
    if not c:
        raise HTTPException(status_code=404, detail="Customer not found")

    addr_list = [
        AddressResponse(
            id=a.id,
            label=a.label,
            recipient_name=a.recipient_name,
            recipient_phone=a.recipient_phone,
            address_text=a.address_text,
            latitude=a.latitude,
            longitude=a.longitude,
            is_default=a.is_default
        ) for a in c.addresses
    ]
    return addr_list
