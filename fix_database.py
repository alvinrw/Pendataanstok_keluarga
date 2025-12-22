import sqlite3

DB_PATH = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\database.sqlite'

conn = sqlite3.connect(DB_PATH)
cursor = conn.cursor()

print("=" * 60)
print("FIXING DATABASE - AUTO CREATE DOC EXPENSES")
print("=" * 60)

# Get all kloters
cursor.execute("""
    SELECT k.id, k.nama_kloter, k.harga_beli_doc, k.tanggal_mulai
    FROM kloters k
""")

kloters = cursor.fetchall()

for kloter_id, nama, harga_doc, tanggal_mulai in kloters:
    # Check if DOC expense already exists
    cursor.execute("""
        SELECT COUNT(*) FROM pengeluarans 
        WHERE kloter_id = ? AND kategori = 'DOC'
    """, (kloter_id,))
    
    doc_exists = cursor.fetchone()[0]
    
    if doc_exists == 0:
        # Create DOC expense
        cursor.execute("""
            INSERT INTO pengeluarans (kloter_id, kategori, jumlah_pengeluaran, 
                                     tanggal_pengeluaran, catatan, jumlah_pakan_kg,
                                     created_at, updated_at)
            VALUES (?, 'DOC', ?, ?, 'Pembelian DOC awal (auto-created)', 0,
                    datetime('now'), datetime('now'))
        """, (kloter_id, harga_doc, tanggal_mulai))
        
        print(f"[CREATED] DOC expense for Kloter {kloter_id}: {nama} - Rp {harga_doc:,.0f}")
    else:
        print(f"[EXISTS] DOC expense for Kloter {kloter_id}: {nama}")

conn.commit()

print("\n" + "=" * 60)
print("RECALCULATING ALL SUMMARIES")
print("=" * 60)

# Recalculate all summaries
for kloter_id, nama, _, _ in kloters:
    # Calculate totals
    cursor.execute("SELECT COALESCE(SUM(jumlah_ayam_dibeli), 0) FROM data_penjualans WHERE kloter_id = ? AND deleted_at IS NULL", (kloter_id,))
    total_terjual = cursor.fetchone()[0]
    
    cursor.execute("SELECT COALESCE(SUM(berat_total), 0) FROM data_penjualans WHERE kloter_id = ? AND deleted_at IS NULL", (kloter_id,))
    total_berat = cursor.fetchone()[0] / 1000
    
    cursor.execute("SELECT COALESCE(SUM(harga_total), 0) FROM data_penjualans WHERE kloter_id = ? AND deleted_at IS NULL", (kloter_id,))
    total_pemasukan = cursor.fetchone()[0]
    
    cursor.execute("SELECT COALESCE(SUM(jumlah_pengeluaran), 0) FROM pengeluarans WHERE kloter_id = ? AND deleted_at IS NULL", (kloter_id,))
    total_pengeluaran = cursor.fetchone()[0]
    
    cursor.execute("SELECT COALESCE(SUM(jumlah_mati), 0) FROM kematian_ayams WHERE kloter_id = ? AND deleted_at IS NULL", (kloter_id,))
    total_mati = cursor.fetchone()[0]
    
    cursor.execute("SELECT COALESCE(SUM(jumlah_panen), 0) FROM panens WHERE kloter_id = ? AND deleted_at IS NULL", (kloter_id,))
    total_panen = cursor.fetchone()[0]
    
    cursor.execute("SELECT jumlah_doc FROM kloters WHERE id = ?", (kloter_id,))
    jumlah_doc = cursor.fetchone()[0]
    
    sisa_ayam_hidup = jumlah_doc - total_mati - total_panen
    stok_tersedia = total_panen - total_terjual
    
    # Update kloter
    cursor.execute("""
        UPDATE kloters
        SET sisa_ayam_hidup = ?,
            total_pengeluaran = ?
        WHERE id = ?
    """, (sisa_ayam_hidup, total_pengeluaran, kloter_id))
    
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
    """, (total_terjual, total_berat, total_pemasukan, total_pengeluaran, 
          total_mati, total_panen, stok_tersedia, kloter_id))
    
    print(f"\n[UPDATED] Kloter {kloter_id}: {nama}")
    print(f"  Sisa Kandang: {sisa_ayam_hidup} ekor")
    print(f"  Panen: {total_panen} ekor")
    print(f"  Terjual: {total_terjual} ekor")
    print(f"  Stok Siap Jual: {stok_tersedia} ekor")
    print(f"  Total Pengeluaran: Rp {total_pengeluaran:,.0f}")
    print(f"  Total Pemasukan: Rp {total_pemasukan:,.0f}")

conn.commit()
conn.close()

print("\n" + "=" * 60)
print("DATABASE FIXED!")
print("=" * 60)
print("\nRefresh browser to see updated data!")
