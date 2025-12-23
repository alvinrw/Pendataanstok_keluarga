import sqlite3
import json
from datetime import datetime
import os
import sys
from pathlib import Path

# Add parent directory to path for config import
sys.path.insert(0, str(Path(__file__).resolve().parent.parent))
from scripts.config import get_db_path

DB_PATH = get_db_path()
BACKUP_DIR = Path(__file__).resolve().parent.parent / 'backups'

os.makedirs(BACKUP_DIR, exist_ok=True)

conn = sqlite3.connect(DB_PATH)
conn.row_factory = sqlite3.Row
cursor = conn.cursor()

timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
backup_file = BACKUP_DIR / f'backup_kloter_{timestamp}.json'

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

cursor.execute("SELECT * FROM kloters ORDER BY id")
kloters = cursor.fetchall()

for kloter in kloters:
    kloter_id = kloter['id']
    
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

with open(backup_file, 'w', encoding='utf-8') as f:
    json.dump(backup_data, f, indent=2, ensure_ascii=False)

conn.close()

print("\n" + "=" * 60)
print("BACKUP COMPLETED!")
print("=" * 60)
print(f"\nFile: {backup_file}")
print(f"Total Kloters: {len(backup_data['kloters'])}")
print(f"Size: {os.path.getsize(backup_file) / 1024:.2f} KB")
print("\nBackup contains:")
print("  - Kloter info (DOC, start date, etc)")
print("  - Summary (stock, sold, revenue, etc)")
print("  - All expenses by date")
print("  - All deaths by date")
print("  - All harvests by date")
print("  - All sales by buyer")
print("\nThis backup can be restored anytime!")
