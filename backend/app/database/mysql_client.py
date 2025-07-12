import mysql.connector
from mysql.connector import Error
import os
from dotenv import load_dotenv
from contextlib import contextmanager

load_dotenv()

class MySQLConnector:
    def __init__(self):
        self.config = {
            'host': os.getenv("MYSQL_HOST", "localhost"),
            'database': os.getenv("MYSQL_DATABASE", "skill_swap"),
            'user': os.getenv("MYSQL_USER", "root"),
            'password': os.getenv("MYSQL_PASSWORD", ""),
            'raise_on_warnings': True
        }

    @contextmanager
    def get_connection(self):
        conn = None
        try:
            conn = mysql.connector.connect(**self.config)
            yield conn
        except Error as e:
            print(f"MySQL Error: {e}")
            raise
        finally:
            if conn and conn.is_connected():
                conn.close()

db = MySQLConnector()