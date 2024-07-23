<a href="{{ url('dashboard') }}" class="flex flex-col space-y-2 {{ $mode === 'dark' ? 'text-white' : 'text-primary' }} text-xs sm:text-sm">
    <div class="flex space-x-2 items-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8  ">
            <path d="M4.5 3.75a3 3 0 0 0-3 3v.75h21v-.75a3 3 0 0 0-3-3h-15Z" />
            <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-7.5Zm-18 3.75a.75.75 0 0 1 .75-.75h6a.75.75 0 0 1 0 1.5h-6a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd" />
        </svg>

        <span class="{{ $mode === 'dark' ? 'bg-secondary text-primary' : 'bg-primary text-white' }} px-2 py-1 font-bold rounded-sm">#{{ $user->cardNumber }}</span>
    </div>

    <div class="flex justify-end space-x-2">
        <span>Saldo: </span>
        <strong class="text-secondary">{{ $user->cardBalance }} Punti</strong>
    </div>
</a>
