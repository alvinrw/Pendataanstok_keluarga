#!/usr/bin/env python3
"""
Script untuk membaca dan menganalisis struktur database dari file SQL
Bandingkan dengan migration files Laravel untuk memastikan sinkronisasi
"""

import re
import sys
from pathlib import Path

def parse_sql_file(sql_file_path):
    """Parse file SQL dan ekstrak struktur tabel"""
    
    with open(sql_file_path, 'r', encoding='utf-8') as f:
        sql_content = f.read()
    
    # Regex untuk menangkap CREATE TABLE statements
    table_pattern = r'CREATE TABLE\s+(?:IF NOT EXISTS\s+)?`?(\w+)`?\s*\((.*?)\)(?:\s*ENGINE.*?)?;'
    tables = {}
    
    matches = re.finditer(table_pattern, sql_content, re.DOTALL | re.IGNORECASE)
    
    for match in matches:
        table_name = match.group(1)
        table_definition = match.group(2)
        
        # Parse kolom-kolom
        columns = []
        constraints = []
        
        lines = [line.strip() for line in table_definition.split(',')]
        
        for line in lines:
            line = line.strip()
            if not line:
                continue
                
            # Skip constraints (PRIMARY KEY, FOREIGN KEY, etc)
            if any(keyword in line.upper() for keyword in ['PRIMARY KEY', 'FOREIGN KEY', 'UNIQUE KEY', 'KEY ', 'CONSTRAINT']):
                constraints.append(line)
                continue
            
            # Parse kolom
            # Format: `column_name` type [attributes]
            col_match = re.match(r'`?(\w+)`?\s+(\w+(?:\([^)]+\))?)(.*)', line)
            if col_match:
                col_name = col_match.group(1)
                col_type = col_match.group(2)
                col_attrs = col_match.group(3).strip()
                
                columns.append({
                    'name': col_name,
                    'type': col_type,
                    'attributes': col_attrs
                })
        
        tables[table_name] = {
            'columns': columns,
            'constraints': constraints
        }
    
    return tables

def print_table_structure(tables):
    """Print struktur tabel dengan format yang rapi"""
    
    print("\n" + "="*80)
    print("STRUKTUR DATABASE DARI FILE SQL")
    print("="*80 + "\n")
    
    for table_name, table_info in sorted(tables.items()):
        print(f"\n📋 Tabel: {table_name}")
        print("-" * 80)
        
        print("\nKolom-kolom:")
        for col in table_info['columns']:
            print(f"  • {col['name']:<30} {col['type']:<20} {col['attributes']}")
        
        if table_info['constraints']:
            print("\nConstraints:")
            for constraint in table_info['constraints']:
                print(f"  • {constraint}")
        
        print()

def compare_with_migrations(tables, migration_dir):
    """Bandingkan dengan migration files Laravel"""
    
    print("\n" + "="*80)
    print("PERBANDINGAN DENGAN MIGRATION FILES")
    print("="*80 + "\n")
    
    migration_path = Path(migration_dir)
    
    if not migration_path.exists():
        print(f"⚠️  Migration directory tidak ditemukan: {migration_dir}")
        return
    
    migration_files = sorted(migration_path.glob("*.php"))
    
    print(f"Ditemukan {len(migration_files)} migration files\n")
    
    # Tabel yang ada di SQL
    sql_tables = set(tables.keys())
    
    # Tabel yang diharapkan dari migration (simplified check)
    expected_tables = set()
    
    for mig_file in migration_files:
        content = mig_file.read_text(encoding='utf-8')
        
        # Cari Schema::create statements
        create_matches = re.findall(r"Schema::create\(['\"](\w+)['\"]", content)
        expected_tables.update(create_matches)
    
    print("📊 Ringkasan:")
    print(f"  • Tabel di SQL file: {len(sql_tables)}")
    print(f"  • Tabel di migrations: {len(expected_tables)}")
    
    # Tabel yang ada di SQL tapi tidak di migration
    extra_tables = sql_tables - expected_tables
    if extra_tables:
        print(f"\n⚠️  Tabel di SQL tapi TIDAK di migration:")
        for table in sorted(extra_tables):
            print(f"    - {table}")
    
    # Tabel yang ada di migration tapi tidak di SQL
    missing_tables = expected_tables - sql_tables
    if missing_tables:
        print(f"\n⚠️  Tabel di migration tapi TIDAK di SQL:")
        for table in sorted(missing_tables):
            print(f"    - {table}")
    
    # Tabel yang sama
    common_tables = sql_tables & expected_tables
    if common_tables:
        print(f"\n✅ Tabel yang sama ({len(common_tables)}):")
        for table in sorted(common_tables):
            print(f"    - {table}")

def main():
    print("\n" + "="*80)
    print("DATABASE STRUCTURE ANALYZER")
    print("="*80)
    
    # Path ke file SQL (akan diupload user)
    sql_file = input("\nMasukkan path ke file SQL: ").strip().strip('"')
    
    if not Path(sql_file).exists():
        print(f"\n❌ File tidak ditemukan: {sql_file}")
        sys.exit(1)
    
    print(f"\n📂 Membaca file: {sql_file}")
    
    # Parse SQL file
    tables = parse_sql_file(sql_file)
    
    print(f"\n✅ Berhasil parse {len(tables)} tabel")
    
    # Print struktur
    print_table_structure(tables)
    
    # Bandingkan dengan migrations
    migration_dir = r"c:\Users\alvin\Documents\vscode_apin\Web_branchku\apin\database\migrations"
    compare_with_migrations(tables, migration_dir)
    
    print("\n" + "="*80)
    print("SELESAI")
    print("="*80 + "\n")

if __name__ == "__main__":
    main()
