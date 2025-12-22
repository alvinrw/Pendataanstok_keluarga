import sqlite3

DB_PATH = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\database.sqlite'

conn = sqlite3.connect(DB_PATH)
cursor = conn.cursor()

try:
    cursor.execute("""
        INSERT INTO kloters (id, nama_kloter, status, tanggal_mulai, jumlah_doc, 
                            sisa_ayam_hidup, harga_beli_doc, total_pengeluaran,
                            stok_awal, stok_tersedia, total_terjual, total_berat,
                            total_pemasukan, created_at, updated_at, tanggal_panen, harga_jual_total)
        VALUES (7, 'Kloter 1 perode panen 25 sep', 'Aktif', '2025-07-29', 100, 
                0, 600000, 2112000, 0, 0, 81, 58.40, 4377000.00, 
                '2025-09-21 21:46:06', '2025-11-30 14:51:31', NULL, NULL)
    """)
    conn.commit()
    print("[OK] Kloter 7 inserted successfully!")
except sqlite3.IntegrityError:
    print("[INFO] Kloter 7 already exists")
except Exception as e:
    print(f"[ERROR] {e}")
finally:
    conn.close()
