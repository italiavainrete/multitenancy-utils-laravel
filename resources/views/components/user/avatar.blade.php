<div class="flex flex-col space-y-1 justify-center items-center  cursor-pointer">
    <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-secondary">
        <img src="{{ $user->avatar }}" alt="" class="w-full h-full object-cover">
    </div>
    <div class="font-semibold {{ $mode === "dark" ? 'text-white' : 'text-primary' }} text-sm">
        <div class="cursor-pointer">{{ $user->name }}</div>
    </div>
</div>
