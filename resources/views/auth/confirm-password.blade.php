<x-guest-layout title="Confirm Password">
    <div class="mb-4 text-sm text-gray-600">
        This is a secure area of the application. Please confirm your password before continuing.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="user">
        @csrf

        <div class="form-group">
            <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror"
                id="exampleInputPassword" placeholder="Password" name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-user btn-block">
            Confirm
        </button>
    </form>
</x-guest-layout>
