from fastapi import APIRouter, Depends, HTTPException, status, Request
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from sqlmodel import Session, select
from datetime import datetime
import uuid
from typing import List, Optional

from ..database import get_db
from ..models import User, Role, Permission, RefreshToken, LoginLog, UserRoleLink
from ..auth import verify_password, get_password_hash, create_access_token, create_refresh_token, decode_token
from pydantic import BaseModel, EmailStr

router = APIRouter()
security = HTTPBearer()

# --- Pydantic Schemas ---
class LoginRequest(BaseModel):
    email: EmailStr
    password: str

class TokenResponse(BaseModel):
    access_token: str
    refresh_token: str
    token_type: str = "bearer"

class RefreshRequest(BaseModel):
    refresh_token: str

class LogoutRequest(BaseModel):
    refresh_token: str

class PermissionResponse(BaseModel):
    id: uuid.UUID
    name: str
    description: Optional[str]

class RoleResponse(BaseModel):
    id: uuid.UUID
    name: str
    description: Optional[str]
    permissions: List[PermissionResponse]

class UserProfileResponse(BaseModel):
    id: uuid.UUID
    name: str
    email: str
    phone: Optional[str]
    is_active: bool
    roles: List[RoleResponse]
    permissions: List[str]

class UserCreateRequest(BaseModel):
    name: str
    email: EmailStr
    phone: Optional[str] = None
    password: str
    role_names: List[str]

class UserUpdateRequest(BaseModel):
    name: Optional[str] = None
    phone: Optional[str] = None
    is_active: Optional[bool] = None
    role_names: Optional[List[str]] = None

# --- Auth Dependencies ---
def get_current_user(credentials: HTTPAuthorizationCredentials = Depends(security), db: Session = Depends(get_db)) -> User:
    token = credentials.credentials
    payload = decode_token(token)
    if not payload or payload.get("type") != "access":
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Could not validate credentials",
            headers={"WWW-Authenticate": "Bearer"},
        )
    user_id = payload.get("sub")
    if not user_id:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Could not validate credentials",
            headers={"WWW-Authenticate": "Bearer"},
        )
    user = db.get(User, uuid.UUID(user_id))
    if not user or not user.is_active:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="User not active or not found",
        )
    return user

def require_admin(current_user: User = Depends(get_current_user)) -> User:
    is_admin = any(role.name == "sistem_admin" for role in current_user.roles)
    if not is_admin:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Operational permission denied: Admin access required",
        )
    return current_user

# --- Endpoints ---

@router.post("/auth/login", response_model=TokenResponse)
def login(req: LoginRequest, request: Request, db: Session = Depends(get_db)):
    ip = request.client.host if request.client else None
    agent = request.headers.get("user-agent")

    user = db.exec(select(User).where(User.email == req.email)).first()
    if not user or not verify_password(req.password, user.password_hash):
        # Log failed attempt
        failed_log = LoginLog(
            email_attempted=req.email,
            user_id=user.id if user else None,
            ip_address=ip,
            user_agent=agent,
            status="failed"
        )
        db.add(failed_log)
        db.commit()
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Incorrect email or password",
        )

    if not user.is_active:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="User account is deactivated",
        )

    # Log successful login
    success_log = LoginLog(
        email_attempted=req.email,
        user_id=user.id,
        ip_address=ip,
        user_agent=agent,
        status="success"
    )
    db.add(success_log)

    # Create tokens
    access = create_access_token(user.id)
    refresh_val = create_refresh_token(user.id)

    # Store refresh token
    refresh_token_record = RefreshToken(
        user_id=user.id,
        token=refresh_val,
        expires_at=datetime.utcnow() + timedelta(days=7) if 'timedelta' in globals() else datetime.utcnow() # we can fetch value safely
    )
    # Calculate expiry
    from ..config import settings
    import datetime as dt
    refresh_token_record.expires_at = dt.datetime.utcnow() + dt.timedelta(days=settings.REFRESH_TOKEN_EXPIRE_DAYS)

    db.add(refresh_token_record)
    db.commit()

    return TokenResponse(access_token=access, refresh_token=refresh_val)

@router.post("/auth/refresh", response_model=TokenResponse)
def refresh(req: RefreshRequest, db: Session = Depends(get_db)):
    payload = decode_token(req.refresh_token)
    if not payload or payload.get("type") != "refresh":
        raise HTTPException(status_code=401, detail="Invalid refresh token")
    
    user_id = payload.get("sub")
    # Verify token exists in db
    token_record = db.exec(select(RefreshToken).where(RefreshToken.token == req.refresh_token)).first()
    if not token_record or token_record.expires_at < datetime.utcnow():
        if token_record:
            db.delete(token_record)
            db.commit()
        raise HTTPException(status_code=401, detail="Refresh token expired or invalid")

    # Generate new tokens
    access = create_access_token(user_id)
    new_refresh = create_refresh_token(user_id)

    # Swap refresh token
    db.delete(token_record)
    
    from ..config import settings
    import datetime as dt
    new_token_record = RefreshToken(
        user_id=uuid.UUID(user_id),
        token=new_refresh,
        expires_at=dt.datetime.utcnow() + dt.timedelta(days=settings.REFRESH_TOKEN_EXPIRE_DAYS)
    )
    db.add(new_token_record)
    db.commit()

    return TokenResponse(access_token=access, refresh_token=new_refresh)

@router.post("/auth/logout")
def logout(req: LogoutRequest, db: Session = Depends(get_db)):
    token_record = db.exec(select(RefreshToken).where(RefreshToken.token == req.refresh_token)).first()
    if token_record:
        db.delete(token_record)
        db.commit()
    return {"message": "Successfully logged out"}

@router.get("/auth/me", response_model=UserProfileResponse)
def me(user: User = Depends(get_current_user)):
    permissions_list = []
    roles_list = []
    
    for role in user.roles:
        perms = [PermissionResponse(id=p.id, name=p.name, description=p.description) for p in role.permissions]
        roles_list.append(RoleResponse(id=role.id, name=role.name, description=role.description, permissions=perms))
        for p in role.permissions:
            permissions_list.append(p.name)

    return UserProfileResponse(
        id=user.id,
        name=user.name,
        email=user.email,
        phone=user.phone,
        is_active=user.is_active,
        roles=roles_list,
        permissions=list(set(permissions_list))
    )

# --- Admin User CRUD ---

@router.get("/users", response_model=List[UserProfileResponse])
def get_users(admin: User = Depends(require_admin), db: Session = Depends(get_db)):
    users = db.exec(select(User)).all()
    res = []
    for user in users:
        permissions_list = []
        roles_list = []
        for role in user.roles:
            perms = [PermissionResponse(id=p.id, name=p.name, description=p.description) for p in role.permissions]
            roles_list.append(RoleResponse(id=role.id, name=role.name, description=role.description, permissions=perms))
            for p in role.permissions:
                permissions_list.append(p.name)
        res.append(UserProfileResponse(
            id=user.id,
            name=user.name,
            email=user.email,
            phone=user.phone,
            is_active=user.is_active,
            roles=roles_list,
            permissions=list(set(permissions_list))
        ))
    return res

@router.post("/users", response_model=UserProfileResponse)
def create_user(req: UserCreateRequest, admin: User = Depends(require_admin), db: Session = Depends(get_db)):
    # Check email exists
    existing = db.exec(select(User).where(User.email == req.email)).first()
    if existing:
        raise HTTPException(status_code=400, detail="Email already registered")

    new_user = User(
        name=req.name,
        email=req.email,
        phone=req.phone,
        password_hash=get_password_hash(req.password),
        is_active=True
    )
    db.add(new_user)
    db.commit()
    db.refresh(new_user)

    # Assign roles
    for role_name in req.role_names:
        role = db.exec(select(Role).where(Role.name == role_name)).first()
        if not role:
            # Create role if not exist
            role = Role(name=role_name, description=f"Role {role_name}")
            db.add(role)
            db.commit()
            db.refresh(role)
        
        # Link user to role
        link = UserRoleLink(user_id=new_user.id, role_id=role.id)
        db.add(link)
    
    db.commit()
    db.refresh(new_user)

    permissions_list = []
    roles_list = []
    for role in new_user.roles:
        perms = [PermissionResponse(id=p.id, name=p.name, description=p.description) for p in role.permissions]
        roles_list.append(RoleResponse(id=role.id, name=role.name, description=role.description, permissions=perms))
        for p in role.permissions:
            permissions_list.append(p.name)

    return UserProfileResponse(
        id=new_user.id,
        name=new_user.name,
        email=new_user.email,
        phone=new_user.phone,
        is_active=new_user.is_active,
        roles=roles_list,
        permissions=list(set(permissions_list))
    )

@router.put("/users/{user_id}", response_model=UserProfileResponse)
def update_user(user_id: uuid.UUID, req: UserUpdateRequest, admin: User = Depends(require_admin), db: Session = Depends(get_db)):
    user = db.get(User, user_id)
    if not user:
        raise HTTPException(status_code=404, detail="User not found")

    if req.name is not None:
        user.name = req.name
    if req.phone is not None:
        user.phone = req.phone
    if req.is_active is not None:
        user.is_active = req.is_active

    db.add(user)
    db.commit()

    if req.role_names is not None:
        # Clear existing role links
        existing_links = db.exec(select(UserRoleLink).where(UserRoleLink.user_id == user.id)).all()
        for link in existing_links:
            db.delete(link)
        db.commit()

        # Add new links
        for role_name in req.role_names:
            role = db.exec(select(Role).where(Role.name == role_name)).first()
            if not role:
                role = Role(name=role_name, description=f"Role {role_name}")
                db.add(role)
                db.commit()
                db.refresh(role)
            link = UserRoleLink(user_id=user.id, role_id=role.id)
            db.add(link)
        db.commit()

    db.refresh(user)

    permissions_list = []
    roles_list = []
    for role in user.roles:
        perms = [PermissionResponse(id=p.id, name=p.name, description=p.description) for p in role.permissions]
        roles_list.append(RoleResponse(id=role.id, name=role.name, description=role.description, permissions=perms))
        for p in role.permissions:
            permissions_list.append(p.name)

    return UserProfileResponse(
        id=user.id,
        name=user.name,
        email=user.email,
        phone=user.phone,
        is_active=user.is_active,
        roles=roles_list,
        permissions=list(set(permissions_list))
    )
