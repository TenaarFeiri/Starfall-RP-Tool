<?php

declare(strict_types=1);

return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => (int)(getenv('DB_PORT') ?: 3306),
    'database' => getenv('DB_NAME') ?: 'starfall_rp',
    'username' => getenv('DB_USER') ?: 'starfall',
    'password' => getenv('DB_PASS') ?: 'starfall',
    'charset' => 'utf8mb4',
];
