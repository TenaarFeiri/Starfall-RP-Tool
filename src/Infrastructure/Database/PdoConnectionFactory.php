<?php

declare(strict_types=1);

namespace Starfall\Infrastructure\Database;

use PDO;

final class PdoConnectionFactory
{
    /** @param array<string,mixed> $config */
    public static function make(array $config): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'] ?? '127.0.0.1',
            (int)($config['port'] ?? 3306),
            $config['database'] ?? 'starfall_rp',
            $config['charset'] ?? 'utf8mb4'
        );

        return new PDO(
            $dsn,
            (string)($config['username'] ?? ''),
            (string)($config['password'] ?? ''),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
}
