<?php

declare(strict_types=1);

namespace Starfall\Domain\Character;

final class FileMappedLegacyCharacterImporter implements LegacyCharacterImporterInterface
{
    /** @param array<string,array<int,string>> $fieldMap */
    public function __construct(
        private readonly string $legacyDirectory,
        private readonly array $fieldMap,
    ) {
    }

    public function importForAvatar(string $avatarUuid): ?array
    {
        $path = rtrim($this->legacyDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $avatarUuid . '.json';
        if (!is_file($path)) {
            return null;
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            return null;
        }

        $raw = json_decode($contents, true);
        if (!is_array($raw)) {
            return null;
        }

        $normalized = [];
        foreach ($this->fieldMap as $target => $candidates) {
            foreach ($candidates as $candidate) {
                if (array_key_exists($candidate, $raw)) {
                    $normalized[$target] = $raw[$candidate];
                    break;
                }
            }
        }

        return $normalized ?: null;
    }
}
