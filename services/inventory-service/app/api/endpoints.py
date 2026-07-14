from fastapi import APIRouter, Depends, HTTPException, status
from sqlmodel import Session, select
from typing import List, Optional
import uuid
from datetime import datetime
import random

from ..database import get_session
from ..models import Warehouse, InventoryItem, StockBalance, StockMovement
from pydantic import BaseModel

router = APIRouter()

class StockAdjust(BaseModel):
    warehouse_id: uuid.UUID
    inventory_item_id: uuid.UUID
    new_qty: float
    notes: Optional[str] = None
    created_by: Optional[uuid.UUID] = None

@router.get("/items", response_model=List[InventoryItem])
def list_inventory_items(session: Session = Depends(get_session)):
    return session.exec(select(InventoryItem)).all()

@router.get("/balances", response_model=List[dict])
def list_stock_balances(session: Session = Depends(get_session)):
    results = []
    balances = session.exec(select(StockBalance)).all()
    for b in balances:
        wh = session.get(Warehouse, b.warehouse_id)
        item = session.get(InventoryItem, b.inventory_item_id)
        results.append({
            "id": b.id,
            "warehouse_id": str(b.warehouse_id),
            "inventory_item_id": str(b.inventory_item_id),
            "warehouse_code": wh.code if wh else "N/A",
            "warehouse_name": wh.name if wh else "N/A",
            "item_sku": item.sku if item else "N/A",
            "item_name": item.name if item else "N/A",
            "qty_available": b.qty_available,
            "qty_reserved": b.qty_reserved,
            "qty_damaged": b.qty_damaged,
            "last_movement_at": b.last_movement_at
        })
    return results

@router.get("/balances/sku/{sku}", response_model=dict)
def get_balance_by_sku(sku: str, session: Session = Depends(get_session)):
    item = session.exec(select(InventoryItem).where(InventoryItem.sku == sku)).first()
    if not item:
        raise HTTPException(status_code=404, detail=f"Inventory Item with SKU {sku} not found")
    
    wh = session.exec(select(Warehouse).where(Warehouse.code == "GD-UTM")).first()
    if not wh:
        raise HTTPException(status_code=404, detail="Warehouse GD-UTM not found")
        
    balance = session.exec(select(StockBalance).where(
        StockBalance.warehouse_id == wh.id,
        StockBalance.inventory_item_id == item.id
    )).first()
    
    if not balance:
        return {
            "sku": sku,
            "qty_available": 0.0,
            "qty_reserved": 0.0,
            "qty_damaged": 0.0
        }
        
    return {
        "sku": sku,
        "qty_available": balance.qty_available,
        "qty_reserved": balance.qty_reserved,
        "qty_damaged": balance.qty_damaged
    }

@router.post("/adjust", status_code=status.HTTP_200_OK)
def adjust_stock(payload: StockAdjust, session: Session = Depends(get_session)):
    wh = session.get(Warehouse, payload.warehouse_id)
    item = session.get(InventoryItem, payload.inventory_item_id)
    if not wh or not item:
        raise HTTPException(status_code=404, detail="Warehouse or Inventory Item not found")
        
    balance = session.exec(select(StockBalance).where(
        StockBalance.warehouse_id == wh.id,
        StockBalance.inventory_item_id == item.id
    )).first()
    
    if not balance:
        balance = StockBalance(
            warehouse_id=wh.id,
            inventory_item_id=item.id,
            qty_available=0.0,
            qty_reserved=0.0,
            qty_damaged=0.0
        )
        session.add(balance)
        
    before_qty = balance.qty_available
    diff = payload.new_qty - before_qty
    
    if diff == 0:
        return {"message": "No changes made", "qty_available": balance.qty_available}
        
    balance.qty_available = payload.new_qty
    balance.last_movement_at = datetime.utcnow()
    session.add(balance)
    
    # Record movement
    date_str = datetime.utcnow().strftime("%y%m%d")
    movement_number = f"STK-{date_str}-{random.randint(1000, 9999)}"
    movement_type = "adjustment_plus" if diff > 0 else "adjustment_minus"
    
    movement = StockMovement(
        movement_number=movement_number,
        warehouse_id=wh.id,
        inventory_item_id=item.id,
        movement_type=movement_type,
        qty=abs(diff),
        before_qty=before_qty,
        after_qty=payload.new_qty,
        reference_type="adjustment",
        created_by=payload.created_by,
        notes=payload.notes or "Manual stock adjustment"
    )
    session.add(movement)
    session.commit()
    
    return {
        "message": "Stock adjusted successfully",
        "sku": item.sku,
        "warehouse": wh.code,
        "before_qty": before_qty,
        "after_qty": balance.qty_available,
        "difference": diff
    }
