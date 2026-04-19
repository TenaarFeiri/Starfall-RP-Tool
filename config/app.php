<?php

declare(strict_types=1);

$env = getenv('APP_ENV') ?: 'development';
$secret = getenv('STARFALL_APP_SECRET') ?: '';

if ($secret === '') {
    throw new \RuntimeException('STARFALL_APP_SECRET must be set.');
}

return [
    'name' => 'Starfall RP Tool',
    'env' => $env,
    'secret' => $secret,
];
