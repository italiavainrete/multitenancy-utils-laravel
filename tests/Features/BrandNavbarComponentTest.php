<?php

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\ComponentAttributeBag;
use IVR\MultiTenancyUtils\Constants\DefaultPages;
use IVR\MultiTenancyUtils\Constants\Tenants;
use IVR\MultiTenancyUtils\Data\Brand\BrandData;
use IVR\MultiTenancyUtils\Data\UserData;
use IVR\MultiTenancyUtils\Views\Components\BrandFavicons;
use IVR\MultiTenancyUtils\Views\Components\BrandLogo;
use IVR\MultiTenancyUtils\Views\Components\BrandNavbar;

it('renders navbar in light mode', function () {
    $brand = app('tenant');

    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    Auth::login(new User);

    $user = new UserData(
        id: '1',
        name: 'John Doe',
        email: 'john@email.com',
        avatar: 'https://avatars.com/jon.svg',
        cardNumber: '123456',
        cardBalance: '12.99'
    );
    $view = (new BrandNavbar($user, "light"))->render()->render();


    expect($view)
        ->toContain($brand->logo->imageUrl)
        ->toContain('John Doe')
        ->toContain('john@email.com')
        ->toContain('https://avatars.com/jon.svg',)
        ->toContain('123456')
        ->toContain('12.99');

    foreach (DefaultPages::HEADER as $link)
    {
        expect($view)
            ->toContain('href="' . ($brand->links->main ?? '') . '/' . $link['slug'] . '"')
            ->toContain($link['title'])
            ->toContain($link['icon']);
    }
});

it('renders navbar in dark mode', function () {
    $brand = app('tenant');

    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    Auth::login(new User);

    $user = new UserData(
        id: '1',
        name: 'John Doe',
        email: 'john@email.com',
        avatar: 'https://avatars.com/jon.svg',
        cardNumber: '123456',
        cardBalance: '12.99'
    );
    $view = (new BrandNavbar($user, "dark"))->render()->render();


    expect($view)
        ->toContain($brand->logo_dark->imageUrl)
        ->toContain('John Doe')
        ->toContain('john@email.com')
        ->toContain('https://avatars.com/jon.svg',)
        ->toContain('123456')
        ->toContain('12.99');

    foreach (DefaultPages::HEADER as $link)
    {
        expect($view)
            ->toContain('href="' . ($brand->links->main ?? '') . '/' . $link['slug'] . '"')
            ->toContain($link['title'])
            ->toContain($link['icon']);
    }
});


it('will include marketplace link if present', function () {
    $brand = app('tenant');
    $brand->links->marketplace = "https://marketplace.com";

    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    Auth::login(new User);

    $user = new UserData(
        id: '1',
        name: 'John Doe',
        email: 'john@email.com',
        avatar: 'https://avatars.com/jon.svg',
        cardNumber: '123456',
        cardBalance: '12.99'
    );
    $view = (new BrandNavbar($user, "dark"))->render()->render();


    expect($view)
        ->toContain($brand->logo_dark->imageUrl)
        ->toContain('id="navbar:marketplace"')
        ->toContain("https://marketplace.com");
});

it('will not include marketplace link if missing', function () {
    $brand = app('tenant');
    $brand->links->marketplace = null;

    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    Auth::login(new User);

    $user = new UserData(
        id: '1',
        name: 'John Doe',
        email: 'john@email.com',
        avatar: 'https://avatars.com/jon.svg',
        cardNumber: '123456',
        cardBalance: '12.99'
    );
    $view = (new BrandNavbar($user, "dark"))->render()->render();


    expect($view)
        ->not()->toContain('id="navbar:marketplace"')
        ->not()->toContain("https://marketplace.com");
});
