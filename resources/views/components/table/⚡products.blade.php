<?php

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;

new class extends Component {
    use \Livewire\WithPagination;

    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $search;
    public $confirmingStatusId = null;
    public $isConfirmingStatusModalOpen = false;
    public $switchKeys = [];
    public $categoryFilter = null;

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function Products()
    {
        $query = Product::query()->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query);

        $keyword = $this->search;

        if ($keyword) {
            $query = Product::whereRaw('Lower(name) LIKE ?', ['%' . strtolower($keyword) . '%'])->orWhere('price', 'LIKE', '%' . $keyword . '%')->orWhere('stock', 'LIKE', '%' . $keyword . '%');
        }

        $category = $this->categoryFilter;

        if ($category) {
            $query = $query->where('category_id', $category);
        }

        $products = $query->paginate(10);
        return $products;
    }

    // update status
    public function toggleStatus($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return;
        }

        $product->status = $product->status === 'active' ? 'inactive' : 'active';

        $product->save();

        $this->switchKeys[$id] = rand();
    }

    public function confirmToggleStatus($id)
    {
        $this->confirmingStatusId = $id;
        $this->isConfirmingStatusModalOpen = true;
    }

    public function updateStatus()
    {
        if ($this->confirmingStatusId) {
            $product = product::find($this->confirmingStatusId);

            if ($product) {
                $product->status = $product->status === 'active' ? 'inactive' : 'active';
                $product->save();

                // Trigger re-render for this specific toggle
                $this->switchKeys[$this->confirmingStatusId] = rand();
            }
        }

        $this->confirmingStatusId = null;
        $this->isConfirmingStatusModalOpen = false;
    }

    public function cancelToggle()
    {
        if ($this->confirmingStatusId) {
            $this->switchKeys[$this->confirmingStatusId] = rand();
        }

        $this->confirmingStatusId = null;
        $this->isConfirmingStatusModalOpen = false;
    }

    public function categories()
    {
        return Category::orderBy('name', 'ASC')->get();
    }
};
?>

<div>
    <div class="flex gap-4 mb-4">
        <div>
            <flux:input icon="magnifying-glass" placeholder="Cari produk..." class="sm:w-40 md:w-55 lg:w-60"
                wire:model.live="search" />
        </div>
        <div>
            <flux:select wire:model.live="categoryFilter" class="w-40">
                <flux:select.option value="">Pilih Kategori...</flux:select.option>
                @foreach ($this->categories() as $cate)
                    <flux:select.option value="{{ $cate->id }}">{{ ucwords(strtolower($cate->name)) }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>
    </div>
    <div
        class="rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden bg-white dark:bg-zinc-900 p-4 sm:p-6">
        <div class="overflow-x-auto w-full">
            <flux:table :paginate="$this->products()">
                <flux:table.columns>
                    <flux:table.column>#</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy = 'name'" :direction="$sortDirection"
                        wire:click="sort('name')">{{ __('Nama') }}</flux:table.column>
                    <flux:table.column>{{ __('Gambar') }}</flux:table.column>
                    <flux:table.column>{{ __('Kategori') }}</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy = 'price'" :direvtion="$sortDirection"
                        wire:click="sort('price')">{{ __('Harga') }}</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy = 'stock'" :direvtion="$sortDirection"
                        wire:click="sort('stock')">{{ __('Stok') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column class="flex justify-center">{{ __('Aksi') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->products() as $index => $row)
                        <flux:table.row key="{{ $row->id }}">
                            <flux:table.cell>
                                {{ $this->products()->firstItem() + $index }}
                            </flux:table.cell>
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ Str::ucwords(strtolower($row->name)) }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="w-10 h-10 md:w-15 md:h-15 overflow-hidden hover:cursor-pointer">
                                    <flux:modal.trigger name="show-foto-{{ $row->id }}">
                                        <img src="{{ asset('storage/' . $row->image) }}" class="aspect-square"
                                            alt="">
                                    </flux:modal.trigger>
                                </div>
                                {{-- modal gambar --}}
                                <flux:modal name="show-foto-{{ $row->id }}" class="sm:p-10 md:p-2">
                                    <div class="space-y-6">
                                        <div>
                                            <flux:heading size="lg">{{ Str::ucwords(strtolower($row->name)) }}</flux:heading>
                                        </div>
                                        <div class="flex p-2">
                                            <img src="{{ asset('storage/' . $row->image) }}"
                                                class="h-auto max-h-[75vh]" alt="">
                                        </div>
                                    </div>
                                </flux:modal>
                            </flux:table.cell>
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ ucwords(strtolower($row->category->name)) }}
                            </flux:table.cell>
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ 'Rp ' . $row->format_price }}
                            </flux:table.cell>
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $row->stock }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="relative inline-block">
                                    <flux:field>
                                        <flux:switch :checked="$row->status == 'active'"
                                            class="data-checked:bg-green-400! data-checked:border-green-400!"
                                            wire:key="switch-{{ $row->id }}-{{ $this->switchKeys[$row->id] ?? 0 }}" />
                                    </flux:field>
                                    <div wire:click="confirmToggleStatus({{ $row->id }})"
                                        class="absolute inset-0 cursor-pointer"></div>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell class="flex justify-end gap-2">
                                <flux:button href="{{ route('admin.product.edit', $row->id) }}" variant="filled"
                                    size="sm" icon="pencil-square"
                                    class="text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20" />

                                <flux:modal.trigger name="delete-user-modal-{{ $row->id }}">
                                    <flux:button variant="danger" size="sm" icon="trash" />
                                </flux:modal.trigger>

                                <flux:modal name="delete-user-modal-{{ $row->id }}" class="min-w-[400px]">
                                    <form action="{{ route('admin.product.destroy', $row->id) }}" method="POST"
                                        class="space-y-6">
                                        @csrf
                                        @method('DELETE')

                                        <div>
                                            <flux:heading size="xl">{{ __('Konfirmasi Hapus') }}</flux:heading>
                                            <flux:subheading>
                                                {{ __('Apakah Anda yakin? Kategori yang belum digunakan akan dihapus, sedangkan yang sudah digunakan hanya akan dinonaktifkan.') }}
                                            </flux:subheading>
                                        </div>

                                        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                                            <flux:modal.close>
                                                <flux:button variant="filled">{{ __('Batal') }}</flux:button>
                                            </flux:modal.close>

                                            <flux:button variant="danger" type="submit">
                                                {{ __('Hapus') }}
                                            </flux:button>
                                        </div>
                                    </form>
                                </flux:modal>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center text-zinc-500 py-6">
                                {{ __('Tidak ada data kategori.') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </div>

    <flux:modal name="confirm-status-modal" class="min-w-[400px]" wire:model="isConfirmingStatusModalOpen">

        <div class="space-y-6">

            <div>
                <flux:heading size="xl">
                    Konfirmasi Perubahan Status
                </flux:heading>

                <flux:subheading>
                    Apakah Anda yakin ingin mengubah status produk ini?
                </flux:subheading>
            </div>

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button variant="filled" wire:click="cancelToggle">
                        Batal
                    </flux:button>
                </flux:modal.close>

                <flux:button variant="danger" wire:click="updateStatus">
                    Ya, Ubah Status
                </flux:button>

            </div>

        </div>

    </flux:modal>
</div>
