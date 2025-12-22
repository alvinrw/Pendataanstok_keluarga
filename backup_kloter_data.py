import sqlite3
import json
from datetime import datetime
import os

DB_PATH = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\database.sqlite'
BACKUP_DIR = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\backups'

# Create backup directory if not exists
os.makedirs(BACKUP_DIR, exist_ok=True)

conn = sqlite3.connect(DB_PATH)
conn.row_factory = sqlite3.Row  # Enable column access by name
cursor = conn.cursor()

timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
backup_file = os.path.join(BACKUP_DIR, f'backup_kloter_{timestamp}.json')

print("=" * 60)
print("CREATING COMPREHENSIVE BACKUP")
print("=" * 60)

backup_data = {
    'backup_info': {
        'timestamp': timestamp,
        'date': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
        'database': 'apin_db',
        'version': '2.0 (New Architecture)',
    },
    'kloters': []
}

# Get all kloters
cursor.execute("SELECT * FROM kloters ORDER BY id")
kloters = cursor.fetchall()

for kloter in kloters:
    kloter_id = kloter['id']
    
    # Get all related data
    cursor.execute("SELECT * FROM pengeluarans WHERE kloter_id = ? ORDER BY tanggal_pengeluaran", (kloter_id,))
    pengeluarans = [dict(row) for row in cursor.fetchall()]
    
    cursor.execute("SELECT * FROM kematian_ayams WHERE kloter_id = ? ORDER BY tanggal_kematian", (kloter_id,))
    kematians = [dict(row) for row in cursor.fetchall()]
    
    cursor.execute("SELECT * FROM panens WHERE kloter_id = ? ORDER BY tanggal_panen", (kloter_id,))
    panens = [dict(row) for row in cursor.fetchall()]
    
    cursor.execute("SELECT * FROM data_penjualans WHERE kloter_id = ? ORDER BY tanggal", (kloter_id,))
    penjualans = [dict(row) for row in cursor.fetchall()]
    
    cursor.execute("SELECT * FROM kloter_summaries WHERE kloter_id = ?", (kloter_id,))
    summary_row = cursor.fetchone()
    summary = dict(summary_row) if summary_row else {}
    
    # Build kloter data
    kloter_data = {
        'info': dict(kloter),
        'summary': summary,
        'pengeluarans': pengeluarans,
        'kematians': kematians,
        'panens': panens,
        'penjualans': penjualans,
        'statistics': {
            'total_pengeluaran_records': len(pengeluarans),
            'total_kematian_records': len(kematians),
            'total_panen_records': len(panens),
            'total_penjualan_records': len(penjualans),
        }
    }
    
    backup_data['kloters'].append(kloter_data)
    
    print(f"\n[BACKED UP] Kloter {kloter_id}: {kloter['nama_kloter']}")
    print(f"  Pengeluaran: {len(pengeluarans)} records")
    print(f"  Kematian: {len(kematians)} records")
    print(f"  Panen: {len(panens)} records")
    print(f"  Penjualan: {len(penjualans)} records")

# Save to JSON
with open(backup_file, 'w', encoding='utf-8') as f:
    json.dump(backup_data, f, indent=2, ensure_ascii=False)

conn.close()

print("\n" + "=" * 60)
print("BACKUP COMPLETED!")
print("=" * 60)
print(f"\nFile: {backup_file}")
print(f"Total Kloters: {len(backup_data['kloters'])}")
print(f"Size: {os.path.getsize(backup_file) / 1024:.2f} KB")
print("\nBackup ini berisi:")
print("  - Info kloter (DOC, tanggal mulai, dll)")
print("  - Summary (stok, terjual, pemasukan, dll)")
print("  - Semua pengeluaran per tanggal")
print("  - Semua kematian per tanggal")
print("  - Semua panen per tanggal")
print("  - Semua penjualan per pembeli")
print("\nBackup ini bisa di-restore kapan saja!")
