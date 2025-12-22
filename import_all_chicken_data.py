import sqlite3
import csv
import os

DB_PATH = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\database.sqlite'
CSV_DIR = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\cpanel-upload\csv_data'

conn = sqlite3.connect(DB_PATH)
cursor = conn.cursor()

print("=" * 60)
print("IMPORTING CHICKEN DATA FROM CSV")
print("=" * 60)

# Import order matters (dependencies)
imports = [
    ('pengeluarans.csv', 'pengeluarans', ['id', 'kloter_id', 'kategori', 'jumlah_pengeluaran', 'jumlah_pakan_kg', 'tanggal_pengeluaran', 'catatan', 'created_at', 'updated_at']),
    ('kematian_ayams.csv', 'kematian_ayams', ['id', 'kloter_id', 'jumlah_mati', 'tanggal_kematian', 'catatan', 'created_at', 'updated_at']),
    ('panens.csv', 'panens', ['id', 'kloter_id', 'jumlah_panen', 'tanggal_panen', 'created_at', 'updated_at']),
    ('data_penjualans.csv', 'data_penjualans', ['id', 'kloter_id', 'tanggal', 'nama_pembeli', 'jumlah_ayam_dibeli', 'berat_total', 'harga_asli', 'diskon', 'harga_total', 'created_at', 'updated_at']),
]

total_imported = 0

for csv_file, table_name, columns in imports:
    csv_path = os.path.join(CSV_DIR, csv_file)
    
    if not os.path.exists(csv_path):
        print(f"\n[SKIP] {csv_file} not found")
        continue
    
    print(f"\n[IMPORTING] {csv_file}...")
    
    with open(csv_path, 'r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        count = 0
        skipped = 0
        
        for row in reader:
            try:
                # Build values list
                values = []
                for col in columns:
                    val = row.get(col, '')
                    if val == '' or val == 'NULL':
                        values.append(None)
                    else:
                        values.append(val)
                
                # Build INSERT query
                placeholders = ','.join(['?' for _ in columns])
                cols_str = ','.join(columns)
                query = f"INSERT INTO {table_name} ({cols_str}) VALUES ({placeholders})"
                
                cursor.execute(query, values)
                count += 1
                
            except Exception as e:
                skipped += 1
                # print(f"  [SKIP] Row: {e}")
        
        print(f"  [OK] Imported {count} records, Skipped {skipped}")
        total_imported += count

conn.commit()

print("\n" + "=" * 60)
print(f"IMPORT COMPLETED! Total: {total_imported} records")
print("=" * 60)

# Trigger Observer to recalculate summaries
print("\n[RECALCULATING] Kloter summaries...")

cursor.execute("SELECT id FROM kloters")
kloter_ids = [row[0] for row in cursor.fetchall()]

for kloter_id in kloter_ids:
    # Calculate totals
    cursor.execute("SELECT COALESCE(SUM(jumlah_ayam_dibeli), 0) FROM data_penjualans WHERE kloter_id = ?", (kloter_id,))
    total_terjual = cursor.fetchone()[0]
    
    cursor.execute("SELECT COALESCE(SUM(berat_total), 0) FROM data_penjualans WHERE kloter_id = ?", (kloter_id,))
    total_berat = cursor.fetchone()[0] / 1000  # gram to kg
    
    cursor.execute("SELECT COALESCE(SUM(harga_total), 0) FROM data_penjualans WHERE kloter_id = ?", (kloter_id,))
    total_pemasukan = cursor.fetchone()[0]
    
    cursor.execute("SELECT COALESCE(SUM(jumlah_pengeluaran), 0) FROM pengeluarans WHERE kloter_id = ?", (kloter_id,))
    total_pengeluaran = cursor.fetchone()[0]
    
    cursor.execute("SELECT COALESCE(SUM(jumlah_mati), 0) FROM kematian_ayams WHERE kloter_id = ?", (kloter_id,))
    total_mati = cursor.fetchone()[0]
    
    cursor.execute("SELECT COALESCE(SUM(jumlah_panen), 0) FROM panens WHERE kloter_id = ?", (kloter_id,))
    total_panen = cursor.fetchone()[0]
    
    cursor.execute("SELECT jumlah_doc FROM kloters WHERE id = ?", (kloter_id,))
    jumlah_doc = cursor.fetchone()[0]
    
    stok_tersedia = total_panen - total_terjual
    
    # Update summary
    cursor.execute("""
        UPDATE kloter_summaries
        SET total_terjual = ?,
            total_berat_kg = ?,
            total_pemasukan = ?,
            total_pengeluaran = ?,
            total_mati = ?,
            total_panen = ?,
            stok_tersedia = ?,
            last_calculated_at = datetime('now')
        WHERE kloter_id = ?
    """, (total_terjual, total_berat, total_pemasukan, total_pengeluaran, total_mati, total_panen, stok_tersedia, kloter_id))
    
    print(f"  Kloter {kloter_id}: Panen={total_panen}, Terjual={total_terjual}, Stok={stok_tersedia}")

conn.commit()
conn.close()

print("\n" + "=" * 60)
print("ALL DATA IMPORTED & SUMMARIES UPDATED!")
print("=" * 60)
print("\nRefresh browser to see all data!")
