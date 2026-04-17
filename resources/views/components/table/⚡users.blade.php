<?php

use Livewire\Component;
use App\Models\User;

new class extends Component {
    use \Livewire\WithPagination;

    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $search;
    public $role;
    public $confirmingStatusUserId = null;
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

    public function users()
    {
        $query = User::query()->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query);

        $keyword = $this->search;

        if ($keyword) {
            $query = User::query()
                ->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
                ->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
        }

        $role = $this->role;
        if ($role) {
            $query = $query->where('role', $role);
        }

        $users = $query->where('id', '!=', auth()->user()->id)->paginate(10);
        return $users;
    }

    // update status
    public function toggleStatus($id)
    {
        $user = User::find($id);

        if (!$user) {
            return;
        }

        if ($user->id == auth()->id()) {
            return; // optional: biar gak bisa ubah status sendiri
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';

        $user->save();

        $this->switchKeys[$id] = rand();
    }

    public function confirmToggleStatus($id)
    {
        $this->confirmingStatusUserId = $id;
        $this->isConfirmingStatusModalOpen = true;
    }

    public function updateStatus()
    {
        if ($this->confirmingStatusUserId) {
            $user = User::find($this->confirmingStatusUserId);

            if ($user && $user->id != auth()->id()) {
                $user->status = $user->status === 'active' ? 'inactive' : 'active';
                $user->save();
                
                // Trigger re-render for this specific toggle
                $this->switchKeys[$this->confirmingStatusUserId] = rand();
            }
        }

        $this->confirmingStatusUserId = null;
        $this->isConfirmingStatusModalOpen = false;
    }

    public function cancelToggle()
    {
        if ($this->confirmingStatusUserId) {
            $this->switchKeys[$this->confirmingStatusUserId] = rand();
        }

        $this->confirmingStatusUserId = null;
        $this->isConfirmingStatusModalOpen = false;
    }
};
?>

<div>
    <div class="flex gap-4 mb-4">
        <div>
            <flux:input icon="magnifying-glass" placeholder="Cari nama atau email" class="w-55 sm:w-50 lg:w-60"
                wire:model.live="search" />
        </div>
        <div>
            <flux:select wire:model.live="role">
                <flux:select.option value="">Pilih Role...</flux:select.option>
                <flux:select.option value="admin">Admin</flux:select.option>
                <flux:select.option value="kasir">Kasir</flux:select.option>
            </flux:select>
        </div>
    </div>
    <div
        class="rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden bg-white dark:bg-zinc-900 p-4 sm:p-6">
        <div class="overflow-x-auto w-full">
            <flux:table :paginate="$this->users()">
                <flux:table.columns>
                    <flux:table.column>#</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy = 'name'" :direction="$sortDirection"
                        wire:click="sort('name')">{{ __('Nama') }}</flux:table.column>
                    <flux:table.column sortable :sorted="$sortBy = 'email'" :direction="$sortDirection"
                        wire:click="sort('email')">{{ __('Email') }}</flux:table.column>
                    <flux:table.column>{{ __('No. Telp') }}</flux:table.column>
                    <flux:table.column>{{ __('Alamat') }}</flux:table.column>
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
                            <flux:table.cell class="text-zinc-500 dark:text-zinc-400">
                                {{ $user->phone_number }}
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-500 dark:text-zinc-400">
                                {{ $user->address }}
                            </flux:table.cell>
                            <flux:table.cell>

                                <flux:badge size="sm" :color="$user->role_color" inset="top bottom">
                                    {{ $user->role ?? 'User' }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="relative inline-block">
                                    <flux:field>
                                        <flux:switch :checked="$user->status == 'active'" class="bg-green-500"
                                            wire:key="switch-{{ $user->id }}-{{ $this->switchKeys[$user->id] ?? 0 }}" />
                                    </flux:field>
                                    <div wire:click="confirmToggleStatus({{ $user->id }})" class="absolute inset-0 cursor-pointer"></div>
                                </div>
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

    <flux:modal name="confirm-status-modal" class="min-w-[400px]" wire:model="isConfirmingStatusModalOpen">

        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Konfirmasi Perubahan Status
                </flux:heading>

                <flux:subheading>
                    Apakah Anda yakin ingin mengubah status user ini?
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
