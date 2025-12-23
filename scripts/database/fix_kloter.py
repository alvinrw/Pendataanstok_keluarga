#!/usr/bin/env python3
"""
Script untuk insert kloter 8 dan 9 yang gagal import
"""

import sqlite3

import sys
from pathlib import Path
sys.path.insert(0, str(Path(__file__).resolve().parent.parent))
from scripts.config import get_db_path

DB_PATH = get_db_path()

conn = sqlite3.connect(DB_PATH)
cursor = conn.cursor()

try:
    # Insert Kloter 8
    cursor.execute("""
        INSERT INTO kloters (id, nama_kloter, status, tanggal_mulai, jumlah_doc, 
                            sisa_ayam_hidup, harga_beli_doc, total_pengeluaran,
                            stok_awal, stok_tersedia, total_terjual, total_berat,
                            total_pemasukan, created_at, updated_at, tanggal_panen, harga_jual_total)
        VALUES (8, 'Kloter 2 chickin 13 okt 2025', 'Aktif', '2025-10-13', 108, 
                0, 500000, 2096000, 0, 89, 12, 9.27, 695000.00, 
                '2025-10-21 05:19:23', '2025-12-19 13:26:31', NULL, NULL)
    """)
    print("[OK] Kloter 8 inserted")
    
    # Insert Kloter 9
    cursor.execute("""
        INSERT INTO kloters (id, nama_kloter, status, tanggal_mulai, jumlah_doc, 
                            sisa_ayam_hidup, harga_beli_doc, total_pengeluaran,
                            stok_awal, stok_tersedia, total_terjual, total_berat,
                            total_pemasukan, created_at, updated_at, tanggal_panen, harga_jual_total)
        VALUES (9, 'Kloter 3', 'Aktif', '2025-12-15', 102, 
                102, 500000, 1256000, 0, 0, 0, 0.00, 0.00, 
                '2025-12-12 23:06:55', '2025-12-17 19:08:23', NULL, NULL)
    """)
    print("[OK] Kloter 9 inserted")
    
    conn.commit()
    print("\n[DONE] Semua kloter berhasil ditambahkan!")
    print("Refresh browser untuk melihat perubahan.")
    
except sqlite3.IntegrityError as e:
    print(f"[WARN] Kloter sudah ada: {e}")
except Exception as e:
    print(f"[ERROR] Error: {e}")
    conn.rollback()
finally:
    conn.close()
