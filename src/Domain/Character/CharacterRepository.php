<?php

declare(strict_types=1);

namespace Starfall\Domain\Character;

use Starfall\Infrastructure\Database\Database;

final class CharacterRepository
{
    public function __construct(private readonly Database $database)
    {
    }

    /** @return array<string,mixed>|null */
    public function findLastByAvatarUuid(string $avatarUuid): ?array
    {
        return $this->database->fetchOne(
            'SELECT * FROM characters WHERE avatar_uuid = :avatar_uuid ORDER BY last_loaded_at DESC, id DESC LIMIT 1',
            ['avatar_uuid' => $avatarUuid]
        );
    }

    /** @return list<array<string,mixed>> */
    public function listByAvatarUuid(string $avatarUuid): array
    {
        return $this->database->fetchAll(
            'SELECT * FROM characters WHERE avatar_uuid = :avatar_uuid ORDER BY updated_at DESC, id DESC',
            ['avatar_uuid' => $avatarUuid]
        );
    }

    /** @param array<string,mixed> $payload @return array<string,mixed> */
    public function create(array $payload): array
    {
        $id = $this->database->insert(
            'INSERT INTO characters (avatar_uuid, name, description, text_color, gender_tag, consent_tag, stat_weights_json, imported_from_legacy, last_loaded_at, created_at, updated_at)
             VALUES (:avatar_uuid, :name, :description, :text_color, :gender_tag, :consent_tag, :stat_weights_json, :imported_from_legacy, NOW(), NOW(), NOW())',
            [
                'avatar_uuid' => $payload['avatar_uuid'],
                'name' => $payload['name'] ?? 'Unnamed Character',
                'description' => $payload['description'] ?? '',
                'text_color' => $payload['text_color'] ?? '#FFFFFF',
                'gender_tag' => $payload['gender_tag'] ?? '',
                'consent_tag' => $payload['consent_tag'] ?? '',
                'stat_weights_json' => json_encode($payload['stat_weights'] ?? [], JSON_UNESCAPED_SLASHES),
                'imported_from_legacy' => (int)($payload['imported_from_legacy'] ?? 0),
            ]
        );

        return $this->database->fetchOne('SELECT * FROM characters WHERE id = :id', ['id' => (int)$id]) ?? [];
    }

    public function touchLoaded(int $characterId): void
    {
        $this->database->execute(
            'UPDATE characters SET last_loaded_at = NOW(), updated_at = NOW() WHERE id = :id',
            ['id' => $characterId]
        );
    }
}
