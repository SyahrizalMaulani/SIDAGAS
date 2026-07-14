from datetime import datetime
from typing import Optional, List
from sqlmodel import SQLModel, Field, Relationship
import uuid

# --- Relationship Tables ---
class UserRoleLink(SQLModel, table=True):
    __tablename__ = "user_roles"
    __table_args__ = {"schema": "identity"}
    
    user_id: uuid.UUID = Field(foreign_key="identity.users.id", primary_key=True)
    role_id: uuid.UUID = Field(foreign_key="identity.roles.id", primary_key=True)

class RolePermissionLink(SQLModel, table=True):
    __tablename__ = "role_permissions"
    __table_args__ = {"schema": "identity"}
    
    role_id: uuid.UUID = Field(foreign_key="identity.roles.id", primary_key=True)
    permission_id: uuid.UUID = Field(foreign_key="identity.permissions.id", primary_key=True)

# --- Core Tables ---
class Permission(SQLModel, table=True):
    __tablename__ = "permissions"
    __table_args__ = {"schema": "identity"}

    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    name: str = Field(unique=True, index=True)
    description: Optional[str] = None
    created_at: datetime = Field(default_factory=datetime.utcnow)

    # Relationships
    roles: List["Role"] = Relationship(back_populates="permissions", link_model=RolePermissionLink)

class Role(SQLModel, table=True):
    __tablename__ = "roles"
    __table_args__ = {"schema": "identity"}

    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    name: str = Field(unique=True, index=True)
    description: Optional[str] = None
    created_at: datetime = Field(default_factory=datetime.utcnow)

    # Relationships
    users: List["User"] = Relationship(back_populates="roles", link_model=UserRoleLink)
    permissions: List["Permission"] = Relationship(back_populates="roles", link_model=RolePermissionLink)

class User(SQLModel, table=True):
    __tablename__ = "users"
    __table_args__ = {"schema": "identity"}

    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    name: str
    email: str = Field(unique=True, index=True)
    phone: Optional[str] = None
    password_hash: str
    is_active: bool = Field(default=True)
    created_at: datetime = Field(default_factory=datetime.utcnow)
    updated_at: datetime = Field(default_factory=datetime.utcnow)

    # Relationships
    roles: List[Role] = Relationship(back_populates="users", link_model=UserRoleLink)

class RefreshToken(SQLModel, table=True):
    __tablename__ = "refresh_tokens"
    __table_args__ = {"schema": "identity"}

    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    user_id: uuid.UUID = Field(foreign_key="identity.users.id")
    token: str = Field(unique=True, index=True)
    expires_at: datetime
    created_at: datetime = Field(default_factory=datetime.utcnow)

class LoginLog(SQLModel, table=True):
    __tablename__ = "login_logs"
    __table_args__ = {"schema": "identity"}

    id: uuid.UUID = Field(default_factory=uuid.uuid4, primary_key=True)
    user_id: Optional[uuid.UUID] = Field(default=None, foreign_key="identity.users.id", nullable=True)
    email_attempted: str
    ip_address: Optional[str] = None
    user_agent: Optional[str] = None
    status: str = Field(description="success / failed")
    created_at: datetime = Field(default_factory=datetime.utcnow)
