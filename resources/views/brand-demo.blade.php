@php
    $colors = collect(IVR\MultiTenancyUtils\Enums\SemanticColor::cases())->map(fn(\UnitEnum $case) => $case->value);
    $variations = [ 'light', 'dark' ];
@endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand Colors Demo</title>
    <x-multi-tenancy::brand-favicons />
    <x-multi-tenancy::brand-style  />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans themed-font">
<div class="fixed top-0 left-0 w-full bg-yellow-200 text-black py-2 px-4 flex justify-between items-center">
    <span>Demo Page</span>
    <div>
        <a href="javascript:history.back()" class="text-blue-500 hover:underline mr-4">Back</a>
    </div>
</div>


<div class="container mt-16 mx-auto p-4 max-w-4xl">
    <h1 class="text-4xl font-bold mb-4">{{ $brand->name }} Colors Demo</h1>

    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-2">Navbar Examples</h2>
        <div class="mb-4">
            <h3>Primary</h3>
            <nav class="bg-primary text-white p-4 flex items-start">
                <x-multi-tenancy::brand-logo class="w-32" />
                <ul class="flex space-x-4 ml-auto">
                    <li><a href="#" class="hover:underline">Home</a></li>
                    <li><a href="#" class="hover:underline">About</a></li>
                    <li><a href="#" class="hover:underline">Contact</a></li>
                </ul>
            </nav>
        </div>
        <div class="mb-4">
            <h3>Secondary</h3>
            <nav class="bg-secondary text-white p-4 flex items-start">
                <x-multi-tenancy::brand-logo class="w-32" />
                <ul class="flex space-x-4 ml-auto">
                    <li><a href="#" class="hover:underline">Home</a></li>
                    <li><a href="#" class="hover:underline">About</a></li>
                    <li><a href="#" class="hover:underline">Contact</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <div>

        <h2 class="text-2xl font-semibold mb-2">Buttons Examples</h2>
        <div class="mb-8 grid grid-cols-1  gap-4">
            @foreach($colors as $color)
                <div>
                    <h3>Solid</h3>
                    <div class="mb-4 flex space-x-4">
                        <button class="bg-{{ $color }} text-white font-bold py-2 px-4 rounded">{{ ucfirst($color) }} Button</button>
                        <button class="bg-{{ $color }}-light text-gray-900 font-bold py-2 px-4 rounded">{{ ucfirst($color) }} Light Button</button>
                        <button class="bg-{{ $color }}-light text-{{ $color }} font-bold py-2 px-4 rounded">{{ ucfirst($color) }} Light Button</button>
                        <button class="bg-{{ $color }}-dark text-white font-bold py-2 px-4 rounded">{{ ucfirst($color) }} Dark Button</button>
                    </div>
                </div>

                <div>
                    <h3>Outline</h3>
                    <div class="mb-4 flex space-x-4">
                        <button class="border border-{{ $color }} text-{{ $color }} font-bold py-2 px-4 rounded">{{ ucfirst($color) }} Button</button>
                        <button class="border border-{{ $color }}-light text-{{ $color }}-light font-bold py-2 px-4 rounded">{{ ucfirst($color) }} Light Button</button>
                        <button class="border border-{{ $color }}-light text-{{ $color }} font-bold py-2 px-4 rounded">{{ ucfirst($color) }} Light Button</button>
                        <button class="border border-{{ $color }}-dark text-{{ $color }}-dark font-bold py-2 px-4 rounded">{{ ucfirst($color) }} Dark Button</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-2">Logo Examples</h2>
        <div class="max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div>
                <h3 class="mb-2 text-center">Logo on Primary</h3>
                <x-multi-tenancy::brand-logo class="px-4 py-2 text-center rounded-lg bg-primary text-white" />
            </div>
            <div>
                <h3 class="mb-2 text-center">Logo on Primary Light</h3>
                <x-multi-tenancy::brand-logo class="px-4 py-2 text-center rounded-lg bg-primary-light text-white" />
            </div>
            <div>
                <h3 class="mb-2 text-center">Logo on Primary Dark</h3>
                <x-multi-tenancy::brand-logo class="px-4 py-2 text-center rounded-lg bg-primary-dark text-white" />
            </div>
        </div>
        <div class="max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div>
                <h3 class="mb-2 text-center">Logo on Secondary</h3>
                <x-multi-tenancy::brand-logo class="px-4 py-2 text-center rounded-lg bg-secondary" />
            </div>
            <div>
                <h3 class="mb-2 text-center">Logo on Secondary Light</h3>
                <x-multi-tenancy::brand-logo class="px-4 py-2 text-center rounded-lg bg-secondary-light" />
            </div>
            <div>
                <h3 class="mb-2 text-center">Logo on Secondary Dark</h3>
                <x-multi-tenancy::brand-logo class="px-4 py-2 text-center rounded-lg bg-secondary-dark" />
            </div>
        </div>
        <div class="max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div>
                <h3 class="mb-2 text-center">Logo on Black</h3>
                <x-multi-tenancy::brand-logo class="px-4 py-2 text-center rounded-lg bg-black" />
            </div>
            <div>
                <h3 class="mb-2 text-center">Logo on White</h3>
                <x-multi-tenancy::brand-logo class="px-4 py-2 text-center rounded-lg bg-white" />
            </div>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-2">Text Color Examples</h2>
        <div class="p-4 border border-primary mb-2">This text is <span class="text-primary">primary</span>.</div>
        <div class="p-4 border border-secondary mb-2">This text is <span class="text-secondary">secondary</span>.</div>
        <div class="p-4 border border-success mb-2">This text is <span class="text-success">success</span>.</div>
        <div class="p-4 border border-info mb-2">This text is <span class="text-info">info</span>.</div>
        <div class="p-4 border border-warning mb-2">This text is <span class="text-warning">warning</span>.</div>
        <div class="p-4 border border-danger mb-2">This text is <span class="text-danger">danger</span>.</div>
    </div>
</div>

</body>
</html>
