from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from sqlmodel import SQLModel
from contextlib import asynccontextmanager

from .database import engine
from .api.endpoints import router as api_router
from .events import run_consumer_background

@asynccontextmanager
async def lifespan(app: FastAPI):
    # Create tables
    SQLModel.metadata.create_all(engine)
    
    # Run RabbitMQ consumer in background
    run_consumer_background()
    
    yield

app = FastAPI(
    title="SIDAGAS Payment Service",
    description="Microservice managing payments, confirmations, and verification.",
    version="1.0.0",
    lifespan=lifespan,
    docs_url="/api/v1/payments/docs",
    openapi_url="/api/v1/payments/openapi.json"
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(api_router, prefix="/api/v1/payments", tags=["Payments"])

@app.get("/health")
def health_check():
    return {"status": "healthy", "service": "payment-service"}
