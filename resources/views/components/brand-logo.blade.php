<div {{ $attributes->merge(['class' => 'block']) }}>
    @switch($mode)
        @case('dark')
            @if($brand->logo_dark->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_RASTER)
                <img  style="max-height: 60px" src="{{ $brand->logo_dark->imageUrl }}" alt="{{ $brand->name }} logo">
            @elseif($brand->logo_dark->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_VECTOR)
                {!! $brand->logo_dark->svgMarkup !!}
            @endif
            @break

        @case('light')
            @if($brand->logo->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_RASTER)
                <img style="max-height: 60px" src="{{ $brand->logo->imageUrl }}" alt="{{ $brand->name }} logo">
            @elseif($brand->logo->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_VECTOR)
                {!! $brand->logo->svgMarkup !!}
            @endif
            @break
    @endswitch
</div>
