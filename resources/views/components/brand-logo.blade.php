<div {{ $attributes->merge(['class' => 'block']) }}>
    @if($brand->logo->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_RASTER)
        <img src="{{ $brand->logo->imageUrl }}" alt="{{ $brand->name }} logo">
    @elseif($brand->logo->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_VECTOR)
        {!! $brand->logo->svgMarkup !!}
    @endif
</div>
