<a href="{{url('/')}}">
    <span class="sr-only">{{ $brand->name }}</span>
    @if($brand->logo->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_RASTER)
        <img class="h-16 w-auto" src="{{ $brand->logo->imageUrl }}" alt="">
    @elseif($brand->logo->format === \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_VECTOR)
        <div class="w-[160px] text-white">
            {!! $brand->logo->svgMarkup !!}
        </div>
    @endif
</a>
