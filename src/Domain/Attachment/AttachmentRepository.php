<?php

declare(strict_types=1);

namespace Starfall\Domain\Attachment;

use Starfall\Infrastructure\Database\Database;

final class AttachmentRepository
{
    public function __construct(private readonly Database $database)
    {
    }

    public function upsert(string $avatarUuid, string $slot, ?string $objectUuid, bool $attached): void
    {
        $this->database->execute(
            'INSERT INTO temporary_attachments (avatar_uuid, slot_name, object_uuid, attached, status, last_seen_at, updated_at, created_at)
             VALUES (:avatar_uuid, :slot_name, :object_uuid, :attached, :status, NOW(), NOW(), NOW())
             ON DUPLICATE KEY UPDATE object_uuid = VALUES(object_uuid), attached = VALUES(attached), status = VALUES(status), last_seen_at = NOW(), updated_at = NOW()',
            [
                'avatar_uuid' => $avatarUuid,
                'slot_name' => $slot,
                'object_uuid' => $objectUuid,
                'attached' => (int)$attached,
                'status' => $attached ? 'attached' : 'pending',
            ]
        );
    }

    /** @return list<array<string,mixed>> */
    public function listForAvatar(string $avatarUuid): array
    {
        return $this->database->fetchAll(
            'SELECT * FROM temporary_attachments WHERE avatar_uuid = :avatar_uuid ORDER BY slot_name ASC',
            ['avatar_uuid' => $avatarUuid]
        );
    }

    public function markTrashNotAttachedSinceMinutes(int $minutes): int
    {
        $minutes = max(1, $minutes);

        return $this->database->execute(
            sprintf(
            'UPDATE temporary_attachments
             SET status = :status, updated_at = NOW()
             WHERE attached = 0 AND status <> :status AND last_seen_at < (NOW() - INTERVAL %d MINUTE)',
             $minutes
            ),
            [
                'status' => 'trash',
            ]
        );
    }
}
