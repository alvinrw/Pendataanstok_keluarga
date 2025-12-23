import sqlite3
import csv

import sys
from pathlib import Path
sys.path.insert(0, str(Path(__file__).resolve().parent.parent))
from scripts.config import get_db_path

DB_PATH = get_db_path()
import sys
from pathlib import Path
sys.path.insert(0, str(Path(__file__).resolve().parent.parent))
from scripts.config import get_csv_dir

CSV_FILE = str(Path(get_csv_dir()) / 'data_penjualans.csv')

conn = sqlite3.connect(DB_PATH)
cursor = conn.cursor()

print("=" * 60)
print("IMPORTING PENJUALAN DATA FOR KLOTER 2 (ID=8)")
print("=" * 60)

# Get Kloter 2 ID from database (assuming it's already created)
cursor.execute("SELECT id, nama_kloter FROM kloters ORDER BY id")
kloters = cursor.fetchall()

print("\nKLOTERS IN DATABASE:")
for k in kloters:
    print(f"  ID={k[0]}: {k[1]}")

# Find kloter with "chickin 13 okt" or similar
cursor.execute("SELECT id, nama_kloter FROM kloters WHERE nama_kloter LIKE '%chickin%' OR nama_kloter LIKE '%13 okt%' OR nama_kloter LIKE '%Kloter 2%' ORDER BY id LIMIT 1")
kloter_row = cursor.fetchone()

if not kloter_row:
    print("\n[INFO] Kloter 2 not found by name, checking if user created it manually...")
    # Maybe user created with different name, let's check latest kloter
    cursor.execute("SELECT id, nama_kloter FROM kloters ORDER BY id DESC LIMIT 1")
    kloter_row = cursor.fetchone()
    
    if kloter_row:
        print(f"\n[USING] Latest Kloter: ID={kloter_row[0]}, Name={kloter_row[1]}")
        confirm = input("Is this Kloter 2? (y/n): ")
        if confirm.lower() != 'y':
            print("[ABORTED] Please create Kloter 2 first!")
            conn.close()
            exit()
    else:
        print("[ERROR] No kloters found in database!")
        conn.close()
        exit()

kloter_id_in_db = kloter_row[0]
kloter_name = kloter_row[1]

print(f"\n[IMPORTING TO] Kloter: ID={kloter_id_in_db}, Name={kloter_name}")

# Read CSV and import penjualan for kloter_id = 8 (from old database)
with open(CSV_FILE, 'r', encoding='utf-8') as f:
    reader = csv.DictReader(f)
    count = 0
    skipped = 0
    
    for row in reader:
        # Only import if kloter_id = 8 (Kloter 2 in old database)
        if row['kloter_id'] != '8':
            continue
        
        try:
            cursor.execute("""
                INSERT INTO data_penjualans (kloter_id, tanggal, nama_pembeli, 
                                            jumlah_ayam_dibeli, berat_total, 
                                            harga_asli, diskon, harga_total,
                                            created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            """, (
                kloter_id_in_db,  # Use new kloter ID
                row['tanggal'],
                row['nama_pembeli'],
                row['jumlah_ayam_dibeli'],
                row['berat_total'],
                row['harga_asli'],
                row['diskon'],
                row['harga_total'],
                row['created_at'],
                row['updated_at'],
            ))
            
            count += 1
            print(f"[OK] {row['nama_pembeli']}: {row['jumlah_ayam_dibeli']} ekor, Rp {int(float(row['harga_total'])):,}")
            
        except Exception as e:
            skipped += 1
            print(f"[SKIP] {row.get('nama_pembeli', 'Unknown')}: {e}")

conn.commit()

print("\n" + "=" * 60)
print(f"IMPORT COMPLETED!")
print(f"Imported: {count} penjualan")
print(f"Skipped: {skipped} penjualan")
print("=" * 60)

# Recalculate summary
print(f"\n[RECALCULATING] Summary for {kloter_name}...")

cursor.execute("SELECT COALESCE(SUM(jumlah_panen), 0) FROM panens WHERE kloter_id = ?", (kloter_id_in_db,))
total_panen = cursor.fetchone()[0]

cursor.execute("SELECT COALESCE(SUM(jumlah_ayam_dibeli), 0) FROM data_penjualans WHERE kloter_id = ?", (kloter_id_in_db,))
total_terjual = cursor.fetchone()[0]

cursor.execute("SELECT COALESCE(SUM(berat_total), 0) FROM data_penjualans WHERE kloter_id = ?", (kloter_id_in_db,))
total_berat = cursor.fetchone()[0] / 1000

cursor.execute("SELECT COALESCE(SUM(harga_total), 0) FROM data_penjualans WHERE kloter_id = ?", (kloter_id_in_db,))
total_pemasukan = cursor.fetchone()[0]

stok_tersedia = total_panen - total_terjual

cursor.execute("""
    UPDATE kloter_summaries
    SET total_terjual = ?,
        total_berat_kg = ?,
        total_pemasukan = ?,
        stok_tersedia = ?,
        last_calculated_at = datetime('now')
    WHERE kloter_id = ?
""", (total_terjual, total_berat, total_pemasukan, stok_tersedia, kloter_id_in_db))

conn.commit()
conn.close()

print(f"\n[UPDATED] {kloter_name}:")
print(f"  Panen: {total_panen} ekor")
print(f"  Terjual: {total_terjual} ekor")
print(f"  Stok Siap Jual: {stok_tersedia} ekor")
print(f"  Total Pemasukan: Rp {total_pemasukan:,.0f}")

print("\n" + "=" * 60)
print("DONE! Refresh browser to see changes!")
print("=" * 60)
