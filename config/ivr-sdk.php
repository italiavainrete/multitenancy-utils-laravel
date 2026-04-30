<?php

return [
    'tenant_key' => env('TENANT_KEY', 'italia-va-in-rete'),
    'fallback_tenant' => env('TENANT_FALLBACK', 'italia-va-in-rete'),

    'static' => [
        'base_url' => env('IVR_SDK_STATIC_BASE_URL', 'https://data.italiavainrete.it'),
        'manifest_path' => 'tenants/index.json',
        'tenant_path_pattern' => 'tenants/{slug}.json',
        'shops_path_pattern' => 'tenants/{slug}/shops.json',
        'cache_ttl' => (int) env('IVR_SDK_STATIC_TTL', 3600),
    ],

    'crm' => [
        'base_url' => env('IVR_SDK_CRM_BASE_URL', 'https://api.crm.italiavainrete.it'),
        'auth_token' => env('IVR_SDK_CRM_TOKEN'),
        'cache_ttl' => (int) env('IVR_SDK_CRM_TTL', 300),
    ],

    'user_area' => [
        'base_url' => env('IVR_SDK_USER_AREA_BASE_URL'),
    ],

    'cache_store' => env('IVR_SDK_CACHE_STORE', 'default'),

    'dev_mode' => [
        'force_localhost_links' => (bool) env('TENANT_DEV_MODE', false),
        'links' => [
            'main' => env('TENANT_DEV_LINK_MAIN', 'http://localhost:8000'),
            'account' => env('TENANT_DEV_LINK_ACCOUNT', 'http://localhost:9000'),
            'marketplace' => env('TENANT_DEV_LINK_MARKETPLACE', 'http://localhost:8080'),
        ],
    ],
];
