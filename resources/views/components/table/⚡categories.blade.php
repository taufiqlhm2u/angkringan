<?php

use Livewire\Component;
use App\Models\Category;

new class extends Component {
    use \Livewire\WithPagination;

    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $search;
    public $confirmingStatusId = null;
    public $isConfirmingStatusModalOpen = false;
    public $switchKeys = [];

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function Categories()
    {
        $query = Category::query()->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query);

        $keyword = $this->search;

        if ($keyword) {
            $query = Category::whereRaw('Lower(name) LIKE ?', ['%' . strtolower($keyword) . '%']);
        }

        $categories = $query->paginate(10);
        return $categories;
    }

    // update status
    public function toggleStatus($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return;
        }

        $category->status = $category->status === 'active' ? 'inactive' : 'active';

        $category->save();

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
            $category = Category::find($this->confirmingStatusId);

            if ($category) {
                $category->status = $category->status === 'active' ? 'inactive' : 'active';
                $category->save();

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
};
?>

<div>
    <div class="flex gap-4 mb-4">
        <div>
            <flux:input icon="magnifying-glass" placeholder="Cari kategori" class="w-55 sm:w-50 lg:w-60"
                wire:model.live="search" />
        </div>
    </div>
    <div
        class="rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden bg-white dark:bg-zinc-900 p-4 sm:p-6">
        <div class="overflow-x-auto w-full">
            <flux:table :paginate="$this->categories()">
                <flux:table.columns>
                    <flux:table.column>#</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy = 'name'" :direction="$sortDirection"
                        wire:click="sort('name')">{{ __('Nama') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column class="flex justify-center">{{ __('Aksi') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->categories() as $index => $cate)
                        <flux:table.row key="{{ $cate->id }}">
                            <flux:table.cell>
                                {{ $this->categories()->firstItem() + $index }}
                            </flux:table.cell>
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ Str::ucwords(strtolower($cate->name)) }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="relative inline-block">
                                    <flux:field>
                                        <flux:switch :checked="$cate->status == 'active'"
                                            class="data-checked:bg-green-400! data-checked:border-green-400!"
                                            wire:key="switch-{{ $cate->id }}-{{ $this->switchKeys[$cate->id] ?? 0 }}" />
                                    </flux:field>
                                    <div wire:click="confirmToggleStatus({{ $cate->id }})"
                                        class="absolute inset-0 cursor-pointer"></div>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell class="flex justify-end gap-2">
                                <flux:button href="{{ route('admin.category.edit', $cate->id) }}" variant="filled"
                                    size="sm" icon="pencil-square"
                                    class="text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20" />

                                <flux:modal.trigger name="delete-user-modal-{{ $cate->id }}">
                                    <flux:button variant="danger" size="sm" icon="trash" />
                                </flux:modal.trigger>

                                <flux:modal name="delete-user-modal-{{ $cate->id }}" class="min-w-[400px]">
                                    <form action="{{ route('admin.category.destroy', $cate->id) }}" method="POST"
                                        class="space-y-6">
                                        @csrf
                                        @method('DELETE')

                                        <div>
                                            <flux:heading size="xl">{{ __('Konfirmasi Hapus') }}</flux:heading>
                                            <flux:subheading>
                                                {{ __('Apakah Anda yakin?
                                                Kategori yang belum digunakan akan dihapus, sedangkan yang sudah digunakan hanya akan dinonaktifkan.') }}
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
                    Apakah Anda yakin ingin mengubah status kategori ini?
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
