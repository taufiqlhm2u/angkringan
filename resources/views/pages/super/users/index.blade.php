<x-layouts::app :title="__('Daftar Pengguna')">
    <div class="flex flex-col gap-6 w-full">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Manajemen Pengguna') }}</flux:heading>
                <flux:subheading size="lg" class="text-zinc-500 dark:text-zinc-400">
                    {{ __('Kelola pengguna yang memiliki akses ke sistem.') }}</flux:subheading>
            </div>
            <flux:button href="{{ route('super.users.create') }}" class="bg-blue-600 text-white hover:bg-blue-700"
                icon="plus" variant="primary">
                {{ __('Tambah') }}
            </flux:button>
        </div>

        <flux:separator variant="subtle" />
        @livewire('table.users')

    </div>
</x-layouts::app>
