<?php

declare(strict_types=1);

namespace Starfall\Domain\Environment;

use Starfall\Infrastructure\Database\Database;

final class EnvironmentZoneRepository
{
    public function __construct(private readonly Database $database)
    {
    }

    /** @param array<string,mixed> $zone */
    public function upsert(array $zone): void
    {
        $this->database->execute(
            'INSERT INTO environment_zones (zone_name, min_x, max_x, min_y, max_y, min_z, max_z, settings_json, updated_at, created_at)
             VALUES (:zone_name, :min_x, :max_x, :min_y, :max_y, :min_z, :max_z, :settings_json, NOW(), NOW())
             ON DUPLICATE KEY UPDATE min_x = VALUES(min_x), max_x = VALUES(max_x), min_y = VALUES(min_y), max_y = VALUES(max_y), min_z = VALUES(min_z), max_z = VALUES(max_z),
             settings_json = VALUES(settings_json), updated_at = NOW()',
            [
                'zone_name' => $zone['zone_name'],
                'min_x' => (float)$zone['min_x'],
                'max_x' => (float)$zone['max_x'],
                'min_y' => (float)$zone['min_y'],
                'max_y' => (float)$zone['max_y'],
                'min_z' => (float)$zone['min_z'],
                'max_z' => (float)$zone['max_z'],
                'settings_json' => json_encode($zone['settings'] ?? [], JSON_UNESCAPED_SLASHES),
            ]
        );
    }

    /** @return list<array<string,mixed>> */
    public function findByCoordinate(float $x, float $y, float $z): array
    {
        return $this->database->fetchAll(
            'SELECT * FROM environment_zones
             WHERE :x BETWEEN min_x AND max_x
               AND :y BETWEEN min_y AND max_y
               AND :z BETWEEN min_z AND max_z
             ORDER BY id ASC',
            ['x' => $x, 'y' => $y, 'z' => $z]
        );
    }
}
