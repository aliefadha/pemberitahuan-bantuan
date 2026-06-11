<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('Tambah User') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Form Tambah User</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Kembali</a>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('email') border-red-500 @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">No. HP (WhatsApp)</label>
                        <input type="text" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('no_telepon') border-red-500 @enderror" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="08xxxxxxxxx">
                        @error('no_telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Contoh: 081234567890</p>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('role') border-red-500 @enderror" id="role" name="role" required>
                            <option value="" disabled selected>-- Pilih Role --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="peserta" {{ old('role') == 'peserta' ? 'selected' : '' }}>Peserta</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('password') border-red-500 @enderror" id="password" name="password" required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input type="password" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    @include('partials.anggota-keluarga-form')

                    <div class="pt-4 flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>