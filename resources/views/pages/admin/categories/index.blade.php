<x-layouts::app :title="__('Daftar Pengguna')">
    <div class="flex flex-col gap-6 w-full">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Manajemen Kategori') }}</flux:heading>
                <flux:subheading size="lg" class="text-zinc-500 dark:text-zinc-400">
                    {{ __('Kelola kategori produk.') }}</flux:subheading>
            </div>
            <flux:button href="{{ route('admin.category.create') }}" class="bg-blue-600 text-white hover:bg-blue-700"
                icon="plus" variant="primary">
                {{ __('Tambah') }}
            </flux:button>
        </div>

        <flux:separator variant="subtle" />
        @livewire('table.categories')

    </div>
</x-layouts::app>
