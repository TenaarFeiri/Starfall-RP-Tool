<?php

declare(strict_types=1);

namespace Starfall\Domain\Character;

interface LegacyCharacterImporterInterface
{
    /** @return array<string,mixed>|null */
    public function importForAvatar(string $avatarUuid): ?array;
}
