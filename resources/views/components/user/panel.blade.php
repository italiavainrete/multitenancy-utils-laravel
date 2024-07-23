<div class="bg-white sm:rounded overflow-hidden shadow-lg">
    <div class="text-center p-6 bg-primary border-b">
        <div class="rounded-lg border-2 border-secondary w-32 overflow-hidden mx-auto">
            <img src="{{ 'https://api.dicebear.com/9.x/initials/svg?seed=' . $user->name }}" class="w-32 h-32 rounded-lg" alt="avatar">
        </div>
        <p class="pt-2 text-lg font-semibold text-gray-50">{{ $user->name }}</p>
        <p class="text-sm text-gray-100"> {{ $user->email }}</p>
        <div class="mt-5">
            <a
                href="{{ $brand->links->account . '/' . \IVR\MultiTenancyUtils\Constants\NetworkLinks::PROFILE_EDIT }}"
                class="border rounded-full py-2 px-4 text-xs font-semibold text-gray-100"
            >
                {{ __('Dati Profilo') }}
            </a>
        </div>
    </div>
    <div class="border-b">
        <a href="{{ $brand->links->account . '/' . \IVR\MultiTenancyUtils\Constants\NetworkLinks::LOYALTY_DASHBOARD }}" >
            <div class="px-4 py-2 hover:bg-gray-100 flex">
                <div class="text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path d="M4.5 3.75a3 3 0 0 0-3 3v.75h21v-.75a3 3 0 0 0-3-3h-15Z" />
                        <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-7.5Zm-18 3.75a.75.75 0 0 1 .75-.75h6a.75.75 0 0 1 0 1.5h-6a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd" />
                    </svg>

                </div>
                <div class="pl-3">
                    <p class="text-sm font-medium text-gray-800 leading-none">
                        La Mia Card
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Saldo e Movimenti</p>
                </div>
            </div>
        </a>
        @if($brand->links->marketplace)
            <a href="{{ $brand->links->marketplace . '/' . \IVR\MultiTenancyUtils\Constants\NetworkLinks::MARKETPLACE_ORDERS }}" >
                <div class="px-4 py-2 hover:bg-gray-100 flex">
                    <div class="text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                            <path fill-rule="evenodd" d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087ZM12 10.5a.75.75 0 0 1 .75.75v4.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-3 3a.75.75 0 0 1-1.06 0l-3-3a.75.75 0 1 1 1.06-1.06l1.72 1.72v-4.94a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                        </svg>

                    </div>
                    <div class="pl-3">
                        <p class="text-sm font-medium text-gray-800 leading-none">Ordini</p>
                        <p class="text-xs text-gray-500 mt-1">I tuoi acquisti online</p>
                    </div>
                </div>
            </a>
        @endif
    </div>

    <!-- Authentication -->
    <form method="POST" action="{{ '/logout' }}">
        @csrf
        <button type="submit" class="w-full px-4 py-2 hover:bg-gray-100 flex text-gray-800 leading-none py-4">
            <span class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                </svg>

                <span>{{ __('Log Out') }}</span>
            </span>
        </button>
    </form>
</div>
