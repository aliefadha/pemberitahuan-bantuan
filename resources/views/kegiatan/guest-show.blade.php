<x-guest-layout title="Konfirmasi Kehadiran" max-width="2xl">

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-3 text-sm font-medium text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid min-w-0 gap-6 md:grid-cols-2 md:items-start">
        <section class="min-w-0 space-y-4" aria-labelledby="event-title">
            <div>
                <p class="break-words text-sm text-slate-500">Halo, {{ $recipient->name }}</p>
                <h2 id="event-title" class="mt-1 break-words text-xl font-bold leading-snug text-slate-900 sm:text-2xl">
                    {{ $kegiatan->judul }}
                </h2>
                @if($kegiatan->jorong_label)
                    <span class="mt-2 inline-flex max-w-full rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                        <span class="truncate">{{ $kegiatan->jorong_label }}</span>
                    </span>
                @endif
            </div>

            <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-700 ring-1 ring-slate-100 sm:p-5">
                <div class="flex items-start gap-2 font-semibold text-slate-900">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <time datetime="{{ $kegiatan->tanggal->toIso8601String() }}" class="break-words">
                        {{ $kegiatan->tanggal->format('d/m/Y H:i') }} WIB
                    </time>
                </div>
                @if($kegiatan->deskripsi)
                    <p class="mt-3 break-words whitespace-pre-wrap leading-relaxed">{{ $kegiatan->deskripsi }}</p>
                @endif
            </div>

            <div class="rounded-xl border border-amber-100 bg-amber-50/60 p-3 text-xs leading-relaxed text-amber-900 sm:text-sm">
                Tautan ini khusus untuk <strong class="break-words">{{ $recipient->name }}</strong>. Jangan teruskan kepada orang lain.
            </div>
        </section>

        <section class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5" aria-labelledby="response-title">
            <h3 id="response-title" class="text-base font-bold text-slate-900 sm:text-lg">Tanggapan Anda</h3>

            @if($status)
                <div class="mt-3 break-words rounded-xl border p-3 text-sm {{ $status === 'bersedia' ? 'border-green-200 bg-green-50 text-green-800' : 'border-red-200 bg-red-50 text-red-800' }}">
                    Status: <strong>{{ $status === 'bersedia' ? 'Bersedia hadir' : 'Tidak bersedia' }}</strong>
                    @if($alasan)
                        <p class="mt-1 leading-relaxed">Alasan: {{ $alasan }}</p>
                    @endif
                </div>
            @endif

            <p class="mt-3 text-sm leading-relaxed text-slate-600">
                Silakan konfirmasi kehadiran Anda. Tanggapan masih dapat diubah selama tautan berlaku.
            </p>

            <form method="POST" action="{{ $respondUrl }}" class="mt-4 space-y-3">
                @csrf
                <button type="submit" name="status" value="bersedia"
                    class="flex min-h-12 w-full items-center justify-center rounded-xl bg-green-600 px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-100">
                    Bersedia Hadir
                </button>

                <div class="rounded-xl border border-slate-200 p-3 sm:p-4">
                    <label for="alasan" class="block text-sm font-medium leading-snug text-slate-700">Jika tidak bersedia, tuliskan alasan</label>
                    <textarea id="alasan" name="alasan" rows="3" maxlength="1000"
                        class="mt-2 block w-full resize-y rounded-lg border-slate-300 text-base sm:text-sm focus:border-red-500 focus:ring-red-500"
                        placeholder="Alasan tidak dapat hadir">{{ old('alasan', $status === 'tidak_bersedia' ? $alasan : '') }}</textarea>
                    @error('alasan')
                        <p class="mt-1 break-words text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <button type="submit" name="status" value="tidak_bersedia"
                        class="mt-3 flex min-h-12 w-full items-center justify-center rounded-xl bg-red-600 px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-100">
                        Tidak Bersedia
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-guest-layout>
