from fastapi import FastAPI
from contextlib import asynccontextmanager
from sqlmodel import SQLModel, select, Session
from .database import engine
from .models import ProductCategory, Product
from .api.endpoints import router as api_router

def seed_catalog():
    with Session(engine) as session:
        # 1. Seed Categories
        categories = {
            "galon-isi-ulang": ProductCategory(name="Galon Isi Ulang", slug="galon-isi-ulang", description="Air minum isi ulang untuk kebutuhan rumah tangga, warung, dan UMKM."),
            "lpg": ProductCategory(name="LPG", slug="lpg", description="LPG 3 kg bersubsidi untuk kebutuhan memasak rumah tangga dan usaha kecil."),
            "air-kemasan": ProductCategory(name="Air Kemasan", slug="air-kemasan", description="Air kemasan bermerek untuk pelanggan yang membutuhkan pilihan air minum premium."),
            "layanan-digital": ProductCategory(name="Layanan Digital", slug="layanan-digital", description="Pulsa, paket data, token listrik, dan pembayaran tagihan.")
        }
        
        seeded_cats = {}
        for key, cat in categories.items():
            db_cat = session.exec(select(ProductCategory).where(ProductCategory.slug == cat.slug)).first()
            if not db_cat:
                session.add(cat)
                session.commit()
                session.refresh(cat)
                seeded_cats[key] = cat
            else:
                seeded_cats[key] = db_cat
        
        # 2. Seed Products
        products = [
            Product(
                category_id=seeded_cats["galon-isi-ulang"].id,
                name="Galon Isi Ulang",
                sku="GLN-ISI-19L",
                slug="galon-isi-ulang",
                description="Air minum isi ulang untuk kebutuhan rumah tangga, warung, dan UMKM sekitar Indramayu.",
                price=5000.0,
                min_order=1,
                max_order=100,
                stock_type="gallon",
                is_active=True
            ),
            Product(
                category_id=seeded_cats["lpg"].id,
                name="Gas LPG 3 Kg",
                sku="LPG-3KG",
                slug="gas-lpg-3-kg",
                description="LPG 3 kg bersubsidi untuk kebutuhan memasak rumah tangga dan usaha kecil.",
                price=20000.0,
                min_order=1,
                max_order=10,
                stock_type="lpg",
                is_active=True
            ),
            Product(
                category_id=seeded_cats["air-kemasan"].id,
                name="Aqua Galon",
                sku="AQ-GLN-19L",
                slug="aqua-galon",
                description="Air kemasan bermerek untuk pelanggan yang membutuhkan pilihan air minum premium.",
                price=20000.0,
                min_order=1,
                max_order=100,
                stock_type="gallon",
                is_active=True
            ),
            Product(
                category_id=seeded_cats["layanan-digital"].id,
                name="Pulsa, Token Listrik, PDAM",
                sku="LYN-DGT",
                slug="layanan-digital",
                description="Layanan digital pendamping untuk pulsa, paket data, token listrik, dan pembayaran tagihan.",
                price=0.0,
                min_order=1,
                max_order=1,
                stock_type="other",
                is_active=True
            )
        ]

        for prod in products:
            db_prod = session.exec(select(Product).where(Product.sku == prod.sku)).first()
            if not db_prod:
                session.add(prod)
            else:
                # Update details if exists to keep it synced
                db_prod.name = prod.name
                db_prod.price = prod.price
                db_prod.description = prod.description
                db_prod.category_id = prod.category_id
                session.add(db_prod)
        session.commit()

@asynccontextmanager
async def lifespan(app: FastAPI):
    # Create tables in catalog schema
    SQLModel.metadata.create_all(engine)
    # Seed categories and products
    seed_catalog()
    yield

app = FastAPI(
    title="SIDAGAS Catalog Service",
    description="Product and Catalog management for SIDAGAS microservices",
    version="1.0.0",
    docs_url="/api/v1/catalog/docs",
    openapi_url="/api/v1/catalog/openapi.json",
    lifespan=lifespan
)

app.include_router(api_router, prefix="/api/v1")
