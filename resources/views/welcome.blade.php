<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dasawisma Padang Rantang - Aplikasi Pengelolaan Data & Broadcast</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-b from-amber-50/30 via-white to-amber-50/10 text-slate-800 min-h-screen antialiased selection:bg-amber-100 selection:text-amber-900">

    <!-- Decorative Top Accent -->
    <div class="absolute top-0 inset-x-0 h-1.5 bg-amber-200"></div>

    <!-- Hero Background Blobs -->
    <div class="absolute top-24 left-1/4 w-96 h-96 bg-amber-100/20 rounded-full blur-3xl -z-10 pointer-events-none"></div>
    <div class="absolute top-48 right-1/4 w-80 h-80 bg-amber-100/10 rounded-full blur-3xl -z-10 pointer-events-none"></div>

    <!-- Navigation Header -->
    <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4">
        <nav class="flex items-center justify-between bg-white/70 backdrop-blur-md px-6 py-4 rounded-2xl border border-amber-100/30 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100/80 border border-amber-200/50 flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">DASAWISMA</span>
                    <span class="block text-xs font-semibold text-amber-600/80 tracking-wider uppercase -mt-1">Padang Rantang</span>
                </div>
            </div>

            <div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold rounded-xl text-amber-900 bg-amber-100/80 hover:bg-amber-200 border border-amber-200/60 shadow-sm hover:scale-[1.01] active:scale-[0.99] transition duration-200">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold rounded-xl text-amber-900 bg-amber-100/80 hover:bg-amber-200 border border-amber-200/60 shadow-sm hover:scale-[1.01] active:scale-[0.99] transition duration-200">
                            Login Member
                        </a>
                    @endauth
                @endif
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-16">

        <!-- Hero Section -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-20">
            <!-- Left Side: Hero Text -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    Sistem Informasi Dasawisma
                </span>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-none">
                    Aplikasi Pengelolaan Data <br class="hidden sm:inline" />
                    <span class="text-amber-600">Dasawisma</span> Berbasis Web
                </h1>

                <p class="text-lg text-slate-600 font-medium max-w-xl mx-auto lg:mx-0">
                    Memudahkan pengelolaan data anggota keluarga dan penyebaran informasi secara cepat menggunakan fitur WhatsApp Broadcast Message.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-2xl text-amber-900 bg-amber-100/90 hover:bg-amber-200/95 border border-amber-200/60 shadow-md shadow-amber-100/40 hover:-translate-y-0.5 transition duration-200">
                                Masuk Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-2xl text-amber-900 bg-amber-100/90 hover:bg-amber-200/95 border border-amber-200/60 shadow-md shadow-amber-100/40 hover:-translate-y-0.5 transition duration-200">
                                Login Ke Aplikasi
                            </a>
                        @endauth
                    @endif
                    <a href="#tentang" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-2xl text-slate-700 bg-slate-50 border border-slate-200/60 hover:bg-slate-100 hover:-translate-y-0.5 transition duration-200">
                        Lihat Informasi
                    </a>
                </div>
            </div>

            <!-- Right Side: Hero Image -->
            <div class="lg:col-span-5 relative">
                <div class="absolute inset-0 bg-amber-100/40 rounded-[2.5rem] blur-2xl transform rotate-3 -z-10 scale-95 pointer-events-none"></div>
                <img src="{{ asset('images/dasawisma_hero.jpeg') }}" alt="Aplikasi Dasawisma" class="w-full max-w-lg mx-auto rounded-[2rem] shadow-2xl border-8 border-white transform hover:scale-[1.01] transition duration-500 ease-out">
            </div>
        </section>

        <!-- Stats Section -->
        <section class="mb-24">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Card 1: Data Anggota -->
                <div class="group relative bg-white/80 hover:bg-white p-6 rounded-3xl border border-amber-100 shadow-sm hover:shadow-xl hover:shadow-amber-100/30 hover:-translate-y-1 transition duration-300">
                    <div class="absolute top-4 right-4 w-12 h-12 rounded-2xl bg-amber-50 group-hover:bg-amber-100/80 flex items-center justify-center text-2xl transition duration-300">
                        👥
                    </div>
                    <span class="block text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-1">
                        {{ number_format($totalAnggota) }}
                    </span>
                    <span class="block font-bold text-slate-900 text-sm">Data Anggota</span>
                    <span class="block text-xs text-slate-500 mt-1">Total warga terdaftar</span>
                </div>

                <!-- Card 2: Data Keluarga -->
                <div class="group relative bg-white/80 hover:bg-white p-6 rounded-3xl border border-amber-100 shadow-sm hover:shadow-xl hover:shadow-amber-100/30 hover:-translate-y-1 transition duration-300">
                    <div class="absolute top-4 right-4 w-12 h-12 rounded-2xl bg-amber-50 group-hover:bg-amber-100/80 flex items-center justify-center text-2xl transition duration-300">
                        🏠
                    </div>
                    <span class="block text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-1">
                        {{ number_format($totalKeluarga) }}
                    </span>
                    <span class="block font-bold text-slate-900 text-sm">Data Keluarga</span>
                    <span class="block text-xs text-slate-500 mt-1">Kepala Keluarga / KK</span>
                </div>

                <!-- Card 3: Broadcast Message -->
                <div class="group relative bg-white/80 hover:bg-white p-6 rounded-3xl border border-amber-100 shadow-sm hover:shadow-xl hover:shadow-amber-100/30 hover:-translate-y-1 transition duration-300">
                    <div class="absolute top-4 right-4 w-12 h-12 rounded-2xl bg-amber-50 group-hover:bg-amber-100/80 flex items-center justify-center text-2xl transition duration-300">
                        📢
                    </div>
                    <span class="block text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-1">
                        {{ number_format($totalBroadcast) }}
                    </span>
                    <span class="block font-bold text-slate-900 text-sm">Broadcast Message</span>
                    <span class="block text-xs text-slate-500 mt-1">Pesan disebarkan</span>
                </div>

                <!-- Card 4: Laporan -->
                <div class="group relative bg-white/80 hover:bg-white p-6 rounded-3xl border border-amber-100 shadow-sm hover:shadow-xl hover:shadow-amber-100/30 hover:-translate-y-1 transition duration-300">
                    <div class="absolute top-4 right-4 w-12 h-12 rounded-2xl bg-amber-50 group-hover:bg-amber-100/80 flex items-center justify-center text-2xl transition duration-300">
                        📊
                    </div>
                    <span class="block text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-1">
                        {{ number_format($totalKelompok) }}
                    </span>
                    <span class="block font-bold text-slate-900 text-sm">Laporan</span>
                    <span class="block text-xs text-slate-500 mt-1">Kelompok Dasawisma</span>
                </div>
            </div>
        </section>

        <!-- About & Features Section -->
        <section id="tentang" class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-stretch mb-16 scroll-mt-24">

            <!-- About Section -->
            <div class="lg:col-span-7 flex flex-col justify-between bg-white p-8 sm:p-10 rounded-[2rem] border border-amber-100/80 shadow-sm shadow-amber-100/20">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-amber-50 text-amber-800 border border-amber-200/30 text-xs font-extrabold uppercase tracking-widest">
                        ℹ️ Tentang Aplikasi
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        TENTANG APLIKASI
                    </h2>

                    <p class="text-lg text-slate-600 leading-relaxed font-medium">
                        Aplikasi ini digunakan untuk mengelola data anggota Dasawisma, data keluarga, kegiatan Dasawisma, serta mengirim informasi kepada seluruh anggota melalui fitur Broadcast Message secara cepat dan efisien.
                    </p>
                </div>

                <div class="pt-8 mt-8 border-t border-slate-100 flex items-center gap-4 text-slate-500 text-sm font-semibold">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Data Terenkripsi & Pengiriman Real-Time
                </div>
            </div>

            <!-- Features Section -->
            <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 to-slate-800 text-white p-8 sm:p-10 rounded-[2rem] shadow-xl relative overflow-hidden flex flex-col justify-between">
                <!-- Decorative background light pattern -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-100/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="space-y-6 relative z-10">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white/10 text-amber-200 border border-white/10 text-xs font-extrabold uppercase tracking-widest">
                        🌟 Fitur Utama
                    </div>

                    <h2 class="text-3xl font-extrabold tracking-tight">
                        FITUR UNGGULAN
                    </h2>

                    <!-- Features List -->
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-amber-200/80 text-amber-950 flex items-center justify-center text-xs font-bold">
                                ✓
                            </div>
                            <span class="text-slate-200 font-semibold text-sm sm:text-base">Pengelolaan Data Anggota</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-amber-200/80 text-amber-950 flex items-center justify-center text-xs font-bold">
                                ✓
                            </div>
                            <span class="text-slate-200 font-semibold text-sm sm:text-base">Pengelolaan Data Keluarga</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-amber-200/80 text-amber-950 flex items-center justify-center text-xs font-bold">
                                ✓
                            </div>
                            <span class="text-slate-200 font-semibold text-sm sm:text-base">Broadcast Message ke Seluruh Anggota</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-amber-200/80 text-amber-950 flex items-center justify-center text-xs font-bold">
                                ✓
                            </div>
                            <span class="text-slate-200 font-semibold text-sm sm:text-base">Laporan dan Rekapitulasi Data</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-amber-200/80 text-amber-950 flex items-center justify-center text-xs font-bold">
                                ✓
                            </div>
                            <span class="text-slate-200 font-semibold text-sm sm:text-base">Cetak Laporan PDF</span>
                        </li>
                    </ul>
                </div>
            </div>

        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 text-slate-400 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
            <div class="space-y-2">
                <div class="flex items-center justify-center md:justify-start gap-2.5">
                    <div class="w-6 h-6 rounded-md bg-amber-100 border border-amber-300/40 flex items-center justify-center text-amber-800 text-xs font-bold">
                        D
                    </div>
                    <span class="text-white font-extrabold tracking-tight">DASAWISMA</span>
                </div>
                <p class="text-xs text-slate-500">
                    Aplikasi Pengelolaan Data Dasawisma Berbasis Web
                </p>
            </div>

            <div class="text-sm">
                <span class="block text-xs text-slate-500 mt-1">© 2026 Hak Cipta Dilindungi Undang-Undang</span>
            </div>
        </div>
    </footer>

</body>
</html>
