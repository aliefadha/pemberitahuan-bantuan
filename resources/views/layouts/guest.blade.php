<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pemberitahuan Bantuan') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-b from-amber-50/30 via-white to-amber-50/10 text-slate-800 min-h-screen antialiased selection:bg-amber-100 selection:text-amber-900 relative overflow-x-hidden">
    @php
        $containerWidth = match ($maxWidth ?? 'md') {
            '2xl' => 'max-w-2xl',
            'xl' => 'max-w-xl',
            'lg' => 'max-w-lg',
            default => 'max-w-md',
        };
    @endphp

    <!-- Decorative Top Accent -->
    <div class="absolute top-0 inset-x-0 h-1.5 bg-amber-200"></div>

    <!-- Background Blobs -->
    <div class="absolute top-24 left-1/4 w-96 h-96 bg-amber-100/20 rounded-full blur-3xl -z-10 pointer-events-none"></div>
    <div class="absolute top-48 right-1/4 w-80 h-80 bg-amber-100/10 rounded-full blur-3xl -z-10 pointer-events-none"></div>

    <div class="min-h-screen flex flex-col items-center justify-center px-3 py-8 sm:px-4 sm:py-12">
        <div @class(['w-full', $containerWidth])>

            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-12 h-12 rounded-xl bg-amber-100/80 border border-amber-200/50 flex items-center justify-center shadow-sm mb-3">
                    <svg class="w-7 h-7 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900">DASAWISMA</span>
            </div>

            <!-- Card Box -->
            <div class="bg-white rounded-2xl sm:rounded-[2rem] shadow-xl border border-amber-100/80 p-4 sm:p-8 lg:p-10 relative overflow-hidden">
                <div class="text-center mb-6">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">{{ $title ?? '' }}</h1>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
