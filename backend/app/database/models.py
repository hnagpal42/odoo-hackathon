from pydantic import BaseModel
from typing import Optional, List
from datetime import datetime

class User(BaseModel):
    id: str
    name: str
    email: str
    bio: Optional[str] = None
    is_public: bool = True
    created_at: datetime
    role: str = "user"

class Skill(BaseModel):
    id: int
    name: str

class UserSkill(BaseModel):
    id: int
    user_id: str
    skill_id: int
    type: str  # 'offered' or 'wanted'

class SwapRequest(BaseModel):
    id: int
    from_user: str
    to_user: str
    skill_id: int
    status: str  # 'pending', 'accepted', 'rejected'
    created_at: datetime

class Feedback(BaseModel):
    id: int
    swap_id: int
    rating: int
    created_at: datetime