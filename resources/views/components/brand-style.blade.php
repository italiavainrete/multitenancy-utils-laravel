@php

@endphp

<style>
    :root {
        --color-primary: {{ $brand->colors->primary() }};
        --color-primary-light: {{ $brand->colors->primary()->lighten($lightenPercentage) }};
        --color-primary-dark: {{ $brand->colors->primary()->darken($darkenPercentage) }};
        --color-secondary: {{ $brand->colors->secondary() }};
        --color-secondary-light: {{ $brand->colors->secondary()->lighten($lightenPercentage) }};
        --color-secondary-dark: {{ $brand->colors->secondary()->darken($darkenPercentage) }};
        --color-success: {{ $brand->colors->success() }};
        --color-success-light: {{ $brand->colors->success()->lighten($lightenPercentage) }};
        --color-success-dark: {{ $brand->colors->success()->darken($darkenPercentage) }};
        --color-info: {{ $brand->colors->info() }};
        --color-info-light: {{ $brand->colors->info()->lighten($lightenPercentage) }};
        --color-info-dark: {{ $brand->colors->info()->darken($darkenPercentage) }};
        --color-warning: {{ $brand->colors->warning() }};
        --color-warning-light: {{ $brand->colors->warning()->lighten($lightenPercentage) }};
        --color-warning-dark: {{ $brand->colors->warning()->darken($darkenPercentage) }};
        --color-danger: {{ $brand->colors->danger()  }};
        --color-danger-light: {{ $brand->colors->danger()->lighten($lightenPercentage) }};
        --color-danger-dark: {{ $brand->colors->danger()->darken($darkenPercentage) }};
    }

    .themed-font {
        font-family: "{{ $brand->font->family }}", sans-serif !important;
    }

    @foreach($colors as $color)
        .bg-{{ $color }} {
            background-color: var(--color-{{ $color }});
        }

        .text-{{ $color }} {
            color: var(--color-{{ $color }});
        }

        .border-{{ $color }} {
            border-color: var(--color-{{ $color }});
        }

        @foreach($variations as $variation)
            .bg-{{ $color }}-{{ $variation }} {
                background-color: var(--color-{{ $color }}-{{ $variation }});
            }

            .text-{{ $color }}-{{ $variation }} {
                color: var(--color-{{ $color }}-{{ $variation }});
            }

            .border-{{ $color }}-{{ $variation }} {
                border-color: var(--color-{{ $color }}-{{ $variation }});
            }
        @endforeach
    @endforeach
</style>
