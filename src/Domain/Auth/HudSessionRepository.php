<?php

declare(strict_types=1);

namespace Starfall\Domain\Auth;

use Starfall\Infrastructure\Database\Database;

final class HudSessionRepository
{
    public function __construct(private readonly Database $database)
    {
    }

    public function create(string $avatarUuid, string $tokenHash, int $ttlSeconds = 3600): void
    {
        $this->database->execute(
            'INSERT INTO hud_sessions (avatar_uuid, token_hash, expires_at, created_at, updated_at)
             VALUES (:avatar_uuid, :token_hash, DATE_ADD(NOW(), INTERVAL :ttl SECOND), NOW(), NOW())',
            [
                'avatar_uuid' => $avatarUuid,
                'token_hash' => $tokenHash,
                'ttl' => max(60, $ttlSeconds),
            ]
        );
    }
}
