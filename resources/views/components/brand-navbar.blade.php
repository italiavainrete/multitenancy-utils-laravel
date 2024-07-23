<nav x-data="{ open: false }" class="{{ $mode === "dark" ? 'bg-primary text-white' : 'bg-white text-primary' }} shadow sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">

        <div class="flex space-x-8 items-center">
            <a href="{{ $brand->links->main ?? url('/') }}">
                <x-multi-tenancy::brand-logo :mode="$mode" class="w-32"/>
            </a>

            <!-- Primary Navigation Menu -->
            <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">

                @foreach(collect(\IVR\MultiTenancyUtils\Constants\DefaultPages::HEADER) as $page)
                    <a id="{{ "navbar:" . IVR\MultiTenancyUtils\Enums\NavBarEnum::HEADER->value . ":" . $page['slug'] }}"
                       href="{{ ($brand->links->main ?? '') . '/' .$page['slug'] }}"
                       class="flex space-x-2 items-center py-2 border-t-4 border-transparent hover:text-primary hover:border-primary">
                        {!! $page['icon'] !!}
                        <span>{{$page['title']}}</span>
                    </a>
                @endforeach

                @if(!empty($brand->links->marketplace))
                    <a id="navbar:marketplace"
                       href="{{ $brand->links->marketplace }}"
                       class="flex space-x-2 items-center py-2 border-t-4 border-transparent hover:text-primary hover:border-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>

                        <span>Marketplace</span>
                    </a>
                @endif

            </div>
        </div>

        @auth
            <x-multi-tenancy::user-dropdown :user="$user" :mode="$mode" />
        @endauth

        @guest
            <a href="{{ $brand->contacts->userarea ?? '' }}" class="ml-4 bg-secondary text-white px-4 py-2 rounded">Accedi
                o Iscriviti</a>
        @endguest

        <!-- Hamburger -->
        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round"
                          stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                          stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class=" flex flex-col px-4 ">

            @foreach(collect(\IVR\MultiTenancyUtils\Constants\DefaultPages::HEADER) as $page)
                <a href="{{ (app('tenant')->contacts->main ?? url('/')) . '/' . $page['slug'] }}"
                   class="mr-4 py-2 text-gray-200 border-t-4 border-transparent hover:text-primary hover:border-primary">
                    {{$page['title']}}
                </a>
            @endforeach


            @auth
                <div class="p-6">
                    <x-multi-tenancy::user.panel :user="$user" />
                </div>
            @endauth

            @guest
                <a href="{{ $brand->contacts->userarea ?? '' }}" class="my-4 bg-secondary text-white px-4 py-2 rounded">Accedi
                    o Iscriviti</a>
            @endguest
        </div>

    </div>
</nav>
