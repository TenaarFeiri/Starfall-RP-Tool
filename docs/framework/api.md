# API Baseline

## `POST /api/hud/login`
Request:

```json
{
  "avatar_uuid": "uuid",
  "attachments": [
    {"slot": "hud", "attached": true, "object_uuid": "uuid"},
    {"slot": "titler", "attached": true, "object_uuid": "uuid"}
  ]
}
```

Response includes:

- `token`
- `avatar_uuid`
- `character`
- `attachment_sync` (contains `should_dispense` and `missing_slots`)

Session token hashes are persisted in `hud_sessions` for server-side tracking.

## `POST /api/attachments/sync`
Syncs attachment states and returns current tracking state.

## `GET /api/characters?avatar_uuid=...`
Lists avatar characters.

## `POST /api/objects/register`
Registers or updates object metadata, coordinates, boundaries, linkset data.

## `POST /api/objects/command`
Queues a server command for an object (`queued` in `object_commands` table).

## `GET /api/environment/query?x=...&y=...&z=...`
Returns all zones matching the coordinate/elevation point.
