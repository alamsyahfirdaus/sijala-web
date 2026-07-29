<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIJALA | Jaga Kesehatan Lansia</title>
    <meta name="title" content="SIJALA | Jaga Kesehatan Lansia">
    <meta name="description"
        content="SIJALA merupakan Jaga Kesehatan Lansia yang mendukung skrining risiko jatuh, kemandirian kesehatan keluarga, edukasi kesehatan, dan layanan konseling digital.">
    <meta name="theme-color" content="#FBC02D">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/adminlte.css">
    <style>
        :root {
            --primary: #fbc02d;
            --primary-dark: #f9a825;
            --success: #198754;
        }

        body {
            font-family: "Source Sans 3", sans-serif;
            background: linear-gradient(135deg, #fffef8, #fff8e1);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            border-radius: 50%;
            background: rgba(251, 192, 45, .12);
            z-index: -1;
        }

        body::before {
            width: 350px;
            height: 350px;
            top: -120px;
            left: -120px;
        }

        body::after {
            width: 280px;
            height: 280px;
            bottom: -120px;
            right: -120px;
        }

        .maintenance-card {
            border: 0;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .10);
        }

        .maintenance-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 3rem 2rem;
        }

        .logo-circle {
            width: 120px;
            height: 120px;
            margin: auto;
            border-radius: 50%;
            background: rgba(255, 255, 255, .18);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 3.8rem;
            box-shadow: inset 0 0 0 8px rgba(255, 255, 255, .08);
        }

        .badge-status {
            background: rgba(255, 255, 255, .18);
            color: white;
            padding: .7rem 1.3rem;
            border-radius: 100px;
            font-size: .95rem;
        }

        .feature-item {
            padding: .85rem 1rem;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #ececec;
            margin-bottom: 12px;
            transition: .25s;
        }

        .feature-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08);
        }

        .feature-item i {
            color: var(--success);
            font-size: 1.2rem;
            margin-right: .75rem;
        }

        .btn-warning {
            background: var(--primary);
            border-color: var(--primary);
            color: #222;
            font-weight: 600;
        }

        .btn-warning:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        footer {
            color: #6c757d;
            font-size: .9rem;
        }

        @media (max-width:768px) {

            .maintenance-header {
                padding: 2.5rem 1.5rem;
            }

            .logo-circle {
                width: 95px;
                height: 95px;
                font-size: 3rem;
            }

            h1.display-5 {
                font-size: 2.3rem;
            }

            .card-body {
                padding: 2rem !important;
            }

        }
    </style>

</head>

<body>
    <main class="container d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="row justify-content-center w-100">
            <div class="col-lg-8 col-xl-7">
                <div class="card maintenance-card">
                    <div class="maintenance-header text-center">
                        <div class="logo-circle">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </div>
                        <h1 class="display-5 fw-bold mt-4 mb-2">SIJALA</h1>
                        <h4 class="fw-normal mb-4">Jaga Kesehatan Lansia</h4>
                        <span class="badge-status">
                            <i class="bi bi-tools me-2"></i>
                            Dalam Tahap Pengembangan
                        </span>
                    </div>
                    <div class="card-body p-5">
                        <p class="lead text-center text-secondary mb-5">
                            Kami sedang menyempurnakan SIJALA agar dapat memberikan
                            pengalaman terbaik dalam mendukung pelayanan kesehatan lansia,
                            skrining risiko jatuh, kemandirian kesehatan keluarga, edukasi kesehatan,
                            dan layanan konseling digital.
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Skrining Risiko Jatuh
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Kemandirian Kesehatan Keluarga
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Konseling Digital
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Edukasi Kesehatan Lansia
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning mt-4 mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Sistem sedang dalam proses penyempurnaan untuk memastikan seluruh
                            fitur berjalan secara optimal, aman, dan nyaman digunakan.
                        </div>
                        <div class="text-center mt-4">
                            <div class="text-secondary">
                                <i class="bi bi-hourglass-split fs-4 text-warning d-block mb-2"></i>
                                <small>
                                    Halaman ini akan segera tersedia setelah proses pengembangan selesai.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center bg-body-tertiary py-4">
                        <footer>
                            <strong>© 2026 SIJALA</strong>
                            <br>
                            Jaga Kesehatan Lansia
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/adminlte.js"></script>
</body>

</html>
