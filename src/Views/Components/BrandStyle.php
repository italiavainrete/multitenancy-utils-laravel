<?php

namespace IVR\MultiTenancyUtils\Views\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use IVR\MultiTenancyUtils\Enums\SemanticColor;

class BrandStyle extends Component
{
    public function render(): View
    {
        $colors = collect(SemanticColor::cases())->map(fn(\UnitEnum $case) => $case->value);
        $variations = [ 'light', 'dark' ];
        $lightenPercentage = config('multitenancy-utils-laravel.colors.lighten_percentage');
        $darkenPercentage = config('multitenancy-utils-laravel.colors.darken_percentage');

        return view(
            'multi-tenancy::components.brand-style',
            compact('colors','variations','lightenPercentage','darkenPercentage')
        );
    }
}
