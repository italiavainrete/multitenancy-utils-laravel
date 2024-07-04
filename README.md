# MultiTenancy Utils for Laravel

`laravel/multitenancy-utils-laravel` is a package designed to simplify multi-tenancy management in Laravel applications. It provides tools to handle tenant-specific configurations, assets, branding, and more.

## Installation

To install the package, use composer:

```bash
composer require laravel/multitenancy-utils-laravel
```


## Configuration

Most of the options can be customized using `.env` variabiles, however you can publish the config file with:

```bash
php artisan vendor:publish --tag="multitenancy-utils-laravel-config"
```

This is the contents of the published config file `config/multitenancy-utils-laravel.php`,  modify it to suit your needs:

```php
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
```

## Usage

### Controllers

Two routes are provided for serving tenant-specific web manifest and browser config XML:

```php
Route::get('/site.webmanifest', WebManifestController::class)->name('web-manifest');
Route::get('/browserconfig.xml', BrowserConfigXmlController::class)->name('browser-config-xml');
```

### Getting Started

The package aims to simplify rendering of a series of common tags to define the style of a page using data from the tenant's brand 

## View Components

You can use the following view components provided by the package:

- `x-multi-tenancy::brand-favicons` - Renders tenant favicon meta tags.
- `x-multi-tenancy::brand-style` - Renders styles for tenant.
- `x-multi-tenancy::brand-logo` - Displays tenant logo.

## Full Usage Example

Here's a full example of a Blade template using the components and shared brand data:

```html
<!DOCTYPE html>
<html>
<head>
    <title>{{ $brand->name }} Marketplace</title>
    
    <!-- Renders Tenant Favicon meta tags -->
    <x-multi-tenancy::brand-favicons />

    <!-- Render Styles for Tenant -->
    <x-multi-tenancy::brand-style />
</head>
<body class="themed-font antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">

    <!-- Background Tenant colors -->
    <header class="bg-primary">
        <!-- Tenant Logo -->
        <x-multi-tenancy::brand-logo />
    </header>

    <main>
        <!-- Text, Border and Background tenant primary/secondary and semantic colors including light/dark variations -->
        <h1 class="text-primary border-primary bg-primary-light">Hello World</h1>
        <p class="text-success border-success bg-success-light">Success!</p>
        <p class="text-danger border-danger bg-danger-light">Danger!</p>
        <p class="text-warning border-warning bg-warning-light">Warning!</p>
        <p class="text-info border-info bg-info-light">Info!</p>
    </main>

</div>
</body>
</html>
```

## Testing

```bash
composer test
```


- [Salvo Bonanno](https://github.com/salvobee)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
