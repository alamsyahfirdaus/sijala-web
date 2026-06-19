<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJALA | Login</title>
    <link rel="icon" href="{{ url('image/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .input-group .form-control.error {
            border-color: #dc3545 !important;
        }

        .input-group .form-control.valid {
            border-color: #198754 !important;
        }

        label.error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: block;
            font-weight: 500;
        }

        .btn-login:disabled {
            opacity: .7;
            cursor: not-allowed;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .15);
        }

        .login-header {
            text-align: center;
            padding: 30px 20px 15px;
        }

        .logo-circle {
            width: 100px;
            height: 100px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .15);
        }

        .logo-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: contain;
        }

        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
        }

        .input-group-text {
            border-radius: 0 12px 12px 0;
        }

        .btn-login {
            height: 50px;
            border-radius: 12px;
            font-weight: 600;
        }

        .footer-text {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 12px;
        }
    </style>
</head>

<body>

    <div class="card login-card">

        <div class="login-header">

            <div class="logo-circle">
                <img src="{{ url('image/logo.png') }}" alt="Logo SIJALA" class="logo-image">
            </div>

            <div class="login-title">
                SIJALA
            </div>
        </div>

        <div class="card-body p-4">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form id="loginForm" action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Username</label>

                    <div class="input-group">
                        <input type="text" name="username" id="username" class="form-control"
                            placeholder="Masukkan username" autocomplete="off" value="{{ old('username') }}">

                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                    </div>

                    @error('username')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>

                    <div class="input-group">

                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Masukkan password">

                        <button type="button" class="input-group-text" id="togglePassword">
                            <i id="eyeIcon" class="bi bi-eye"></i>
                        </button>

                    </div>

                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-warning text-white btn-login w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Login
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('landing') }}" class="text-decoration-none" style="color: #f59e0b; font-size: 14px;">
                        Kembali ke Dashboard
                    </a>
                </div>

            </form>

            <div class="footer-text">
                © {{ date('Y') }} Jaga Lansia Official.
            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.20.0/dist/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {

            // =====================================================
            // TOGGLE SHOW / HIDE PASSWORD
            // =====================================================
            $("#togglePassword").on("click", function() {

                const passwordField = $("#password");
                const eyeIcon = $("#eyeIcon");

                if (passwordField.attr("type") === "password") {

                    passwordField.attr("type", "text");

                    eyeIcon
                        .removeClass("bi-eye")
                        .addClass("bi-eye-slash");

                } else {

                    passwordField.attr("type", "password");

                    eyeIcon
                        .removeClass("bi-eye-slash")
                        .addClass("bi-eye");

                }
            });

            // =====================================================
            // VALIDASI FORM LOGIN
            // =====================================================
            $("#loginForm").validate({

                errorElement: "label",

                errorClass: "error",

                rules: {
                    username: {
                        required: true,
                    },

                    password: {
                        required: true,
                    }
                },

                messages: {

                    username: {
                        required: "Username wajib diisi",
                    },

                    password: {
                        required: "Password wajib diisi",
                    }
                },

                highlight: function(element) {

                    $(element)
                        .addClass("error")
                        .removeClass("valid");
                },

                unhighlight: function(element) {

                    $(element)
                        .removeClass("error")
                        .addClass("valid");
                },

                errorPlacement: function(error, element) {

                    error.addClass("mt-1");

                    if (element.closest(".input-group").length) {
                        error.insertAfter(element.closest(".input-group"));
                    } else {
                        error.insertAfter(element);
                    }
                },

                submitHandler: function(form) {

                    const button = $(".btn-login");

                    button
                        .prop("disabled", true)
                        .html(`
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Sedang Login...
                    `);

                    form.submit();
                }
            });

            // =====================================================
            // ENTER KEY SUBMIT
            // =====================================================
            $("#username, #password").on("keypress", function(e) {

                if (e.which === 13) {

                    e.preventDefault();

                    if ($("#loginForm").valid()) {
                        $("#loginForm").submit();
                    }
                }
            });

            // =====================================================
            // HAPUS ERROR SAAT MENGETIK
            // =====================================================
            $("#username, #password").on("keyup", function() {

                $(this).valid();
            });

        });
    </script>

</body>

</html>
