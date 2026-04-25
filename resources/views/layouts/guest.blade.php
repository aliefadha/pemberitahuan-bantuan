<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pemberitahuan Dosen') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased  min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-xl">
            <div class="bg-white rounded-xl shadow-2xl p-8 border border-gray-200">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $title ?? '' }}</h1>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
