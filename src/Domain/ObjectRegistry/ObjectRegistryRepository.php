<?php

declare(strict_types=1);

namespace Starfall\Domain\ObjectRegistry;

use Starfall\Infrastructure\Database\Database;

final class ObjectRegistryRepository
{
    public function __construct(private readonly Database $database)
    {
    }

    /** @param array<string,mixed> $payload */
    public function register(array $payload): void
    {
        $this->database->execute(
            'INSERT INTO registered_objects (object_uuid, owner_avatar_uuid, object_type, x, y, z, min_x, max_x, min_y, max_y, min_z, max_z, linkset_id, metadata_json, updated_at, created_at)
             VALUES (:object_uuid, :owner_avatar_uuid, :object_type, :x, :y, :z, :min_x, :max_x, :min_y, :max_y, :min_z, :max_z, :linkset_id, :metadata_json, NOW(), NOW())
             ON DUPLICATE KEY UPDATE owner_avatar_uuid = VALUES(owner_avatar_uuid), object_type = VALUES(object_type), x = VALUES(x), y = VALUES(y), z = VALUES(z),
             min_x = VALUES(min_x), max_x = VALUES(max_x), min_y = VALUES(min_y), max_y = VALUES(max_y), min_z = VALUES(min_z), max_z = VALUES(max_z),
             linkset_id = VALUES(linkset_id), metadata_json = VALUES(metadata_json), updated_at = NOW()',
            [
                'object_uuid' => $payload['object_uuid'],
                'owner_avatar_uuid' => $payload['owner_avatar_uuid'] ?? '',
                'object_type' => $payload['object_type'] ?? 'generic',
                'x' => (float)($payload['x'] ?? 0),
                'y' => (float)($payload['y'] ?? 0),
                'z' => (float)($payload['z'] ?? 0),
                'min_x' => (float)($payload['min_x'] ?? 0),
                'max_x' => (float)($payload['max_x'] ?? 0),
                'min_y' => (float)($payload['min_y'] ?? 0),
                'max_y' => (float)($payload['max_y'] ?? 0),
                'min_z' => (float)($payload['min_z'] ?? 0),
                'max_z' => (float)($payload['max_z'] ?? 0),
                'linkset_id' => $payload['linkset_id'] ?? null,
                'metadata_json' => json_encode($payload['metadata'] ?? [], JSON_UNESCAPED_SLASHES),
            ]
        );
    }

    /** @param array<string,mixed> $payload */
    public function enqueueCommand(string $objectUuid, string $command, array $payload): string
    {
        return $this->database->insert(
            'INSERT INTO object_commands (object_uuid, command_name, payload_json, status, created_at, updated_at)
             VALUES (:object_uuid, :command_name, :payload_json, :status, NOW(), NOW())',
            [
                'object_uuid' => $objectUuid,
                'command_name' => $command,
                'payload_json' => json_encode($payload, JSON_UNESCAPED_SLASHES),
                'status' => 'queued',
            ]
        );
    }
}
