<x-guest-layout title="Selamat Datang!">
    <x-auth-session-status class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('email') border-red-500 @enderror" id="exampleInputEmail" placeholder="Masukkan Alamat Email..." name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="exampleInputPassword" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
                <input type="password" class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 pl-2 pr-10 focus:border-gray-500 focus:ring-gray-500 @error('password') border-red-500 @enderror" id="exampleInputPassword" placeholder="Password" name="password" required autocomplete="current-password">
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" onclick="const input = document.getElementById('exampleInputPassword'); const type = input.getAttribute('type') === 'password' ? 'text' : 'password'; input.setAttribute('type', type); this.innerHTML = type === 'password' ? '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\' /><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\' /></svg>' : '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\' /></svg>';">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-500" id="customCheck" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="ml-2 text-sm text-gray-700" for="customCheck">Ingat Saya</label>
        </div>

        <button type="submit" class="w-full px-4 py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
            Masuk
        </button>
    </form>

    <hr class="my-6">

    <div class="text-center space-y-2">
        @if (Route::has('password.request'))
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">Lupa Password?</a>
        @endif
        @if (Route::has('register'))
            <div>
                <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">Belum punya akun? Daftar!</a>
            </div>
        @endif
    </div>
</x-guest-layout>
