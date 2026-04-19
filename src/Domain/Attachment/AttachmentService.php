<?php

declare(strict_types=1);

namespace Starfall\Domain\Attachment;

final class AttachmentService
{
    public function __construct(private readonly AttachmentRepository $attachmentRepository)
    {
    }

    /**
     * @param list<array{slot:string,object_uuid?:string,attached:bool}> $states
     * @return array<string,mixed>
     */
    public function sync(string $avatarUuid, array $states): array
    {
        foreach ($states as $state) {
            $this->attachmentRepository->upsert(
                avatarUuid: $avatarUuid,
                slot: $state['slot'],
                objectUuid: $state['object_uuid'] ?? null,
                attached: (bool)$state['attached']
            );
        }

        $current = $this->attachmentRepository->listForAvatar($avatarUuid);
        $missing = array_values(array_map(
            fn (array $row): string => (string)$row['slot_name'],
            array_filter($current, fn (array $row): bool => !(bool)$row['attached'])
        ));

        return [
            'attachments' => $current,
            'should_dispense' => count($missing) > 0,
            'missing_slots' => $missing,
        ];
    }

    public function cleanupStalePending(int $minutes = 5): int
    {
        return $this->attachmentRepository->markTrashNotAttachedSinceMinutes($minutes);
    }
}
