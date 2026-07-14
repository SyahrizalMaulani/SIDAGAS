from pydantic_settings import BaseSettings

class Settings(BaseSettings):
    DB_HOST: str
    DB_PORT: int = 5432
    DB_NAME: str = "postgres"
    DB_USER: str
    DB_PASSWORD: str
    DB_SCHEMA: str = "catalog"

    class Config:
        env_file = ".env"
        env_file_encoding = "utf-8"

settings = Settings()
