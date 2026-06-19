<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIJALA | @yield('title', 'SIJALA')</title>
    <link rel="icon" href="{{ url('image/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .app-sidebar {
            background: #f59e0b !important;
        }

        .brand-link {
            border-bottom: 1px solid rgba(255, 255, 255, .15);
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .brand-logo-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .content-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
        }

        .stat-card {
            border-radius: 16px;
            border: none;
            transition: .3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .app-footer {
            padding: 12px 20px;
            font-size: 14px;
        }

        .app-footer strong {
            color: #f59e0b;
        }

        .app-footer .text-muted {
            color: #6c757d !important;
        }

        .app-sidebar .nav-link.active {
            background: rgba(255, 255, 255, .20);
            color: #fff !important;
            border-radius: 10px;
        }

        .app-sidebar .menu-open>.nav-link {
            background: rgba(255, 255, 255, .15);
            color: #fff !important;
        }

        .app-sidebar .nav-treeview .nav-link.active {
            background: rgba(255, 255, 255, .25);
        }
    </style>

    @stack('styles')
</head>

<body class="fixed-header sidebar-expand-lg bg-body-tertiary">

    <div class="app-wrapper">

        @include('layouts.navbar')

        @include('layouts.sidebar')

        <main class="app-main">

            <div class="app-content-header">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">
                                @yield('page_title')
                            </h3>
                        </div>

                        <div class="col-sm-6">
                            @yield('breadcrumb')
                        </div>
                    </div>

                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">

                    @yield('content')

                </div>
            </div>

        </main>

        @include('layouts.footer')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('js/adminlte.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('#table, .datatable').DataTable({

                responsive: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],

                language: {
                    processing: "Sedang memproses...",
                    search: "Pencarian:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Data tidak ditemukan",
                    emptyTable: "Tidak ada data tersedia",
                    loadingRecords: "Memuat data...",
                    paginate: {
                        first: '<i class="bi bi-chevron-bar-left"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>',
                        next: '<i class="bi bi-chevron-right"></i>',
                        last: '<i class="bi bi-chevron-bar-right"></i>'
                    }
                }

            });

        });
    </script>

    {{-- @stack('scripts') --}}

</body>

</html>
