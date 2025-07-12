from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI()

# CORS Configuration
app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost"],
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/skills/user/{user_id}")
async def get_skills(user_id: str):
    # Mock data - replace with database queries
    return [
        {"name": "Python", "type": "offered"},
        {"name": "Graphic Design", "type": "wanted"}
    ]