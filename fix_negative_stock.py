import sqlite3

DB_PATH = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\database.sqlite'

conn = sqlite3.connect(DB_PATH)
cursor = conn.cursor()

print("=" * 60)
print("CHECKING KLOTER SUMMARIES")
print("=" * 60)

# Get all summaries
cursor.execute("""
    SELECT k.id, k.nama_kloter, ks.total_panen, ks.total_terjual, ks.stok_tersedia
    FROM kloters k
    LEFT JOIN kloter_summaries ks ON k.id = ks.kloter_id
    ORDER BY k.id
""")

for row in cursor.fetchall():
    kloter_id, nama, panen, terjual, stok = row
    print(f"\nKloter {kloter_id}: {nama}")
    print(f"  Panen: {panen or 0}")
    print(f"  Terjual: {terjual or 0}")
    print(f"  Stok: {stok or 0}")
    
    if stok and stok < 0:
        print(f"  [NEGATIVE!] Need to create panen: {abs(stok)} ekor")

print("\n" + "=" * 60)
print("FIXING NEGATIVE STOCKS")
print("=" * 60)

# Fix negative stocks
cursor.execute("""
    SELECT k.id, k.nama_kloter, k.tanggal_mulai, ks.total_panen, ks.total_terjual, ks.stok_tersedia
    FROM kloters k
    JOIN kloter_summaries ks ON k.id = ks.kloter_id
    WHERE ks.stok_tersedia < 0
""")

negative_kloters = cursor.fetchall()

for row in negative_kloters:
    kloter_id, nama, tanggal_mulai, panen, terjual, stok = row
    panen_needed = terjual - panen
    
    print(f"\nFixing Kloter {kloter_id}: {nama}")
    print(f"  Current: Panen={panen}, Terjual={terjual}, Stok={stok}")
    print(f"  Creating panen: {panen_needed} ekor")
    
    # Create panen record
    cursor.execute("""
        INSERT INTO panens (kloter_id, jumlah_panen, tanggal_panen, created_at, updated_at)
        VALUES (?, ?, ?, datetime('now'), datetime('now'))
    """, (kloter_id, panen_needed, tanggal_mulai))
    
    # Update summary
    new_panen = panen + panen_needed
    new_stok = new_panen - terjual
    
    cursor.execute("""
        UPDATE kloter_summaries
        SET total_panen = ?,
            stok_tersedia = ?,
            last_calculated_at = datetime('now')
        WHERE kloter_id = ?
    """, (new_panen, new_stok, kloter_id))
    
    print(f"  [FIXED] New: Panen={new_panen}, Stok={new_stok}")

conn.commit()

print("\n" + "=" * 60)
print("VERIFICATION")
print("=" * 60)

# Verify all stocks
cursor.execute("""
    SELECT k.id, k.nama_kloter, ks.total_panen, ks.total_terjual, ks.stok_tersedia
    FROM kloters k
    LEFT JOIN kloter_summaries ks ON k.id = ks.kloter_id
    ORDER BY k.id
""")

all_good = True
for row in cursor.fetchall():
    kloter_id, nama, panen, terjual, stok = row
    status = "[OK]" if stok >= 0 else "[ERROR]"
    print(f"{status} Kloter {kloter_id}: Panen={panen}, Terjual={terjual}, Stok={stok}")
    if stok < 0:
        all_good = False

if all_good:
    print("\n[SUCCESS] All stocks are now positive!")
else:
    print("\n[ERROR] Some stocks are still negative!")

conn.close()
