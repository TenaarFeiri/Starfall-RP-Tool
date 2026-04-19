<?php

declare(strict_types=1);

return [
    'name' => 'Starfall RP Tool',
    'env' => 'development',
    'secret' => getenv('STARFALL_APP_SECRET') ?: 'change-me-in-production',
];
