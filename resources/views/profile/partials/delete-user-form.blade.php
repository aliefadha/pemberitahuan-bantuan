<div class="space-y-4">
    <p class="text-gray-600">
        Setelah akun Anda dihapus, semua data akan dihapus secara permanen.
    </p>

    <button type="button" onclick="document.getElementById('deleteModal').classList.remove('hidden')" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
        Hapus Akun
    </button>
</div>

<div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="document.getElementById('deleteModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Hapus Account?</h3>
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-3">Masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.</p>
                    <input type="password" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('password', 'userDeletion') border-red-500 @enderror" id="password" name="password" placeholder="Password" autocomplete="current-password">
                    @error('password', 'userDeletion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                        Hapus Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
