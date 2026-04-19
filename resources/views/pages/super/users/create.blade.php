<x-layouts::app :title="__('Daftar Pengguna')">
    <div class="flex flex-col gap-6 w-full">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Tambah User') }}</flux:heading>
            </div>
        </div>

        <flux:separator variant="subtle" />

        <form action="{{ route('super.users.store') }}" method="POST" class="flex flex-col gap-6 w-full max-w-4xl mt-2">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <flux:input name="name" :label="__('Nama Lengkap')" type="text" autofocus
                    :placeholder="__('Masukkan nama lengkap')" value="{{ old('name') }}" />

                <flux:input name="email" :label="__('Alamat Email')" type="email"
                    :placeholder="__('email@contoh.com')" value="{{ old('email') }}" />

                <!-- Role -->
                <flux:select name="role" :label="__('Role')" :placeholder="__('Pilih Role')">
                    <flux:select.option value="super" :selected="old('role') == 'super'">{{ __('Super Admin') }}
                    </flux:select.option>
                    <flux:select.option value="admin" :selected="old('role') == 'admin'">{{ __('Admin') }}
                    </flux:select.option>
                    <flux:select.option value="kasir" :selected="old('role') == 'kasir'">{{ __('Kasir') }}
                    </flux:select.option>
                </flux:select>

                <!-- Status -->
                <flux:input type="number" name="no_telp" :label="__('No. telp')" value="{{ old('no_telp') }}" :placeholder="__('08XXXX')" viewable>
                </flux:input>

                <flux:input name="password" :label="__('Kata Sandi')" type="password"
                    :placeholder="__('Masukkan kata sandi')" viewable />

                <!-- Confirm Password -->
                <flux:input name="password_confirmation" :label="__('Konfirmasi Kata Sandi')" type="password"
                    :placeholder="__('Ulangi kata sandi')" viewable />
            </div>
            <flux:textarea :label="__('Alamat')" name="address" placeholder="Masukan alamat lengkap" viewable >{{ old('address') }}</flux:textarea>
            @error('address')
                <div class="text-red-400 text-sm">{{ $message }}</div>
            @enderror


            <div class="flex items-center justify-end gap-3 mt-4">
                <flux:button :href="route('super.users.index')" wire:navigate variant="ghost">{{ __('Batal') }}
                </flux:button>
                <flux:button type="submit" variant="primary" class="bg-blue-600 hover:bg-blue-700 text-white">
                    {{ __('Simpan Pengguna') }}</flux:button>
            </div>
        </form>

    </div>
</x-layouts::app>
