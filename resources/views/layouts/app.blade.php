<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIJALA | @yield('title', 'SIJALA')</title>
    <link rel="icon" href="{{ url('image/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
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

        .select2-error .select2-selection {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            font-size: 12px;
        }

        .auto-dismiss {
            transition: all .5s ease;
        }
    </style>

    @stack('styles')
</head>

<body class="fixed-header sidebar-expand-lg bg-body-tertiary">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/adminlte.js') }}"></script>

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
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show auto-dismiss" role="alert">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show auto-dismiss" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                    @yield('content')

                </div>
            </div>

        </main>

        @include('layouts.footer')

    </div>

    <script>
        $(document).ready(function() {

            $('#table, .datatable').DataTable({

                responsive: true,
                autoWidth: false,

                order: [],

                pageLength: 10,

                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],

                columnDefs: [{
                    targets: 0,
                    orderable: false,
                    searchable: false
                }],

                language: {
                    processing: "Sedang memproses...",
                    search: "Pencarian:",
                    lengthMenu: "Tampilkan _MENU_",
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

            setTimeout(function() {

                $('.auto-dismiss').css({
                    transition: 'all .5s ease',
                    opacity: 0,
                    transform: 'translateY(-10px)'
                });

                setTimeout(function() {
                    $('.auto-dismiss').slideUp(300, function() {
                        $(this).remove();
                    });
                }, 500);

            }, 5000);

            
        });
    </script>

    {{-- @stack('scripts') --}}

</body>

</html>
