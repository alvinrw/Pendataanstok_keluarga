import sqlite3
import json
import os
from datetime import datetime

DB_PATH = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\database.sqlite'
BACKUP_DIR = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\backups'

def restore_from_backup(backup_file):
    """Restore kloter data from JSON backup file"""
    
    if not os.path.exists(backup_file):
        print(f"[ERROR] Backup file not found: {backup_file}")
        return
    
    print("=" * 60)
    print("RESTORING FROM BACKUP")
    print("=" * 60)
    
    # Load backup data
    with open(backup_file, 'r', encoding='utf-8') as f:
        backup_data = json.load(f)
    
    print(f"\nBackup Info:")
    print(f"  Date: {backup_data['backup_info']['date']}")
    print(f"  Version: {backup_data['backup_info']['version']}")
    print(f"  Total Kloters: {len(backup_data['kloters'])}")
    
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    
    # Clear existing data
    print("\n[CLEARING] Existing kloter data...")
    cursor.execute("DELETE FROM kloter_summaries")
    cursor.execute("DELETE FROM data_penjualans")
    cursor.execute("DELETE FROM panens")
    cursor.execute("DELETE FROM kematian_ayams")
    cursor.execute("DELETE FROM pengeluarans")
    cursor.execute("DELETE FROM kloters")
    
    # Restore each kloter
    for kloter_data in backup_data['kloters']:
        kloter_info = kloter_data['info']
        kloter_id = kloter_info['id']
        
        print(f"\n[RESTORING] Kloter {kloter_id}: {kloter_info['nama_kloter']}")
        
        # Insert kloter
        columns = ', '.join(kloter_info.keys())
        placeholders = ', '.join(['?' for _ in kloter_info])
        cursor.execute(f"INSERT INTO kloters ({columns}) VALUES ({placeholders})", 
                      list(kloter_info.values()))
        
        # Insert pengeluarans
        for item in kloter_data['pengeluarans']:
            columns = ', '.join(item.keys())
            placeholders = ', '.join(['?' for _ in item])
            cursor.execute(f"INSERT INTO pengeluarans ({columns}) VALUES ({placeholders})", 
                          list(item.values()))
        print(f"  Pengeluaran: {len(kloter_data['pengeluarans'])} records")
        
        # Insert kematians
        for item in kloter_data['kematians']:
            columns = ', '.join(item.keys())
            placeholders = ', '.join(['?' for _ in item])
            cursor.execute(f"INSERT INTO kematian_ayams ({columns}) VALUES ({placeholders})", 
                          list(item.values()))
        print(f"  Kematian: {len(kloter_data['kematians'])} records")
        
        # Insert panens
        for item in kloter_data['panens']:
            columns = ', '.join(item.keys())
            placeholders = ', '.join(['?' for _ in item])
            cursor.execute(f"INSERT INTO panens ({columns}) VALUES ({placeholders})", 
                          list(item.values()))
        print(f"  Panen: {len(kloter_data['panens'])} records")
        
        # Insert penjualans
        for item in kloter_data['penjualans']:
            columns = ', '.join(item.keys())
            placeholders = ', '.join(['?' for _ in item])
            cursor.execute(f"INSERT INTO data_penjualans ({columns}) VALUES ({placeholders})", 
                          list(item.values()))
        print(f"  Penjualan: {len(kloter_data['penjualans'])} records")
        
        # Insert summary
        if kloter_data['summary']:
            summary = kloter_data['summary']
            columns = ', '.join(summary.keys())
            placeholders = ', '.join(['?' for _ in summary])
            cursor.execute(f"INSERT INTO kloter_summaries ({columns}) VALUES ({placeholders})", 
                          list(summary.values()))
    
    conn.commit()
    conn.close()
    
    print("\n" + "=" * 60)
    print("RESTORE COMPLETED!")
    print("=" * 60)
    print("\nRefresh browser to see restored data!")

if __name__ == "__main__":
    # List available backups
    print("Available backups:")
    backups = [f for f in os.listdir(BACKUP_DIR) if f.startswith('backup_kloter_') and f.endswith('.json')]
    
    if not backups:
        print("  No backups found!")
    else:
        for i, backup in enumerate(sorted(backups, reverse=True), 1):
            print(f"  {i}. {backup}")
        
        print("\nTo restore, run:")
        print(f"  python restore_kloter_data.py")
        print("\nOr edit this file to specify backup file.")
