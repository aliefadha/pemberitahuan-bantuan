<x-guest-layout>
    <x-slot name="title">Konfirmasi Kehadiran</x-slot>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-3 text-sm font-medium text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-5">
        <div>
            <p class="text-sm text-slate-500">Halo, {{ $recipient->name }}</p>
            <h2 class="mt-1 text-xl font-bold text-slate-900">{{ $kegiatan->judul }}</h2>
            @if($kegiatan->jorong_label)
                <p class="mt-1 text-sm font-medium text-amber-700">{{ $kegiatan->jorong_label }}</p>
            @endif
        </div>

        <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-700">
            <p class="font-semibold text-slate-900">{{ $kegiatan->tanggal->format('d/m/Y H:i') }}</p>
            @if($kegiatan->deskripsi)
                <p class="mt-3 whitespace-pre-wrap">{{ $kegiatan->deskripsi }}</p>
            @endif
        </div>

        @if($status)
            <div class="rounded-xl border p-3 text-sm {{ $status === 'bersedia' ? 'border-green-200 bg-green-50 text-green-800' : 'border-red-200 bg-red-50 text-red-800' }}">
                Status Anda: <strong>{{ $status === 'bersedia' ? 'Bersedia hadir' : 'Tidak bersedia' }}</strong>
                @if($alasan)
                    <p class="mt-1">Alasan: {{ $alasan }}</p>
                @endif
            </div>
        @endif

        <p class="text-sm text-slate-600">Silakan konfirmasi kehadiran Anda. Tanggapan masih dapat diubah selama tautan berlaku.</p>

        <form method="POST" action="{{ $respondUrl }}" class="space-y-3">
            @csrf
            <button type="submit" name="status" value="bersedia"
                class="w-full rounded-xl bg-green-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-green-700">
                Bersedia Hadir
            </button>

            <div class="rounded-xl border border-slate-200 p-3">
                <label for="alasan" class="block text-sm font-medium text-slate-700">Jika tidak bersedia, tuliskan alasan</label>
                <textarea id="alasan" name="alasan" rows="3" maxlength="1000"
                    class="mt-2 block w-full rounded-lg border-slate-300 text-sm focus:border-red-500 focus:ring-red-500"
                    placeholder="Alasan tidak dapat hadir">{{ old('alasan', $status === 'tidak_bersedia' ? $alasan : '') }}</textarea>
                @error('alasan')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit" name="status" value="tidak_bersedia"
                    class="mt-3 w-full rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                    Tidak Bersedia
                </button>
            </div>
        </form>

        <p class="text-center text-xs text-slate-400">Tautan ini khusus untuk {{ $recipient->name }}. Jangan teruskan kepada orang lain.</p>
    </div>
</x-guest-layout>
