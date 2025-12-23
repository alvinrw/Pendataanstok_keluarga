#!/usr/bin/env python3
"""
Script untuk import SEMUA data CSV ke SQLite database
Langsung pakai Python, lebih reliable daripada Laravel seeder
"""

import sqlite3
import csv
import os
from datetime import datetime

# Path ke database SQLite
import sys
from pathlib import Path
sys.path.insert(0, str(Path(__file__).resolve().parent.parent))
from scripts.config import get_db_path

DB_PATH = get_db_path()
CSV_DIR = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\seeders\data'

def connect_db():
    """Connect ke database SQLite"""
    try:
        conn = sqlite3.connect(DB_PATH)
        conn.execute("PRAGMA foreign_keys = OFF")  # Disable foreign key sementara
        return conn
    except Exception as e:
        print(f"Error connecting to database: {e}")
        exit(1)

def clean_value(val):
    """Clean CSV value"""
    if val is None or val == '' or val == 'NULL':
        return None
    return val.strip()

def import_kloters(conn):
    """Import kloters"""
    csv_file = os.path.join(CSV_DIR, 'kloters.csv')
    print(f"\n[1/10] Importing kloters...")
    
    cursor = conn.cursor()
    
    with open(csv_file, 'r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        count = 0
        
        for row in reader:
            try:
                cursor.execute("""
                    INSERT INTO kloters (id, nama_kloter, status, tanggal_mulai, jumlah_doc, 
                                        sisa_ayam_hidup, harga_beli_doc, total_pengeluaran,
                                        stok_awal, stok_tersedia, total_terjual, total_berat,
                                        total_pemasukan, created_at, updated_at, tanggal_panen, harga_jual_total)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                """, (
                    clean_value(row['id']),
                    clean_value(row['nama_kloter']),
                    clean_value(row['status']),
                    clean_value(row['tanggal_mulai']),
                    clean_value(row['jumlah_doc']),
                    clean_value(row['sisa_ayam_hidup']),
                    clean_value(row['harga_beli_doc']),
                    clean_value(row['total_pengeluaran']),
                    clean_value(row['stok_awal']),
                    clean_value(row['stok_tersedia']),
                    clean_value(row['total_terjual']),
                    clean_value(row['total_berat']),
                    clean_value(row['total_pemasukan']),
                    clean_value(row['created_at']),
                    clean_value(row['updated_at']),
                    clean_value(row['tanggal_panen']),
                    clean_value(row['harga_jual_total'])
                ))
                count += 1
            except Exception as e:
                print(f"  [SKIP] Row {count}: {e}")
    
    conn.commit()
    print(f"  [OK] Imported {count} kloters")
    return count

def import_data_penjualans(conn):
    """Import data_penjualans - TANPA Observer (manual import)"""
    csv_file = os.path.join(CSV_DIR, 'data_penjualans.csv')
    print(f"\n[2/10] Importing data_penjualans...")
    
    cursor = conn.cursor()
    
    with open(csv_file, 'r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        count = 0
        
        for row in reader:
            try:
                cursor.execute("""
                    INSERT INTO data_penjualans (id, kloter_id, tanggal, nama_pembeli,
                                                jumlah_ayam_dibeli, berat_total, harga_asli,
                                                diskon, harga_total, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                """, (
                    clean_value(row['id']),
                    clean_value(row['kloter_id']),
                    clean_value(row['tanggal']),
                    clean_value(row['nama_pembeli']),
                    clean_value(row['jumlah_ayam_dibeli']),
                    clean_value(row['berat_total']),
                    clean_value(row['harga_asli']),
                    clean_value(row['diskon']),
                    clean_value(row['harga_total']),
                    clean_value(row['created_at']),
                    clean_value(row['updated_at'])
                ))
                count += 1
            except Exception as e:
                print(f"  [SKIP] Row {count}: {e}")
    
    conn.commit()
    print(f"  [OK] Imported {count} data_penjualans")
    return count

def import_other_tables(conn):
    """Import tabel lainnya"""
    tables = [
        ('pengeluarans', ['id', 'kloter_id', 'kategori', 'jumlah_pengeluaran', 'jumlah_pakan_kg', 'tanggal_pengeluaran', 'catatan', 'created_at', 'updated_at']),
        ('kematian_ayams', ['id', 'kloter_id', 'jumlah_mati', 'tanggal_kematian', 'catatan', 'created_at', 'updated_at']),
        ('panens', ['id', 'kloter_id', 'jumlah_panen', 'tanggal_panen', 'created_at', 'updated_at']),
        ('penjualan_tokos', ['id', 'tanggal', 'total_harga', 'catatan', 'created_at', 'updated_at']),
        ('summaries', ['id', 'stok_ayam', 'total_ayam_terjual', 'total_berat_tertimbang', 'total_pemasukan', 'created_at', 'updated_at']),
        ('transaksis', ['id', 'tanggal', 'kategori', 'jumlah', 'deskripsi', 'created_at', 'updated_at']),
        ('jadwals', ['id', 'activity_name', 'date', 'start_time', 'location', 'description', 'priority', 'created_at', 'updated_at', 'status']),
    ]
    
    total_imported = 0
    for idx, (table_name, columns) in enumerate(tables, start=3):
        csv_file = os.path.join(CSV_DIR, f'{table_name}.csv')
        
        if not os.path.exists(csv_file):
            print(f"\n[{idx}/10] Skipping {table_name} (file not found)")
            continue
        
        print(f"\n[{idx}/10] Importing {table_name}...")
        cursor = conn.cursor()
        count = 0
        
        try:
            with open(csv_file, 'r', encoding='utf-8') as f:
                reader = csv.DictReader(f)
                
                for row in reader:
                    try:
                        placeholders = ','.join(['?' for _ in columns])
                        cols_str = ','.join(columns)
                        values = [clean_value(row.get(col)) for col in columns]
                        
                        cursor.execute(f"INSERT INTO {table_name} ({cols_str}) VALUES ({placeholders})", values)
                        count += 1
                    except Exception as e:
                        print(f"  [SKIP] Row: {e}")
            
            conn.commit()
            print(f"  [OK] Imported {count} {table_name}")
            total_imported += count
        except Exception as e:
            print(f"  [ERROR] {table_name}: {e}")
    
    return total_imported

def main():
    print("=" * 60)
    print("IMPORT ALL CSV DATA TO SQLITE")
    print("=" * 60)
    
    conn = connect_db()
    
    try:
        # Import dalam urutan yang benar (foreign keys)
        kloters_count = import_kloters(conn)
        penjualans_count = import_data_penjualans(conn)
        others_count = import_other_tables(conn)
        
        total = kloters_count + penjualans_count + others_count
        
        print("\n" + "=" * 60)
        print(f"IMPORT COMPLETED: {total} total records imported")
        print("=" * 60)
        
    except Exception as e:
        print(f"\n[ERROR] {e}")
        conn.rollback()
    finally:
        conn.execute("PRAGMA foreign_keys = ON")  # Re-enable foreign keys
        conn.close()

if __name__ == "__main__":
    main()
