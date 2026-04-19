<?php

declare(strict_types=1);

namespace Starfall\Domain\Character;

final class CharacterService
{
    public function __construct(
        private readonly CharacterRepository $characterRepository,
        private readonly LegacyCharacterImporterInterface $legacyImporter,
    ) {
    }

    /** @return array<string,mixed> */
    public function getOrCreateLastCharacter(string $avatarUuid): array
    {
        $existing = $this->characterRepository->findLastByAvatarUuid($avatarUuid);
        if ($existing !== null) {
            $this->characterRepository->touchLoaded((int)$existing['id']);
            return $existing;
        }

        $legacy = $this->legacyImporter->importForAvatar($avatarUuid);
        if ($legacy !== null) {
            return $this->characterRepository->create([
                'avatar_uuid' => $avatarUuid,
                'name' => $legacy['name'] ?? 'Imported Character',
                'description' => $legacy['description'] ?? '',
                'text_color' => $legacy['text_color'] ?? '#FFFFFF',
                'gender_tag' => $legacy['gender_tag'] ?? '',
                'consent_tag' => $legacy['consent_tag'] ?? '',
                'stat_weights' => $legacy['stat_weights'] ?? [],
                'imported_from_legacy' => 1,
            ]);
        }

        return $this->characterRepository->create([
            'avatar_uuid' => $avatarUuid,
            'name' => 'New Character',
            'description' => '',
            'text_color' => '#FFFFFF',
            'gender_tag' => '',
            'consent_tag' => '',
            'stat_weights' => [],
            'imported_from_legacy' => 0,
        ]);
    }

    /** @return list<array<string,mixed>> */
    public function listCharacters(string $avatarUuid): array
    {
        return $this->characterRepository->listByAvatarUuid($avatarUuid);
    }
}
