<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RBTGTECH</title>



    <link rel="shortcut icon" href="{{ asset('mazer/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app') }}.css">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/auth.css') }}">
</head>

<body>
    <script src="{{ asset('mazer/assets/static/js/initTheme.js') }}"></script>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        {{-- <a href="#"><img src="{{ asset('mazer/assets/compiled/svg/logo.svg') }}"
                                alt="Logo"></a> --}}
                    </div>
                    <!-- Menampilkan pesan sukses jika ada -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Menampilkan pesan error jika ada -->
                    @if ($errors->has('error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error') }}
                        </div>
                    @endif

                    <!-- Menampilkan pesan validasi jika ada masalah dengan username -->
                    @if ($errors->has('username_reset'))
                        <div class="alert alert-danger">
                            {{ $errors->first('username_reset') }}
                        </div>
                    @endif

                    <h1 class="auth-title">Log in.</h1>
                    <p class="auth-subtitle mb-5">Aplikasi POS by RBTGTECH</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf <!-- Token CSRF untuk keamanan -->

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="username"
                                placeholder="Username" value="{{ old('username') }}" required autofocus>

                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>

                            @error('username')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" name="password"
                                placeholder="Password" required>

                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>

                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>

                    <!-- Form Reset Password berdasarkan username -->
                    <form action="{{ route('reset.password') }}" method="POST" class="mt-3">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="username_reset"
                                placeholder="Masukkan username untuk reset password" required>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning btn-block btn-lg shadow-lg">Reset Password ke
                            123</button>
                    </form>


                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>

    </div>
</body>

</html>
