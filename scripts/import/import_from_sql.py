#!/usr/bin/env python3
"""
Script untuk convert MySQL SQL ke SQLite dan import langsung
Ini akan import SEMUA data dari supergin_db.sql ke SQLite database
"""

import sqlite3
import re

SQL_FILE = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\supergin_db.sql'
import sys
from pathlib import Path
sys.path.insert(0, str(Path(__file__).resolve().parent.parent))
from scripts.config import get_db_path

DB_PATH = get_db_path()

def clean_sql_for_sqlite(sql_content):
    """Convert MySQL SQL to SQLite compatible SQL"""
    
    # Remove MySQL specific commands
    sql_content = re.sub(r'SET .*?;', '', sql_content)
    sql_content = re.sub(r'START TRANSACTION;', '', sql_content)
    sql_content = re.sub(r'/\*!.*?\*/;', '', sql_content, flags=re.DOTALL)
    
    # Remove ENGINE and CHARSET
    sql_content = re.sub(r'ENGINE=\w+', '', sql_content)
    sql_content = re.sub(r'DEFAULT CHARSET=\w+', '', sql_content)
    sql_content = re.sub(r'COLLATE=\w+', '', sql_content)
    sql_content = re.sub(r'AUTO_INCREMENT', '', sql_content)
    sql_content = re.sub(r'UNSIGNED', '', sql_content)
    sql_content = re.sub(r'CHARACTER SET \w+ COLLATE \w+', '', sql_content)
    
    # Skip CREATE TABLE (already exists from migrations)
    sql_content = re.sub(r'CREATE TABLE.*?;', '', sql_content, flags=re.DOTALL)
    
    # Skip ALTER TABLE (migrations handle this)
    sql_content = re.sub(r'ALTER TABLE.*?;', '', sql_content, flags=re.DOTALL)
    
    # Keep only INSERT statements
    insert_pattern = r'INSERT INTO `(\w+)`.*?VALUES\s+(.*?);'
    inserts = re.findall(insert_pattern, sql_content, re.DOTALL)
    
    return inserts

def import_data(conn, table_name, values_str):
    """Import data to SQLite"""
    cursor = conn.cursor()
    
    # Parse VALUES
    # Split by ),( to get individual records
    records = []
    current = ''
    paren_count = 0
    in_string = False
    
    for char in values_str:
        if char == "'" and (not current or current[-1] != '\\'):
            in_string = not in_string
        
        if not in_string:
            if char == '(':
                paren_count += 1
                if paren_count == 1:
                    current = ''
                    continue
            elif char == ')':
                paren_count -= 1
                if paren_count == 0:
                    records.append(current)
                    current = ''
                    continue
        
        if paren_count > 0:
            current += char
    
    # Insert each record
    count = 0
    skipped = 0
    
    for record in records:
        # Parse fields
        fields = []
        current_field = ''
        in_quotes = False
        
        for i, char in enumerate(record):
            if char == "'" and (i == 0 or record[i-1] != '\\'):
                in_quotes = not in_quotes
                continue
            elif char == ',' and not in_quotes:
                field = current_field.strip()
                if field == 'NULL' or field == '':
                    fields.append(None)
                else:
                    fields.append(field)
                current_field = ''
                continue
            current_field += char
        
        # Last field
        field = current_field.strip()
        if field == 'NULL' or field == '':
            fields.append(None)
        else:
            fields.append(field)
        
        # Get column count
        try:
            cursor.execute(f"PRAGMA table_info({table_name})")
            columns = cursor.fetchall()
            col_count = len(columns)
            
            # Build INSERT
            placeholders = ','.join(['?' for _ in range(col_count)])
            query = f"INSERT INTO {table_name} VALUES ({placeholders})"
            
            cursor.execute(query, fields[:col_count])
            count += 1
        except Exception as e:
            skipped += 1
            # print(f"  [SKIP] {table_name}: {e}")
    
    return count, skipped

def main():
    print("=" * 60)
    print("IMPORT MYSQL SQL TO SQLITE")
    print("=" * 60)
    
    # Read SQL file
    print(f"\nReading SQL file: {SQL_FILE}")
    with open(SQL_FILE, 'r', encoding='utf-8') as f:
        sql_content = f.read()
    
    # Clean and parse
    print("Parsing SQL statements...")
    inserts = clean_sql_for_sqlite(sql_content)
    
    print(f"Found {len(inserts)} INSERT statements\n")
    
    # Connect to SQLite
    conn = sqlite3.connect(DB_PATH)
    conn.execute("PRAGMA foreign_keys = OFF")
    
    total_imported = 0
    total_skipped = 0
    
    # Import each table
    for table_name, values_str in inserts:
        print(f"Importing {table_name}...", end=' ')
        count, skipped = import_data(conn, table_name, values_str)
        total_imported += count
        total_skipped += skipped
        print(f"[OK] {count} records ({skipped} skipped)")
    
    conn.commit()
    conn.execute("PRAGMA foreign_keys = ON")
    conn.close()
    
    print("\n" + "=" * 60)
    print(f"IMPORT COMPLETED!")
    print(f"Total imported: {total_imported}")
    print(f"Total skipped: {total_skipped}")
    print("=" * 60)

if __name__ == "__main__":
    try:
        main()
    except Exception as e:
        print(f"\n[ERROR] {e}")
        import traceback
        traceback.print_exc()
