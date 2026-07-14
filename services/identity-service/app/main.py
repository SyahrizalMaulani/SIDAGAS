from fastapi import FastAPI
from contextlib import asynccontextmanager
from sqlmodel import SQLModel, select, Session
from .database import engine, get_db
from .models import User, Role, Permission, RolePermissionLink, UserRoleLink
from .auth import get_password_hash
from .api.endpoints import router as api_router

def seed_database():
    with Session(engine) as session:
        # 1. Create default roles
        roles_to_create = ["sistem_admin", "admin_operasional", "pelanggan", "driver", "karyawan"]
        role_map = {}
        for rname in roles_to_create:
            role = session.exec(select(Role).where(Role.name == rname)).first()
            if not role:
                role = Role(name=rname, description=f"Role for {rname}")
                session.add(role)
                session.commit()
                session.refresh(role)
            role_map[rname] = role

        # 2. Create some default permissions for testing
        perms_to_create = ["user.create", "user.read", "user.update", "user.delete", "order.create", "order.update", "order.delete"]
        perm_map = {}
        for pname in perms_to_create:
            perm = session.exec(select(Permission).where(Permission.name == pname)).first()
            if not perm:
                perm = Permission(name=pname, description=f"Permission to {pname}")
                session.add(perm)
                session.commit()
                session.refresh(perm)
            perm_map[pname] = perm

        # 3. Link permissions to admin
        admin_role = role_map["sistem_admin"]
        for perm in perm_map.values():
            link = session.exec(
                select(RolePermissionLink)
                .where(RolePermissionLink.role_id == admin_role.id)
                .where(RolePermissionLink.permission_id == perm.id)
            ).first()
            if not link:
                session.add(RolePermissionLink(role_id=admin_role.id, permission_id=perm.id))
        session.commit()

        # 4. Create default admin user
        admin_user = session.exec(select(User).where(User.email == "admin@sidagas.com")).first()
        if not admin_user:
            admin_user = User(
                name="Sistem Admin",
                email="admin@sidagas.com",
                phone="081234567890",
                password_hash=get_password_hash("S1d4@9G4AsSv1"),
                is_active=True
            )
            session.add(admin_user)
            session.commit()
            session.refresh(admin_user)
            
            # Link admin to role
            session.add(UserRoleLink(user_id=admin_user.id, role_id=admin_role.id))
            session.commit()

@asynccontextmanager
async def lifespan(app: FastAPI):
    # Create tables in the identity schema if they don't exist
    SQLModel.metadata.create_all(engine)
    # Seed default data
    seed_database()
    yield

app = FastAPI(
    title="SIDAGAS Identity Service",
    description="Identity and Auth management for SIDAGAS microservices",
    version="1.0.0",
    docs_url="/api/v1/auth/docs",
    openapi_url="/api/v1/auth/openapi.json",
    lifespan=lifespan
)

# Register endpoints under prefix /api/v1
app.include_router(api_router, prefix="/api/v1")
