from fastapi import FastAPI
from contextlib import asynccontextmanager
from sqlmodel import SQLModel
from .database import engine
from .api.endpoints import router as api_router
from .models import Customer, CustomerAddress # ensure models registered in metadata

@asynccontextmanager
async def lifespan(app: FastAPI):
    # Create tables in customer schema
    SQLModel.metadata.create_all(engine)
    yield

app = FastAPI(
    title="SIDAGAS Customer Service",
    description="Customer management for SIDAGAS microservices",
    version="1.0.0",
    docs_url="/api/v1/customers/docs",
    openapi_url="/api/v1/customers/openapi.json",
    lifespan=lifespan
)

app.include_router(api_router, prefix="/api/v1")
