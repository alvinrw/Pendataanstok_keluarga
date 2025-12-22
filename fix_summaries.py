import sqlite3

DB_PATH = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\database.sqlite'

conn = sqlite3.connect(DB_PATH)
cursor = conn.cursor()

print("=" * 60)
print("CREATING MISSING KLOTER SUMMARIES")
print("=" * 60)

# Get all kloters
cursor.execute("SELECT id, nama_kloter FROM kloters")
kloters = cursor.fetchall()

for kloter_id, nama in kloters:
    # Check if summary exists
    cursor.execute("SELECT COUNT(*) FROM kloter_summaries WHERE kloter_id = ?", (kloter_id,))
    exists = cursor.fetchone()[0]
    
    if exists == 0:
        print(f"[CREATING] Summary for Kloter {kloter_id}: {nama}")
        
        # Create summary
        cursor.execute("""
            INSERT INTO kloter_summaries (kloter_id, total_terjual, total_berat_kg, 
                                         total_pemasukan, total_pengeluaran, total_mati,
                                         total_panen, stok_tersedia, last_calculated_at)
            VALUES (?, 0, 0, 0, 0, 0, 0, 0, datetime('now'))
        """, (kloter_id,))
    else:
        print(f"[EXISTS] Summary for Kloter {kloter_id}: {nama}")

conn.commit()

print("\n" + "=" * 60)
print("RECALCULATING ALL SUMMARIES")
print("=" * 60)

# Recalculate
for kloter_id, nama in kloters:
    cursor.execute("SELECT COALESCE(SUM(jumlah_panen), 0) FROM panens WHERE kloter_id = ?", (kloter_id,))
    total_panen = cursor.fetchone()[0]
    
    cursor.execute("SELECT COALESCE(SUM(jumlah_ayam_dibeli), 0) FROM data_penjualans WHERE kloter_id = ?", (kloter_id,))
    total_terjual = cursor.fetchone()[0]
    
    stok_tersedia = total_panen - total_terjual
    
    cursor.execute("""
        UPDATE kloter_summaries
        SET total_panen = ?,
            total_terjual = ?,
            stok_tersedia = ?,
            last_calculated_at = datetime('now')
        WHERE kloter_id = ?
    """, (total_panen, total_terjual, stok_tersedia, kloter_id))
    
    print(f"[UPDATED] {nama}: Panen={total_panen}, Terjual={total_terjual}, Stok={stok_tersedia}")

conn.commit()
conn.close()

print("\n" + "=" * 60)
print("DONE! Refresh browser!")
print("=" * 60)
