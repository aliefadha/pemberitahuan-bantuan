<x-guest-layout title="Reset Password">
    <form method="POST" action="{{ route('password.store') }}" class="user">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                id="exampleInputEmail" placeholder="Email Address" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror"
                    id="exampleInputPassword" placeholder="Password" name="password" required autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-sm-6">
                <input type="password" class="form-control form-control-user @error('password_confirmation') is-invalid @enderror"
                    id="exampleRepeatPassword" placeholder="Repeat Password" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-user btn-block">
            Reset Password
        </button>
    </form>
    <hr>
    <div class="text-center">
        <a class="small" href="{{ route('login') }}">Back to Login</a>
    </div>
</x-guest-layout>
