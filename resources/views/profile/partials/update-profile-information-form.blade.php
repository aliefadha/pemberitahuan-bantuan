<form method="post" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf
    @method('patch')

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
        <input type="text" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('email') border-red-500 @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-gray-800">
                    Your email address is unverified.
                    <button form="send-verification" class="text-blue-600 hover:text-blue-800 underline">Click here to re-send the verification email.</button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm text-green-600">A new verification link has been sent to your email address.</p>
                @endif
            </div>
        @endif
    </div>

    <div>
        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
        <input type="text" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('no_telepon') border-red-500 @enderror" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" placeholder="08xxxxxxxxx" autocomplete="tel">
        @error('no_telepon')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-sm text-gray-500">Contoh: 081234567890</p>
    </div>

    <div>
        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
        <textarea class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('alamat') border-red-500 @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
        @error('alamat')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
            Save
        </button>
        @if (session('status') === 'profile-updated')
            <span class="text-sm text-green-600">Saved.</span>
        @endif
    </div>
</form>

<form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
    @csrf
</form>