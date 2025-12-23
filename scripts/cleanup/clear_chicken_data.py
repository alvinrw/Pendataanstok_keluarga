import sqlite3

import sys
from pathlib import Path
sys.path.insert(0, str(Path(__file__).resolve().parent.parent))
from scripts.config import get_db_path

DB_PATH = get_db_path()

conn = sqlite3.connect(DB_PATH)
cursor = conn.cursor()

print("=" * 60)
print("CLEARING ALL CHICKEN-RELATED DATA")
print("=" * 60)

# Disable foreign keys temporarily
cursor.execute("PRAGMA foreign_keys = OFF")

# Delete in correct order (child tables first)
tables_to_clear = [
    ('kloter_summaries', 'Kloter Summaries'),
    ('data_penjualans', 'Data Penjualan Ayam'),
    ('panens', 'Data Panen'),
    ('kematian_ayams', 'Data Kematian'),
    ('pengeluarans', 'Data Pengeluaran'),
    ('kloters', 'Data Kloter'),
]

for table, name in tables_to_clear:
    cursor.execute(f"SELECT COUNT(*) FROM {table}")
    count = cursor.fetchone()[0]
    
    cursor.execute(f"DELETE FROM {table}")
    print(f"[DELETED] {name}: {count} records")

# Re-enable foreign keys
cursor.execute("PRAGMA foreign_keys = ON")

conn.commit()

print("\n" + "=" * 60)
print("VERIFICATION")
print("=" * 60)

for table, name in tables_to_clear:
    cursor.execute(f"SELECT COUNT(*) FROM {table}")
    count = cursor.fetchone()[0]
    status = "[OK]" if count == 0 else "[ERROR]"
    print(f"{status} {name}: {count} records remaining")

print("\n" + "=" * 60)
print("DATA LAIN (TIDAK DIHAPUS)")
print("=" * 60)

other_tables = [
    ('penjualan_tokos', 'Penjualan Toko'),
    ('transaksis', 'Transaksi'),
    ('jadwals', 'Jadwal'),
    ('admins', 'Admin Users'),
]

for table, name in other_tables:
    cursor.execute(f"SELECT COUNT(*) FROM {table}")
    count = cursor.fetchone()[0]
    print(f"[KEPT] {name}: {count} records")

conn.close()

print("\n" + "=" * 60)
print("CLEAR COMPLETED!")
print("=" * 60)
print("\nAll chicken data has been cleared.")
print("Store, transaction, schedule, and admin data remains safe.")
print("\nRefresh browser to see empty database!")
