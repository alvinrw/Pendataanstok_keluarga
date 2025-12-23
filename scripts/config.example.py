import os
from pathlib import Path

# Base paths - adjust these for your environment
BASE_DIR = Path(__file__).resolve().parent.parent
DB_PATH = BASE_DIR / 'database' / 'database.sqlite'
CSV_DIR = BASE_DIR.parent.parent / 'cpanel-upload' / 'csv_data'

# Database configuration
DB_CONFIG = {
    'path': str(DB_PATH),
    'timeout': 30
}

# For backward compatibility
def get_db_path():
    """Get the database path as a string"""
    return str(DB_PATH)

def get_csv_dir():
    """Get the CSV directory path as a string"""
    return str(CSV_DIR)
