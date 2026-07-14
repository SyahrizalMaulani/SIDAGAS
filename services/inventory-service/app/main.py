from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from sqlmodel import SQLModel, Session, select
from contextlib import asynccontextmanager

from .database import engine
from .api.endpoints import router as api_router
from .events import run_consumer_background
from .models import Warehouse, InventoryItem, StockBalance

def seed_database():
    with Session(engine) as session:
        # Seed Warehouses
        warehouses = {
            "GD-UTM": Warehouse(code="GD-UTM", name="Gudang Utama", description="Gudang penyimpanan utama untuk barang siap kirim."),
            "GD-RSK": Warehouse(code="GD-RSK", name="Gudang Barang Rusak", description="Area penyimpanan khusus untuk galon bocor atau tabung gas rusak.")
        }
        
        db_warehouses = {}
        for key, wh in warehouses.items():
            db_wh = session.exec(select(Warehouse).where(Warehouse.code == wh.code)).first()
            if not db_wh:
                session.add(wh)
                session.commit()
                session.refresh(wh)
                db_warehouses[key] = wh
            else:
                db_warehouses[key] = db_wh
                
        # Seed Inventory Items
        items = {
            "GLN-ISI-19L": InventoryItem(sku="GLN-ISI-19L", name="Galon Isi Ulang", category="galon-isi-ulang", unit="galon", minimum_stock=50.0),
            "LPG-3KG": InventoryItem(sku="LPG-3KG", name="Gas LPG 3 Kg", category="lpg", unit="tabung", minimum_stock=20.0),
            "AQ-GLN-19L": InventoryItem(sku="AQ-GLN-19L", name="Aqua Galon", category="air-kemasan", unit="galon", minimum_stock=10.0),
            "LYN-DGT": InventoryItem(sku="LYN-DGT", name="Pulsa, Token Listrik, PDAM", category="layanan-digital", unit="transaksi", minimum_stock=0.0)
        }
        
        db_items = {}
        for key, item in items.items():
            db_item = session.exec(select(InventoryItem).where(InventoryItem.sku == item.sku)).first()
            if not db_item:
                session.add(item)
                session.commit()
                session.refresh(item)
                db_items[key] = item
            else:
                db_item.name = item.name
                db_item.category = item.category
                db_item.unit = item.unit
                session.add(db_item)
                db_items[key] = db_item
                
        # Seed Stock Balances (Initial Stock)
        initial_stocks = {
            "GLN-ISI-19L": 500.0,
            "LPG-3KG": 100.0,
            "AQ-GLN-19L": 50.0,
            "LYN-DGT": 999999.0
        }
        
        for sku, qty in initial_stocks.items():
            wh = db_warehouses["GD-UTM"]
            inv_item = db_items[sku]
            
            balance = session.exec(select(StockBalance).where(
                StockBalance.warehouse_id == wh.id,
                StockBalance.inventory_item_id == inv_item.id
            )).first()
            
            if not balance:
                new_balance = StockBalance(
                    warehouse_id=wh.id,
                    inventory_item_id=inv_item.id,
                    qty_available=qty,
                    qty_reserved=0.0,
                    qty_damaged=0.0
                )
                session.add(new_balance)
            else:
                # Keep available stocks at seed levels
                balance.qty_available = qty
                session.add(balance)
        session.commit()

@asynccontextmanager
async def lifespan(app: FastAPI):
    # Create tables
    SQLModel.metadata.create_all(engine)
    
    # Seed database
    seed_database()
    
    # Run RabbitMQ consumer in background
    run_consumer_background()
    
    yield

app = FastAPI(
    title="SIDAGAS Inventory Service",
    description="Microservice managing stock items, warehouses, and movements.",
    version="1.0.0",
    lifespan=lifespan,
    docs_url="/api/v1/inventory/docs",
    openapi_url="/api/v1/inventory/openapi.json"
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(api_router, prefix="/api/v1/inventory", tags=["Inventory"])

@app.get("/health")
def health_check():
    return {"status": "healthy", "service": "inventory-service"}
