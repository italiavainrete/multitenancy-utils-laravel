<div class="flex space-x-8 items-start">
    <!-- Settings Dropdown -->
    <div class="hidden sm:flex sm:items-center items-center">
        <x-multi-tenancy::ui.dropdown align="top" width="48">
            <x-slot name="trigger">
                <x-multi-tenancy::user.avatar :mode="$mode" :user="$user" />
            </x-slot>

            <x-slot name="content">
                <x-multi-tenancy::user.panel :user="$user" />
            </x-slot>
        </x-multi-tenancy::ui.dropdown>
    </div>

    <!-- Card Badge -->
    <x-multi-tenancy::user.loyalty-card-badge :mode="$mode" :user="$user" />
</div>
