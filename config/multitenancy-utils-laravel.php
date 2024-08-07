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
    'domain_discovery' => [
        'enable' => env('TENANT_DOMAIN_DISCOVERY', false),
        'fallback_tenant' => \IVR\MultiTenancyUtils\Constants\Tenants::DEFAULT_TENANT_KEY,
    ],
    'force_static_tenant' => env('TENANT_FORCE_STATIC', false),

    'dev_mode' => [
        'force_localhost_links' => env('TENANT_DEV_MODE', false),
        'links' => [
            'main' => env('TENANT_DEV_LINK_MAIN', "http://localhost:7000"),
            'account' => env('TENANT_DEV_LINK_ACCOUNT', "http://localhost"),
            'marketplace' => env('TENANT_DEV_LINK_MARKETPLACE', "http://localhost:8000"),
        ],
    ]

];
