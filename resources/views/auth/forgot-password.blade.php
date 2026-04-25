<x-guest-layout title="Forgot Password">
    <div class="mb-4 text-sm text-gray-600">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="user">
        @csrf

        <div class="form-group">
            <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                id="exampleInputEmail" aria-describedby="emailHelp"
                placeholder="Enter Email Address..." name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-user btn-block">
            Email Password Reset Link
        </button>
    </form>
    <hr>
    <div class="text-center">
        <a class="small" href="{{ route('login') }}">Back to Login</a>
    </div>
</x-guest-layout>
