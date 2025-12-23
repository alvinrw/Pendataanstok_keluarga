# Python Utility Scripts

This directory contains utility scripts for database management, data import, and cleanup operations.

## Configuration

Before running any scripts, ensure `config.py` is properly configured:

```bash
cp config.example.py config.py
```

The config file uses relative paths by default, so it should work without modification if you're running scripts from within the project structure.

## Database Management Scripts

Located in `database/` directory.

### backup_kloter_data.py

Creates a comprehensive JSON backup of all batch data.

**Usage:**
```bash
cd scripts/database
python backup_kloter_data.py
```

**Output:** Creates timestamped backup file in `backups/` directory containing all kloter data, sales, expenses, harvests, and deaths.

### restore_kloter_data.py

Restores data from a JSON backup file.

**Usage:**
```bash
cd scripts/database
python restore_kloter_data.py
```

**Warning:** This will delete all existing kloter data before restoring.

### check_database_structure.py

Verifies database schema and displays table structures.

**Usage:**
```bash
cd scripts/database
python check_database_structure.py
```

### fix_database.py

Automatically creates missing DOC expense entries and recalculates all summaries.

**Usage:**
```bash
cd scripts/database
python fix_database.py
```

**What it does:**
- Creates DOC expense for batches that don't have one
- Recalculates total expenses, revenue, stock, etc.
- Updates kloter and summary tables

### fix_kloter.py

Fixes batch-related data inconsistencies.

**Usage:**
```bash
cd scripts/database
python fix_kloter.py
```

### fix_negative_stock.py

Corrects negative stock issues by recalculating from source data.

**Usage:**
```bash
cd scripts/database
python fix_negative_stock.py
```

### fix_summaries.py

Recalculates all batch summaries from transaction data.

**Usage:**
```bash
cd scripts/database
python fix_summaries.py
```

## Data Import Scripts

Located in `import/` directory.

### import_from_sql.py

Imports data from MySQL SQL dump files.

**Usage:**
```bash
cd scripts/import
python import_from_sql.py
```

**Note:** Edit the script to specify the SQL file path.

### import_all_csv.py

Imports data from multiple CSV files.

**Usage:**
```bash
cd scripts/import
python import_all_csv.py
```

### import_kloter2_final.py

Imports sales data for a specific batch (Kloter 2).

**Usage:**
```bash
cd scripts/import
python import_kloter2_final.py
```

### Other Import Scripts

- `import_kloters.py`: Import batch master data
- `import_penjualan_kloter1.py`: Import Kloter 1 sales
- `import_penjualan_kloter2.py`: Import Kloter 2 sales
- `import_penjualan_kloter2_from_csv.py`: Import Kloter 2 sales from CSV
- `insert_kloter7.py`: Insert specific Kloter 7 data

## Data Cleanup Scripts

Located in `cleanup/` directory.

### clear_chicken_data.py

Removes all chicken-related data while preserving store, transaction, and admin data.

**Usage:**
```bash
cd scripts/cleanup
python clear_chicken_data.py
```

**Warning:** This permanently deletes all kloter, sales, expenses, harvests, and death records.

**What it preserves:**
- Store sales data
- Transaction records
- Schedule data
- Admin users

### clear_panen.py

Clears all harvest data.

**Usage:**
```bash
cd scripts/cleanup
python clear_panen.py
```

## Common Issues

### Import Error: No module named 'scripts.config'

Make sure you're running the script from the correct directory and that `config.py` exists:

```bash
cd /path/to/apin
python scripts/database/backup_kloter_data.py
```

### Database Locked Error

Close any applications that might have the database open (e.g., DB Browser for SQLite, the Laravel application).

### Permission Denied

Ensure the database file has proper permissions:

```bash
chmod 644 database/database.sqlite
```

## Best Practices

1. **Always backup before running cleanup or fix scripts:**
   ```bash
   python scripts/database/backup_kloter_data.py
   ```

2. **Test scripts on a copy of the database first**

3. **Review script output carefully** - scripts provide detailed logs of what they're doing

4. **Keep backups** - backup files are small and compress well

## Development

To add a new script:

1. Place it in the appropriate category folder
2. Import and use `config.py` for paths
3. Add clear output messages
4. Document it in this README
5. Test thoroughly before committing

## Notes

- All scripts use SQLite transactions for data safety
- Scripts provide detailed console output
- Backup files are in JSON format for easy inspection
- Configuration uses relative paths for portability
