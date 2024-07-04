<?php

// config for IVR/MultiTenancyUtils
return [
    'tenant_key' => env('TENANT_KEY','italia-va-in-rete'),
    'crm-api' => [
        'base_url' => env('CRM_API_BASE_URL', 'https://api.crm.italiavainrete.it')
    ],
    'cache' => [
        'ttl' => env('CACHE_TTL', 600),
    ],
    'colors' => [
        'lighten_percentage' => env('COLORS_LIGHTEN_PERCENTAGE', 40),
        'darken_percentage' => env('COLORS_DARKENEN_PERCENTAGE', 15),
    ],
    'cdn' => env('CDN_BASE_URL', 'https://d3vk0yr71svhiq.cloudfront.net/ivr'),
];
