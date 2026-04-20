<x-layouts::app :title="__('Edit Kategori')">
    <div class="flex flex-col gap-6 w-full">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Edit Kategori') }}</flux:heading>
            </div>
        </div>

        <flux:separator variant="subtle" />

        <form action="{{ route('admin.category.update', $category->id) }}" method="POST" class="flex flex-col gap-6 w-full max-w-4xl mt-2">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">

                <flux:input name="category_name" :label="__('Nama kategori')" type="text" autofocus
                    :placeholder="__('Masukkan nama kategori')" value="{{ old('category_name', $category->name) }}" />
            </div>


            <div class="flex items-center justify-end gap-3 mt-4">
                <flux:button :href="route('admin.category.index')" wire:navigate variant="ghost">{{ __('Batal') }}
                </flux:button>
                <flux:button type="submit" variant="primary" class="bg-blue-600 hover:bg-blue-700 text-white">
                    {{ __('Simpan Kategori') }}</flux:button>
            </div>
        </form>

    </div>
</x-layouts::app>