<x-layouts::app :title="__('Tambah Produk')">
    <div class="flex flex-col gap-6 w-full">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Tambah Produk') }}</flux:heading>
            </div>
        </div>

        <flux:separator variant="subtle" />

        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6 w-full max-w-4xl mt-2">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:input name="product_name" :label="__('Nama produk')" type="text" autofocus
                    :placeholder="__('Masukkan nama produk')" value="{{ old('product_name') }}" />

                <flux:select name="product_category" :label="__('kategori produk')" :placeholder="__('Pilih kategori')">
                    @foreach ($categories as $cate)
                        <flux:select.option value="{{ $cate->id }}"
                            :selected="old('product_category') == $cate->name"> {{ __($cate->name) }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input name="price" :label="__('Harga produk')" type="number"
                    :placeholder="__('contoh 5000')" value="{{ old('price') }}" />

                <flux:input name="image" :label="__('Gambar produk')" type="file" accept="image/png, image/jpeg, image/webp" class="file:bg-green-50 file:text-green-700 file:px-4 file:py-2 file:rounded-lg" value="{{ old('image') }}" />
            </div>


            <div class="flex items-center justify-end gap-3 mt-4">
                <flux:button :href="route('admin.product.index')" wire:navigate variant="ghost">{{ __('Batal') }}
                </flux:button>
                <flux:button type="submit" variant="primary" class="bg-blue-600 hover:bg-blue-700 text-white">
                    {{ __('Simpan Produk') }}</flux:button>
            </div>
        </form>

    </div>
</x-layouts::app>
