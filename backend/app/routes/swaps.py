from fastapi import APIRouter, HTTPException
from database.mysql_client import db
from database.models import SwapRequest, Feedback

router = APIRouter(prefix="/swaps", tags=["swaps"])

@router.post("/request")
async def create_swap_request(request: SwapRequest):
    query = """
    INSERT INTO swap_requests 
    (from_user, to_user, skill_id, status)
    VALUES (%s, %s, %s, 'pending')
    """
    with db.get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute(query, 
            (request.from_user, request.to_user, request.skill_id))
        conn.commit()
        return {"message": "Swap request created"}

@router.post("/{swap_id}/feedback")
async def add_feedback(swap_id: int, feedback: Feedback):
    query = """
    INSERT INTO feedback (swap_id, rating)
    VALUES (%s, %s)
    """
    with db.get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute(query, (swap_id, feedback.rating))
        conn.commit()
        return {"message": "Feedback added"}