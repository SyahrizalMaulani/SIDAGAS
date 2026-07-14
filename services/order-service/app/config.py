from pydantic_settings import BaseSettings
import urllib.parse

class Settings(BaseSettings):
    DB_HOST: str
    DB_PORT: str = "5432"
    DB_NAME: str = "postgres"
    DB_USER: str
    DB_PASSWORD: str
    DB_SCHEMA: str = "orders"
    RABBITMQ_URL: str

    @property
    def DATABASE_URL(self) -> str:
        quoted_user = urllib.parse.quote_plus(self.DB_USER)
        quoted_password = urllib.parse.quote_plus(self.DB_PASSWORD)
        return f"postgresql://{quoted_user}:{quoted_password}@{self.DB_HOST}:{self.DB_PORT}/{self.DB_NAME}?options=-csearch_path%3D{self.DB_SCHEMA}"

    class Config:
        env_file = ".env"
        extra = "ignore"

settings = Settings()
