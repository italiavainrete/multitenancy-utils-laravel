<?php

use IVR\Sdk\Clients\Contracts\CrmApiClientContract;
use IVR\Sdk\Clients\Contracts\StaticDataClientContract;
use IVR\Sdk\Clients\Contracts\UserAreaClientContract;
use IVR\Sdk\Multitenancy\Contracts\TenantResolverContract;

it('boots the SDK service provider with bound client contracts', function () {
    expect(app(StaticDataClientContract::class))->toBeInstanceOf(StaticDataClientContract::class);
    expect(app(CrmApiClientContract::class))->toBeInstanceOf(CrmApiClientContract::class);
    expect(app(UserAreaClientContract::class))->toBeInstanceOf(UserAreaClientContract::class);
    expect(app(TenantResolverContract::class))->toBeInstanceOf(TenantResolverContract::class);
});

it('exposes the ivr-sdk config', function () {
    expect(config('ivr-sdk.fallback_tenant'))->toBe('italia-va-in-rete');
    expect(config('ivr-sdk.static.manifest_path'))->toBe('tenants/index.json');
});
