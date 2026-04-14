<?php

use Livewire\Component;
use App\Models\User;

new class extends Component {
    use \Livewire\WithPagination;

    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $search;

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function users()
    {
        $query = User::query()->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query);

        $keyword = $this->search;

        if ($keyword) {
            $query = User::query()->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)->where('name', 'like', '%' . $keyword . '%')->orWhere('email', 'like', '%' . $keyword . '%');
        }

        $users = $query->where('id', '!=', auth()->user()->id)->paginate(10);
        return $users;
    }
};
?>

<div>
    <div class="flex justify-between mb-4">
        <div>
            <flux:input icon="magnifying-glass" placeholder="Cari nama atau email" class="w-55 sm:w-50 lg:w-60" wire:model.live="search"/>
        </div>
        <flux:button href="{{ route('super.users.create') }}" class="bg-blue-600 text-white hover:bg-blue-700" icon="plus" variant="primary">
                {{ __('Tambah') }}
            </flux:button>
    </div>
    <div
        class="rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden bg-white dark:bg-zinc-900 p-4 sm:p-6">
        <div class="overflow-x-auto w-full">
            <flux:table :paginate="$this->users()">
                <flux:table.columns>
                    <flux:table.column>#</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy = 'name'" :direction="$sortDirection" wire:click="sort('name')">{{ __('Nama') }}</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy = 'email'" :direction="$sortDirection" wire:click="sort('email')">{{ __('Email') }}</flux:table.column>
                    <flux:table.column>{{ __('Hak Akses') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column>{{ __('Aksi') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->users() as $index => $user)
                        <flux:table.row key="{{ $user->id }}">
                            <flux:table.cell>
                                {{ $this->users()->firstItem() + $index }}
                            </flux:table.cell>
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $user->name }}
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-500 dark:text-zinc-400">
                                {{ $user->email }}
                            </flux:table.cell>
                            <flux:table.cell>

                                <flux:badge size="sm" :color="$user->role_color" inset="top bottom">{{ $user->role ?? 'User' }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm" :color="$user->status_color" inset="top bottom">{{ $user->status ?? '' }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="flex justify-center gap-2">
                                <flux:button href="{{ route('super.users.edit', $user->id) }}" variant="filled"
                                    size="sm" icon="pencil-square"
                                    class="text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20" />

                                <flux:modal.trigger name="delete-user-modal-{{ $user->id }}">
                                    <flux:button variant="danger" size="sm" icon="trash" />
                                </flux:modal.trigger>

                                <flux:modal name="delete-user-modal-{{ $user->id }}" class="min-w-[400px]">
                                    <form action="{{ route('super.users.destroy', $user->id) }}" method="POST"
                                        class="space-y-6">
                                        @csrf
                                        @method('DELETE')

                                        <div>
                                            <flux:heading size="lg">{{ __('Konfirmasi Hapus') }}</flux:heading>
                                            <flux:subheading>
                                                {{ __('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.') }}
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
                                {{ __('Tidak ada data pengguna.') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</div>
