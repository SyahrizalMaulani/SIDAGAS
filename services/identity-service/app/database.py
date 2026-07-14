import urllib.parse
from sqlmodel import create_engine, Session
from .config import settings

# URL-encode user and password to handle special characters (e.g. '@' in password)
user = urllib.parse.quote_plus(settings.DB_USER)
password = urllib.parse.quote_plus(settings.DB_PASSWORD)

db_url = (
    f"postgresql://{user}:{password}@{settings.DB_HOST}:{settings.DB_PORT}/{settings.DB_NAME}"
    f"?options=-csearch_path%3D{settings.DB_SCHEMA}"
)

# Create engine
engine = create_engine(
    db_url,
    echo=False,
    pool_pre_ping=True,
    pool_size=5,
    max_overflow=10
)

def get_db():
    with Session(engine) as session:
        yield session
