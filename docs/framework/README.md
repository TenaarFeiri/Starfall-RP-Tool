# Starfall RP Tool Framework (Initial Baseline)

This directory documents the initial extensible framework baseline for:

- PHP (latest-style OOP + SRP-focused architecture)
- MariaDB with strict PDO prepared-statement operations
- WebHUD bootstrap/login flow
- LSL/Luau integration contracts
- Object registration + command queue groundwork
- Temporary attachment state + stale cleanup groundwork
- Coordinate/elevation-based environment zone querying

## Directory Summary

- `/src/Core` — autoloading, configuration, basic HTTP request/response/router
- `/src/Infrastructure/Database` — PDO connection factory + reusable DB abstraction
- `/src/Domain/*` — SRP domain components for characters, attachments, objects, environment
- `/src/Application` — controller and composition root wiring
- `/public/index.php` — front controller
- `/config` — app/database/legacy import mapping config
- `/sql/schema.sql` — base MariaDB schema
- `/scripts/lsl` and `/scripts/luau` — in-world bridge templates
- `/webhud` — responsive WebHUD UI prototype

## Key Baseline Behaviors

1. WebHUD/object sends `POST /api/hud/login`
2. Server syncs attachment status and cleans stale pending attachments
3. Server loads last character for avatar
4. If no character exists, attempts legacy import from configured field map
5. If no legacy data is found, creates a default character
6. Server returns a session token + character payload for HUD rendering

## Legacy Import Strategy

Legacy records are read from `storage/legacy/{avatar_uuid}.json`.
`config/legacy_import.php` defines fallback alias fields so inconsistent legacy formats can still map into canonical fields.

## Next Steps (Planned Extensions)

- Persisted auth sessions and token lifecycle
- Combat/stat systems
- Titler 4-prim text split and continuity logic
- Async object command execution worker
- Rich attachment dispense orchestration
- Robust migration/versioning strategy
