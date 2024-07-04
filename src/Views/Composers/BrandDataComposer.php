<?php

namespace IVR\MultiTenancyUtils\Views\Composers;

use Illuminate\View\View;
use IVR\MultiTenancyUtils\Contracts\RetrievesTenantBrandContract;

class BrandDataComposer
{
    /**
     * Create a new profile composer.
     */
    public function __construct(
        protected RetrievesTenantBrandContract $service,
    ) {}

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with('brand', $this->service->getTenantBrand(config('multitenancy-utils-laravel.tenant_key')));
    }
}
