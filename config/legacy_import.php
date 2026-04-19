<?php

declare(strict_types=1);

return [
    'directory' => dirname(__DIR__) . '/storage/legacy',
    'field_map' => [
        'name' => ['name', 'character_name', 'legacy_name'],
        'description' => ['description', 'bio', 'desc'],
        'text_color' => ['text_color', 'colour', 'color'],
        'gender_tag' => ['gender_tag', 'gender', 'tag_gender'],
        'consent_tag' => ['consent_tag', 'consent', 'adult_consent'],
        'stat_weights' => ['stat_weights', 'stats', 'combat_stats'],
    ],
];
