from fastapi import APIRouter, HTTPException
from database.mysql_client import db
from database.models import Skill, UserSkill

router = APIRouter(prefix="/skills", tags=["skills"])

@router.get("/user/{user_id}")
async def get_user_skills(user_id: str, type: str = None):
    query = """
    SELECT s.id, s.name, us.type 
    FROM user_skills us
    JOIN skills s ON us.skill_id = s.id
    WHERE us.user_id = %s
    """
    params = [user_id]
    
    if type:
        query += " AND us.type = %s"
        params.append(type)
        
    with db.get_connection() as conn:
        cursor = conn.cursor(dictionary=True)
        cursor.execute(query, params)
        return cursor.fetchall()

@router.post("/add")
async def add_skill_to_user(skill: UserSkill):
    query = """
    INSERT INTO user_skills (user_id, skill_id, type)
    VALUES (%s, %s, %s)
    """
    with db.get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute(query, (skill.user_id, skill.skill_id, skill.type))
        conn.commit()
        return {"message": "Skill added successfully"}