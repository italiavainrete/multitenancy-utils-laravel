@props(['mode' => 'light', 'size' => null])

@php
    $height = match($size) {
        'l' => '100px',
        default => '60px',
    };
@endphp

<div {{ $attributes->merge(['class' => 'block']) }}>
    @switch($mode)
        @case('dark')
            @if($brand->logo_dark->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_RASTER)
                <img style="max-height: {{ $height }}" src="{{ $brand->logo_dark->imageUrl }}" alt="{{ $brand->name }} logo">
            @elseif($brand->logo_dark->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_VECTOR)
                <div style="max-height: {{ $height }}; display: inline-block;">
                    <div style="height: 100%; max-height: inherit;">
                        {!! $brand->logo_dark->svgMarkup !!}
                    </div>
                </div>
            @endif
            @break

        @case('light')
            @if($brand->logo->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_RASTER)
                <img style="max-height: {{ $height }}" src="{{ $brand->logo->imageUrl }}" alt="{{ $brand->name }} logo">
            @elseif($brand->logo->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_VECTOR)
                <div style="max-height: {{ $height }}; display: inline-block;">
                    <div style="height: 100%; max-height: inherit;">
                        {!! $brand->logo->svgMarkup !!}
                    </div>
                </div>
            @endif
            @break
    @endswitch
</div>
