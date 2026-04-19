# Database and PDO Layer

## PDO Rules

- `PDO::ATTR_EMULATE_PREPARES = false`
- Exceptions enabled
- All repository queries use named prepared parameters through `Database` abstraction helpers.

## `Database` helper methods

- `fetchOne()`
- `fetchAll()`
- `fetchColumn()`
- `execute()`
- `insert()`
- `transaction()`

These remove repetitive boilerplate while preserving explicit SQL control.

## Baseline Tables

- `characters`
- `temporary_attachments`
- `registered_objects`
- `object_commands`
- `environment_zones`

See `/sql/schema.sql` for full definitions and indexes.
