<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pemberitahuan Dosen') }}</title>
    <link href="{{ asset('vendor/sb-admin-2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('vendor/sb-admin-2/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5 text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Pemberitahuan Bantuan</h1>
                                    <p class="mb-4">Sistem Informasi Pemberitahuan Bantuan</p>
                                    @if (Route::has('login'))
                                        @auth
                                            <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-user btn-block">
                                                Dashboard
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary btn-user btn-block">
                                                Login
                                            </a>
                                            @if (Route::has('register'))
                                                <a href="{{ route('register') }}" class="btn btn-secondary btn-user btn-block">
                                                    Register
                                                </a>
                                            @endif
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('vendor/sb-admin-2/js/sb-admin-2.min.js') }}"></script>
</body>
</html>
