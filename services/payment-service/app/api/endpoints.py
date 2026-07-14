from fastapi import APIRouter, Depends, HTTPException, status
from sqlmodel import Session, select
from typing import List, Optional
import uuid
from datetime import datetime
import random

from ..database import get_session
from ..models import Payment, PaymentConfirmation
from ..events import publish_event
from pydantic import BaseModel

router = APIRouter()

class PaymentCreate(BaseModel):
    order_id: Optional[uuid.UUID] = None
    pos_sale_id: Optional[uuid.UUID] = None
    customer_id: uuid.UUID
    payment_method: str  # qris, transfer, tunai, pending_hutang
    amount: float
    notes: Optional[str] = None

class PaymentConfirm(BaseModel):
    proof_image_url: Optional[str] = None
    notes: Optional[str] = None

class PaymentVerify(BaseModel):
    is_approved: bool
    verified_by: Optional[uuid.UUID] = None
    notes: Optional[str] = None

@router.post("", response_model=Payment, status_code=status.HTTP_201_CREATED)
def create_payment(payload: PaymentCreate, session: Session = Depends(get_session)):
    date_str = datetime.utcnow().strftime("%y%m%d")
    payment_number = f"PAY-{date_str}-{random.randint(1000, 9999)}"
    
    db_payment = Payment(
        payment_number=payment_number,
        order_id=payload.order_id,
        pos_sale_id=payload.pos_sale_id,
        customer_id=payload.customer_id,
        payment_method=payload.payment_method,
        status="unpaid",
        amount=payload.amount,
        notes=payload.notes
    )
    session.add(db_payment)
    session.commit()
    session.refresh(db_payment)
    return db_payment

@router.post("/{payment_id}/confirm", response_model=Payment)
def confirm_payment(payment_id: uuid.UUID, payload: PaymentConfirm, session: Session = Depends(get_session)):
    db_payment = session.get(Payment, payment_id)
    if not db_payment:
        raise HTTPException(status_code=404, detail="Payment record not found")
        
    db_payment.status = "waiting_confirmation"
    session.add(db_payment)
    
    conf = session.exec(select(PaymentConfirmation).where(PaymentConfirmation.payment_id == db_payment.id)).first()
    if not conf:
        conf = PaymentConfirmation(
            payment_id=db_payment.id,
            proof_image_url=payload.proof_image_url,
            notes=payload.notes
        )
    else:
        conf.proof_image_url = payload.proof_image_url
        conf.notes = payload.notes
        conf.submitted_at = datetime.utcnow()
        
    session.add(conf)
    session.commit()
    session.refresh(db_payment)
    return db_payment

@router.post("/{payment_id}/verify", response_model=Payment)
def verify_payment(payment_id: uuid.UUID, payload: PaymentVerify, session: Session = Depends(get_session)):
    db_payment = session.get(Payment, payment_id)
    if not db_payment:
        raise HTTPException(status_code=404, detail="Payment record not found")
        
    if payload.is_approved:
        db_payment.status = "paid"
        db_payment.paid_at = datetime.utcnow()
        db_payment.confirmed_by = payload.verified_by
    else:
        db_payment.status = "failed"
        
    session.add(db_payment)
    
    conf = session.exec(select(PaymentConfirmation).where(PaymentConfirmation.payment_id == db_payment.id)).first()
    if conf:
        conf.verified_at = datetime.utcnow()
        conf.verified_by = payload.verified_by
        conf.notes = payload.notes
        session.add(conf)
        
    session.commit()
    session.refresh(db_payment)
    
    if payload.is_approved and db_payment.order_id:
        publish_event("payment.confirmed", {
            "payment_id": str(db_payment.id),
            "order_id": str(db_payment.order_id),
            "amount": db_payment.amount
        })
        
    return db_payment

@router.get("", response_model=List[dict])
def list_payments(session: Session = Depends(get_session)):
    results = []
    payments = session.exec(select(Payment).order_by(Payment.created_at.desc())).all()
    for p in payments:
        conf = session.exec(select(PaymentConfirmation).where(PaymentConfirmation.payment_id == p.id)).first()
        results.append({
            "id": str(p.id),
            "payment_number": p.payment_number,
            "order_id": str(p.order_id) if p.order_id else None,
            "pos_sale_id": str(p.pos_sale_id) if p.pos_sale_id else None,
            "customer_id": str(p.customer_id),
            "payment_method": p.payment_method,
            "status": p.status,
            "amount": p.amount,
            "paid_at": p.paid_at.isoformat() if p.paid_at else None,
            "confirmed_by": str(p.confirmed_by) if p.confirmed_by else None,
            "notes": p.notes,
            "created_at": p.created_at.isoformat(),
            "proof_image_url": conf.proof_image_url if conf else None,
            "submitted_at": conf.submitted_at.isoformat() if (conf and conf.submitted_at) else None,
        })
    return results

@router.get("/order/{order_id}", response_model=Payment)
def get_payment_by_order(order_id: uuid.UUID, session: Session = Depends(get_session)):
    db_payment = session.exec(select(Payment).where(Payment.order_id == order_id)).first()
    if not db_payment:
        raise HTTPException(status_code=404, detail="Payment record not found for this order")
    return db_payment
