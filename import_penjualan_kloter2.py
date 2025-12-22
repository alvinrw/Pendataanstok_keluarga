import sqlite3
from datetime import datetime

DB_PATH = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\database.sqlite'

conn = sqlite3.connect(DB_PATH)
cursor = conn.cursor()

print("=" * 60)
print("IMPORTING PENJUALAN DATA FOR KLOTER 2")
print("=" * 60)

# Get Kloter 2 ID from database
cursor.execute("SELECT id, nama_kloter FROM kloters WHERE nama_kloter LIKE '%Kloter 2%' ORDER BY id LIMIT 1")
kloter_row = cursor.fetchone()

if not kloter_row:
    print("[ERROR] Kloter 2 not found in database!")
    conn.close()
    exit()

kloter_id = kloter_row[0]
kloter_name = kloter_row[1]

print(f"Found Kloter: ID={kloter_id}, Name={kloter_name}")

# Data penjualan dari screenshot
penjualan_data = [
    {
        'tanggal': '2025-12-20',
        'nama_pembeli': 'Rahadi PABM',
        'jumlah_ayam_dibeli': 3,
        'berat_total': 2720,
        'harga_asli': 204000,
        'diskon': 0,
        'harga_total': 204000,
    },
    {
        'tanggal': '2025-12-14',
        'nama_pembeli': 'Yandi GM',
        'jumlah_ayam_dibeli': 2,
        'berat_total': 1413,
        'harga_asli': 106000,
        'diskon': 0,
        'harga_total': 106000,
    },
    {
        'tanggal': '2025-12-14',
        'nama_pembeli': 'Manto tenis GSE',
        'jumlah_ayam_dibeli': 2,
        'berat_total': 1400,
        'harga_asli': 105000,
        'diskon': 0,
        'harga_total': 105000,
    },
    {
        'tanggal': '2025-12-11',
        'nama_pembeli': 'Hargo PABM',
        'jumlah_ayam_dibeli': 2,
        'berat_total': 1747,
        'harga_asli': 131000,
        'diskon': 0,
        'harga_total': 131000,
    },
    {
        'tanggal': '2025-12-11',
        'nama_pembeli': 'Yandi GM',
        'jumlah_ayam_dibeli': 3,
        'berat_total': 1987,
        'harga_asli': 149000,
        'diskon': 0,
        'harga_total': 149000,
    },
]

count = 0
now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

for data in penjualan_data:
    try:
        cursor.execute("""
            INSERT INTO data_penjualans (kloter_id, tanggal, nama_pembeli, 
                                        jumlah_ayam_dibeli, berat_total, 
                                        harga_asli, diskon, harga_total,
                                        created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        """, (
            kloter_id,
            data['tanggal'],
            data['nama_pembeli'],
            data['jumlah_ayam_dibeli'],
            data['berat_total'],
            data['harga_asli'],
            data['diskon'],
            data['harga_total'],
            now,
            now,
        ))
        
        count += 1
        print(f"[OK] {data['nama_pembeli']}: {data['jumlah_ayam_dibeli']} ekor, Rp {data['harga_total']:,}")
        
    except Exception as e:
        print(f"[ERROR] {data['nama_pembeli']}: {e}")

conn.commit()

print("\n" + "=" * 60)
print(f"IMPORT COMPLETED! Imported: {count} penjualan")
print("=" * 60)

# Recalculate summary
print("\n[RECALCULATING] Summary for Kloter 2...")

cursor.execute("SELECT COALESCE(SUM(jumlah_panen), 0) FROM panens WHERE kloter_id = ?", (kloter_id,))
total_panen = cursor.fetchone()[0]

cursor.execute("SELECT COALESCE(SUM(jumlah_ayam_dibeli), 0) FROM data_penjualans WHERE kloter_id = ?", (kloter_id,))
total_terjual = cursor.fetchone()[0]

cursor.execute("SELECT COALESCE(SUM(berat_total), 0) FROM data_penjualans WHERE kloter_id = ?", (kloter_id,))
total_berat = cursor.fetchone()[0] / 1000

cursor.execute("SELECT COALESCE(SUM(harga_total), 0) FROM data_penjualans WHERE kloter_id = ?", (kloter_id,))
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
""", (total_terjual, total_berat, total_pemasukan, stok_tersedia, kloter_id))

conn.commit()
conn.close()

print(f"\n[UPDATED] {kloter_name}:")
print(f"  Panen: {total_panen} ekor")
print(f"  Terjual: {total_terjual} ekor")
print(f"  Stok Siap Jual: {stok_tersedia} ekor")
print(f"  Total Pemasukan: Rp {total_pemasukan:,.0f}")

print("\n" + "=" * 60)
print("DONE! Refresh browser to see data!")
print("=" * 60)
