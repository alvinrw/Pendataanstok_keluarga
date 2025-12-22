import sqlite3
import csv

DB_PATH = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\database.sqlite'
CSV_FILE = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\cpanel-upload\csv_data\kloters.csv'

conn = sqlite3.connect(DB_PATH)
cursor = conn.cursor()

print("=" * 60)
print("IMPORTING KLOTERS FROM CSV")
print("=" * 60)

# Read CSV
with open(CSV_FILE, 'r', encoding='utf-8') as f:
    reader = csv.DictReader(f)
    count = 0
    skipped = 0
    
    for row in reader:
        try:
            # Only import essential fields (no calculated fields)
            cursor.execute("""
                INSERT INTO kloters (id, nama_kloter, status, tanggal_mulai, jumlah_doc, 
                                    harga_beli_doc, sisa_ayam_hidup, total_pengeluaran,
                                    created_at, updated_at, tanggal_panen, harga_jual_total)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            """, (
                row['id'],
                row['nama_kloter'],
                row['status'],
                row['tanggal_mulai'] if row['tanggal_mulai'] else None,
                row['jumlah_doc'],
                row['harga_beli_doc'],
                row['sisa_ayam_hidup'] if row['sisa_ayam_hidup'] else row['jumlah_doc'],
                row['total_pengeluaran'] if row['total_pengeluaran'] else 0,
                row['created_at'],
                row['updated_at'],
                row['tanggal_panen'] if row['tanggal_panen'] else None,
                row['harga_jual_total'] if row['harga_jual_total'] else None,
            ))
            
            # Create empty summary for this kloter
            cursor.execute("""
                INSERT INTO kloter_summaries (kloter_id, total_terjual, total_berat_kg, 
                                             total_pemasukan, total_pengeluaran, total_mati,
                                             total_panen, stok_tersedia, last_calculated_at)
                VALUES (?, 0, 0, 0, 0, 0, 0, 0, datetime('now'))
            """, (row['id'],))
            
            count += 1
            print(f"[OK] Imported: {row['nama_kloter']}")
            
        except Exception as e:
            skipped += 1
            print(f"[SKIP] {row.get('nama_kloter', 'Unknown')}: {e}")

conn.commit()

print("\n" + "=" * 60)
print(f"IMPORT COMPLETED!")
print(f"Imported: {count} kloters")
print(f"Skipped: {skipped} kloters")
print("=" * 60)

# Verify
cursor.execute("SELECT id, nama_kloter, jumlah_doc FROM kloters ORDER BY id")
print("\nKLOTERS IN DATABASE:")
for row in cursor.fetchall():
    print(f"  ID {row[0]}: {row[1]} ({row[2]} DOC)")

conn.close()

print("\nRefresh browser to see imported kloters!")
