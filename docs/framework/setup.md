# Setup

## Requirements

- PHP 8.3+
- MariaDB 10.6+
- PDO extension

## Initialize

1. Create DB and run:
   - `/sql/schema.sql`
2. Configure environment variables:
   - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`
   - `STARFALL_APP_SECRET`
3. Serve `/public` as web root.

## Legacy Import

Place legacy JSON records under:

`/storage/legacy/{avatar_uuid}.json`

and adjust alias mapping in:

`/config/legacy_import.php`
