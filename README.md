# Chicken Farm Management System

Laravel-based application for managing chicken farming operations including batch tracking, sales, expenses, and automated reporting.

## Features

- Batch (Kloter) management with DOC tracking
- Sales and revenue tracking
- Expense monitoring
- Harvest and mortality tracking
- Automated summaries and calculations
- CSV export functionality
- Store and transaction management

## Quick Start

### Installation

1. Install dependencies:
   ```bash
   composer install
   npm install
   ```

2. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Setup database:
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

4. Start server:
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000`

## Database

Uses SQLite by default. Database file: `database/database.sqlite`

## Utility Scripts

Python scripts for database management and data import are located in `scripts/`:

- `scripts/database/` - Database management and fixes
- `scripts/import/` - Data import utilities
- `scripts/cleanup/` - Data cleanup tools

See `scripts/README.md` for details.

## Deployment

See `../cpanel-upload/README.md` for cPanel deployment instructions.

## Documentation

For complete documentation, see the main project README in the parent directory.

## Security Notes

- `.env` file contains sensitive configuration
- Database files are excluded from version control
- Never commit production data or credentials

## Development

Run tests:
```bash
php artisan test
```

Create migration:
```bash
php artisan make:migration migration_name
```

## License

Proprietary software. All rights reserved.
