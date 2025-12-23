import os
import shutil
from pathlib import Path

# Define base paths
BASE_DIR = Path(r"c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin")
SCRIPTS_DIR = BASE_DIR / "scripts"

# Define script categories
SCRIPTS = {
    'database': [
        'check_database_structure.py',
        'fix_database.py',
        'fix_kloter.py',
        'fix_negative_stock.py',
        'fix_summaries.py',
    ],
    'import': [
        'import_from_sql.py',
        'import_all_chicken_data.py',
        'import_all_csv.py',
        'import_kloter2_final.py',
        'import_kloters.py',
        'import_penjualan_kloter1.py',
        'import_penjualan_kloter2.py',
        'import_penjualan_kloter2_from_csv.py',
        'insert_kloter7.py',
    ],
    'cleanup': [
        'clear_chicken_data.py',
        'clear_panen.py',
    ]
}

# Path replacement patterns
OLD_DB_PATH = r"DB_PATH = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\database.sqlite'"
NEW_DB_PATH = """import sys
from pathlib import Path
sys.path.insert(0, str(Path(__file__).resolve().parent.parent))
from scripts.config import get_db_path

DB_PATH = get_db_path()"""

OLD_CSV_PATH = r"CSV_FILE = r'c:\Users\alvin\Documents\vscode_apin\Web_branchku\cpanel-upload\csv_data\data_penjualans.csv'"
NEW_CSV_PATH = """import sys
from pathlib import Path
sys.path.insert(0, str(Path(__file__).resolve().parent.parent))
from scripts.config import get_csv_dir

CSV_FILE = str(Path(get_csv_dir()) / 'data_penjualans.csv')"""

def refactor_script(content):
    """Refactor script content"""
    # Replace hardcoded DB path
    if OLD_DB_PATH in content:
        content = content.replace(OLD_DB_PATH, NEW_DB_PATH)
    elif "DB_PATH = r'c:" in content:
        # Handle variations
        import re
        content = re.sub(
            r"DB_PATH = r'c:\\Users\\alvin\\Documents\\vscode_apin\\Web_branchku\\apin\\database\\database\.sqlite'",
            NEW_DB_PATH,
            content
        )
    
    # Replace hardcoded CSV path
    if OLD_CSV_PATH in content:
        content = content.replace(OLD_CSV_PATH, NEW_CSV_PATH)
    elif "CSV_FILE = r'c:" in content:
        import re
        content = re.sub(
            r"CSV_FILE = r'c:\\Users\\alvin\\Documents\\vscode_apin\\Web_branchku\\cpanel-upload.*?\.csv'",
            NEW_CSV_PATH,
            content
        )
    
    # Remove AI-like language
    replacements = {
        'Semua data ayam sudah dihapus.': 'All chicken data has been cleared.',
        'Data toko, transaksi, jadwal, dan admin tetap aman.': 'Store, transaction, schedule, and admin data remains safe.',
        'Refresh browser untuk melihat database kosong!': 'Refresh browser to see empty database!',
        'Refresh browser to see data!': 'Refresh browser to see updated data!',
        'Refresh browser to see updated data!': 'Refresh browser to see changes!',
    }
    
    for old, new in replacements.items():
        content = content.replace(old, new)
    
    return content

def migrate_scripts():
    """Migrate and refactor all scripts"""
    print("=" * 60)
    print("MIGRATING PYTHON SCRIPTS")
    print("=" * 60)
    
    for category, scripts in SCRIPTS.items():
        category_dir = SCRIPTS_DIR / category
        print(f"\n[{category.upper()}]")
        
        for script_name in scripts:
            source = BASE_DIR / script_name
            dest = category_dir / script_name
            
            if not source.exists():
                print(f"  [SKIP] {script_name} - not found")
                continue
            
            if dest.exists():
                print(f"  [EXISTS] {script_name}")
                continue
            
            # Read and refactor
            with open(source, 'r', encoding='utf-8') as f:
                content = f.read()
            
            refactored = refactor_script(content)
            
            # Write to new location
            with open(dest, 'w', encoding='utf-8') as f:
                f.write(refactored)
            
            print(f"  [MIGRATED] {script_name}")
    
    print("\n" + "=" * 60)
    print("MIGRATION COMPLETED!")
    print("=" * 60)

if __name__ == "__main__":
    migrate_scripts()
