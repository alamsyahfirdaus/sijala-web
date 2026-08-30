<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJALA | Login Administrator</title>
    <link rel="icon" href="{{ url('image/logo.png') }}">
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        /* ===================================================
           DESIGN TOKENS & SYSTEM VARIABLES
        =================================================== */
        :root {
            /* SIJALA Brand Colors */
            --sijala-primary: #d97706;
            --sijala-primary-hover: #b45309;
            --sijala-primary-dark: #78350f;
            --sijala-primary-light: #fef3c7;
            --sijala-primary-subtle: #fffbeb;
            
            /* Healthcare Neutrals & Dark Slate */
            --sijala-dark: #0f172a;
            --sijala-slate: #1e293b;
            --sijala-text: #1e293b;
            --sijala-text-muted: #475569;
            --sijala-text-subtle: #64748b;
            
            /* Surfaces & Borders */
            --sijala-bg: #f8f6f0;
            --sijala-surface: #ffffff;
            --sijala-surface-alt: #faf8f5;
            --sijala-border: #e2e8f0;
            --sijala-border-focus: #d97706;

            /* Feedback Colors */
            --sijala-danger: #dc2626;
            --sijala-danger-bg: #fef2f2;
            --sijala-danger-border: #fecaca;
            --sijala-success: #16a34a;

            /* Radii Scale */
            --sijala-radius-sm: 8px;
            --sijala-radius-md: 14px;
            --sijala-radius-lg: 22px;
            --sijala-radius-full: 9999px;

            /* Shadows */
            --sijala-shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.04);
            --sijala-shadow-card: 0 16px 40px -8px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.04);
            --sijala-shadow-btn: 0 4px 14px rgba(217, 119, 6, 0.3);

            /* Transitions */
            --sijala-transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Base Resets */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            min-height: 100vh;
            background-color: var(--sijala-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(245, 158, 11, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(217, 119, 6, 0.08) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(254, 243, 199, 0.3) 0px, transparent 70%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: var(--sijala-text);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: var(--sijala-transition);
        }

        button {
            border: none;
            outline: none;
            background: none;
            cursor: pointer;
            font-family: inherit;
        }

        /* ===================================================
           LOGIN CONTAINER & SPLIT CARD
        =================================================== */
        .auth-wrapper {
            width: 100%;
            max-width: 880px;
            margin: 0 auto;
        }

        .auth-card {
            background: var(--sijala-surface);
            border-radius: var(--sijala-radius-lg);
            border: 1px solid var(--sijala-border);
            box-shadow: var(--sijala-shadow-card);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            animation: cardEntrance 0.35s ease-out forwards;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(12px) scale(0.99);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ===================================================
           LEFT PANEL: BRAND & HEALTHCARE INFO
        =================================================== */
        .auth-info-panel {
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            padding: 44px 36px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .auth-info-panel::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.18) 0%, transparent 70%);
            pointer-events: none;
        }

        .auth-info-top {
            position: relative;
            z-index: 2;
        }

        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
            padding: 5px 12px;
            border-radius: var(--sijala-radius-full);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
            margin-bottom: 24px;
        }

        .brand-box {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .brand-avatar {
            width: 48px;
            height: 48px;
            border-radius: var(--sijala-radius-full);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border: 2px solid var(--sijala-primary-light);
            flex-shrink: 0;
        }

        .brand-avatar img {
            width: 100%;
            height: 100%;
            border-radius: var(--sijala-radius-full);
            object-fit: contain;
        }

        .brand-titles {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
            line-height: 1.1;
        }

        .brand-sub {
            font-size: 12px;
            font-weight: 500;
            color: var(--sijala-primary-light);
            letter-spacing: 0.3px;
        }

        .auth-info-desc {
            font-size: 13.5px;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .auth-features-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
        }

        .auth-features-list li {
            font-size: 13px;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .auth-features-list li i {
            color: #fbbf24;
            font-size: 15px;
        }

        .auth-security-notice {
            position: relative;
            z-index: 2;
            padding: 12px 14px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--sijala-radius-sm);
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            align-items: flex-start;
            gap: 9px;
            line-height: 1.5;
        }

        .auth-security-notice i {
            color: #10b981;
            font-size: 16px;
            margin-top: 1px;
            flex-shrink: 0;
        }

        /* ===================================================
           RIGHT PANEL: LOGIN FORM
        =================================================== */
        .auth-form-panel {
            padding: 44px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: var(--sijala-surface);
        }

        .form-header {
            margin-bottom: 24px;
        }

        .form-kicker {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--sijala-primary);
            margin-bottom: 6px;
        }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--sijala-dark);
            letter-spacing: -0.4px;
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 13.5px;
            color: var(--sijala-text-muted);
            line-height: 1.6;
        }

        /* Session Alert */
        .alert-session-error {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: var(--sijala-danger-bg);
            border: 1px solid var(--sijala-danger-border);
            color: #991b1b;
            padding: 12px 14px;
            border-radius: var(--sijala-radius-sm);
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .alert-session-error i {
            font-size: 16px;
            color: var(--sijala-danger);
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--sijala-text);
            margin-bottom: 6px;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .form-control {
            width: 100%;
            height: 48px;
            padding: 0 44px 0 14px;
            font-size: 14px;
            color: var(--sijala-text);
            background: var(--sijala-surface-alt);
            border: 1.5px solid var(--sijala-border);
            border-radius: var(--sijala-radius-md);
            transition: var(--sijala-transition);
            outline: none;
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-size: 13.5px;
        }

        .form-control:focus {
            background: #ffffff;
            border-color: var(--sijala-border-focus);
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15);
        }

        .input-icon-left {
            position: absolute;
            left: 14px;
            color: var(--sijala-text-subtle);
            font-size: 16px;
            pointer-events: none;
        }

        .input-has-left-icon .form-control {
            padding-left: 42px;
        }

        /* Input Validation Styles */
        .form-control.error {
            border-color: var(--sijala-danger) !important;
            background-color: #fffafa !important;
        }

        .form-control.error:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12) !important;
        }

        .form-control.valid {
            border-color: var(--sijala-border) !important;
        }

        /* Validation Error Label */
        label.error,
        small.text-danger {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--sijala-danger);
            font-size: 12px;
            font-weight: 500;
            margin-top: 5px;
            line-height: 1.4;
        }

        /* Password Toggle Button */
        .password-toggle-btn {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sijala-text-subtle);
            font-size: 17px;
            transition: var(--sijala-transition);
            border-radius: 0 var(--sijala-radius-md) var(--sijala-radius-md) 0;
        }

        .password-toggle-btn:hover {
            color: var(--sijala-primary);
        }

        .password-toggle-btn:focus-visible {
            outline: 2px solid var(--sijala-primary);
            outline-offset: -2px;
        }

        /* Primary Login Button */
        .btn-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            height: 48px;
            background: var(--sijala-primary);
            color: #ffffff;
            border: 1px solid var(--sijala-primary);
            border-radius: var(--sijala-radius-md);
            font-size: 15px;
            font-weight: 600;
            box-shadow: var(--sijala-shadow-btn);
            transition: var(--sijala-transition);
            margin-top: 8px;
            cursor: pointer;
        }

        .btn-login:hover:not(:disabled) {
            background: var(--sijala-primary-hover);
            border-color: var(--sijala-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(217, 119, 6, 0.4);
        }

        .btn-login:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Custom Spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            display: inline-block;
            animation: spinnerBorder 0.75s linear infinite;
        }

        @keyframes spinnerBorder {
            to { transform: rotate(360deg); }
        }

        /* Secondary Navigation / Back to Landing */
        .back-nav-box {
            text-align: center;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid var(--sijala-border);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--sijala-primary);
            padding: 6px 12px;
            border-radius: var(--sijala-radius-sm);
            transition: var(--sijala-transition);
        }

        .back-link:hover {
            color: var(--sijala-primary-hover);
            background: var(--sijala-primary-subtle);
            transform: translateX(-2px);
        }

        /* Footer */
        .auth-footer {
            text-align: center;
            margin-top: 16px;
            font-size: 12px;
            color: var(--sijala-text-subtle);
        }

        /* ===================================================
           RESPONSIVE BREAKPOINTS
        =================================================== */
        @media (max-width: 840px) {
            .auth-card {
                grid-template-columns: 1fr;
                max-width: 460px;
                margin: 0 auto;
            }

            .auth-info-panel {
                padding: 32px 28px;
                text-align: center;
                align-items: center;
            }

            .auth-info-panel::before {
                display: none;
            }

            .brand-box {
                justify-content: center;
            }

            .auth-features-list {
                display: none;
            }

            .auth-info-desc {
                margin-bottom: 0;
            }

            .auth-security-notice {
                display: none;
            }

            .auth-form-panel {
                padding: 32px 28px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 16px 12px;
            }

            .auth-card {
                border-radius: var(--sijala-radius-md);
            }

            .auth-info-panel {
                padding: 24px 20px;
            }

            .brand-avatar {
                width: 42px;
                height: 42px;
            }

            .brand-name {
                font-size: 20px;
            }

            .auth-form-panel {
                padding: 24px 20px;
            }

            .form-title {
                font-size: 21px;
            }

            .form-control {
                height: 46px;
            }

            .btn-login {
                height: 46px;
            }
        }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>

    <main class="auth-wrapper">
        <div class="auth-card">

            <!-- Left Panel: Branding & Healthcare Information -->
            <section class="auth-info-panel" aria-label="Informasi SIJALA">
                <div class="auth-info-top">
                    <div class="portal-badge">
                        <i class="bi bi-shield-lock-fill"></i> Portal Administrator
                    </div>

                    <div class="brand-box">
                        <div class="brand-avatar">
                            <img src="{{ url('image/logo.png') }}" alt="Logo SIJALA">
                        </div>
                        <div class="brand-titles">
                            <span class="brand-name">SIJALA</span>
                            <span class="brand-sub">Konseling Jaga Lansia</span>
                        </div>
                    </div>

                    <p class="auth-info-desc">
                        Sistem informasi dan layanan pendampingan kesehatan lansia terpadu untuk meningkatkan kesejahteraan lansia dan keluarga.
                    </p>

                    <ul class="auth-features-list">
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Pengelolaan materi edukasi & poster</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Monitoring layanan pendampingan</span>
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Manajemen data & konseling lansia</span>
                        </li>
                    </ul>
                </div>

                <div class="auth-security-notice">
                    <i class="bi bi-shield-check"></i>
                    <span>Area akses khusus administrator, konselor, dan petugas resmi SIJALA.</span>
                </div>
            </section>

            <!-- Right Panel: Login Form -->
            <section class="auth-form-panel" aria-label="Formulir Login">
                <div class="form-header">
                    <div class="form-kicker">Autentikasi Petugas</div>
                    <h1 class="form-title">Login Administrator</h1>
                    <p class="form-subtitle">
                        Masuk untuk mengelola data dan layanan pada sistem SIJALA.
                    </p>
                </div>

                @if (session('error'))
                    <div class="alert-session-error" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                <form id="loginForm" action="{{ route('login.submit') }}" method="POST" novalidate>
                    @csrf

                    <!-- Username Field -->
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group input-has-left-icon">
                            <i class="bi bi-person input-icon-left" aria-hidden="true"></i>
                            <input type="text" 
                                   name="username" 
                                   id="username" 
                                   class="form-control"
                                   placeholder="Masukkan username" 
                                   autocomplete="off" 
                                   value="{{ old('username') }}" 
                                   required>
                        </div>
                        @error('username')
                            <small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group input-has-left-icon">
                            <i class="bi bi-lock input-icon-left" aria-hidden="true"></i>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control"
                                   placeholder="Masukkan password" 
                                   required>
                            <button type="button" 
                                    class="password-toggle-btn" 
                                    id="togglePassword" 
                                    aria-label="Tampilkan atau sembunyikan password">
                                <i id="eyeIcon" class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login" id="submitBtn">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Login</span>
                    </button>

                    <!-- Back to Landing Link -->
                    <div class="back-nav-box">
                        <a href="{{ route('landing') }}" class="back-link">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali ke Beranda</span>
                        </a>
                    </div>
                </form>

                <div class="auth-footer">
                    © {{ date('Y') }} Jaga Lansia Official. All Rights Reserved.
                </div>
            </section>

        </div>
    </main>

    <!-- jQuery & Validation Scripts -->
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
                    eyeIcon.removeClass("bi-eye").addClass("bi-eye-slash");
                    $(this).attr("aria-label", "Sembunyikan password");
                } else {
                    passwordField.attr("type", "password");
                    eyeIcon.removeClass("bi-eye-slash").addClass("bi-eye");
                    $(this).attr("aria-label", "Tampilkan password");
                }
            });

            // =====================================================
            // FORM VALIDATION (JQUERY VALIDATE)
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
                    $(element).addClass("error").removeClass("valid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("error").addClass("valid");
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
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span>Sedang Login...</span>
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
            // REALTIME VALIDATION ON TYPING
            // =====================================================
            $("#username, #password").on("keyup", function() {
                $(this).valid();
            });

        });
    </script>

</body>

</html>