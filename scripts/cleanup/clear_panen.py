import sqlite3

import sys
from pathlib import Path
sys.path.insert(0, str(Path(__file__).resolve().parent.parent))
from scripts.config import get_db_path

DB_PATH = get_db_path()

conn = sqlite3.connect(DB_PATH)
cursor = conn.cursor()

print("=" * 60)
print("CLEARING PANEN DATA")
print("=" * 60)

# Get current panen data
cursor.execute("SELECT COUNT(*) FROM panens")
count = cursor.fetchone()[0]
print(f"Current panen records: {count}")

# Delete all panen
cursor.execute("DELETE FROM panens")
print(f"[DELETED] All {count} panen records")

conn.commit()

print("\n" + "=" * 60)
print("RECALCULATING SUMMARIES")
print("=" * 60)

# Recalculate summaries without panen
cursor.execute("SELECT id, nama_kloter FROM kloters")
kloters = cursor.fetchall()

for kloter_id, nama in kloters:
    # Get totals
    cursor.execute("SELECT COALESCE(SUM(jumlah_ayam_dibeli), 0) FROM data_penjualans WHERE kloter_id = ?", (kloter_id,))
    total_terjual = cursor.fetchone()[0]
    
    cursor.execute("SELECT COALESCE(SUM(jumlah_mati), 0) FROM kematian_ayams WHERE kloter_id = ?", (kloter_id,))
    total_mati = cursor.fetchone()[0]
    
    cursor.execute("SELECT jumlah_doc FROM kloters WHERE id = ?", (kloter_id,))
    jumlah_doc = cursor.fetchone()[0]
    
    # Update with 0 panen
    sisa_ayam_hidup = jumlah_doc - total_mati
    stok_tersedia = 0 - total_terjual  # Will be negative if sold without panen
    
    cursor.execute("""
        UPDATE kloters
        SET sisa_ayam_hidup = ?
        WHERE id = ?
    """, (sisa_ayam_hidup, kloter_id))
    
    cursor.execute("""
        UPDATE kloter_summaries
        SET total_panen = 0,
            stok_tersedia = ?,
            last_calculated_at = datetime('now')
        WHERE kloter_id = ?
    """, (stok_tersedia, kloter_id))
    
    print(f"[UPDATED] {nama}: Sisa Kandang={sisa_ayam_hidup}, Stok={stok_tersedia}")

conn.commit()
conn.close()

print("\n" + "=" * 60)
print("PANEN DATA CLEARED!")
print("=" * 60)
print("\nSekarang bisa input panen manual dari browser.")
print("Refresh browser dan coba input panen!")
