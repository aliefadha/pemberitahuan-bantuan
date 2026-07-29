<x-guest-layout title="Selamat Datang!">
    <x-auth-session-status class="mb-4 p-3 bg-amber-50 border border-amber-200/50 text-amber-800 rounded-lg text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
            <input type="email" class="w-full rounded-xl border-slate-200 shadow-sm py-2 px-3 focus:border-amber-400 focus:ring-amber-400 focus:outline-none transition @error('email') border-red-500 @enderror" id="exampleInputEmail" placeholder="Masukkan Alamat Email..." name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="exampleInputPassword" class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
            <div class="relative">
                <input type="password" class="w-full rounded-xl border-slate-200 shadow-sm py-2 pl-3 pr-10 focus:border-amber-400 focus:ring-amber-400 focus:outline-none transition @error('password') border-red-500 @enderror" id="exampleInputPassword" placeholder="Password" name="password" required autocomplete="current-password">
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" onclick="const input = document.getElementById('exampleInputPassword'); const type = input.getAttribute('type') === 'password' ? 'text' : 'password'; input.setAttribute('type', type); this.innerHTML = type === 'password' ? '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\' /><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\' /></svg>' : '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\' /></svg>';">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500 transition" id="customCheck" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="ml-2 text-sm font-semibold text-slate-600" for="customCheck">Ingat Saya</label>
        </div>

        <div>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            @error('g-recaptcha-response')
                <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full px-4 py-3 bg-amber-100 hover:bg-amber-200 border border-amber-200/60 text-amber-900 font-bold rounded-xl shadow-md shadow-amber-100/40 hover:-translate-y-0.5 active:translate-y-0 transition duration-150">
            Masuk
        </button>
    </form>

    <hr class="my-6 border-slate-100">

    <div class="text-center space-y-2">
        @if (Route::has('password.request'))
            <a class="text-sm text-amber-700 hover:text-amber-900 font-semibold hover:underline" href="{{ route('password.request') }}">Lupa Password?</a>
        @endif
    </div>
</x-guest-layout>
