<aside class="app-sidebar text-white">
    <div class="sidebar-brand">

        <a href="javascript:void(0)" class="brand-link d-flex align-items-center justify-content-center">

            <img src="{{ url('image/logo.png') }}" alt="Logo SIJALA" class="brand-logo-circle">

            <span class="fw-bold text-white ms-2">
                SIJALA
            </span>

        </a>

    </div>

    <div class="sidebar-wrapper">

        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false" role="menu">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') || request()->routeIs('profile') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-house-door-fill"></i>
                        <p>Beranda</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('users') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Pengguna</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('counselings') }}"
                        class="nav-link {{ request()->is('counselings*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-chat-dots-fill"></i>
                        <p>Konseling</p>
                    </a>
                </li>

                @php
                    $reports = [
                        ['slug' => 'counselee', 'name' => 'Konseli'],
                        ['slug' => 'elderly', 'name' => 'Lansia'],
                        ['slug' => 'counselor', 'name' => 'Konselor'],
                        ['slug' => 'counseling', 'name' => 'Konseling'],
                        ['slug' => 'screening', 'name' => 'Skrining'],
                        ['slug' => 'evaluation', 'name' => 'Evaluasi'],
                    ];

                    $currentReport = request()->route('report');
                @endphp

                <li class="nav-item {{ request()->routeIs('reports.show') ? 'menu-open' : '' }}">

                    <a href="javascript:void(0)"
                        class="nav-link {{ request()->routeIs('reports.show') ? 'active' : '' }}">

                        <i class="nav-icon bi bi-file-earmark-bar-graph-fill"></i>

                        <p>
                            Laporan
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>

                    <ul class="nav nav-treeview">

                        @foreach ($reports as $report)
                            <li class="nav-item">

                                <a href="{{ route('reports.show', $report['slug']) }}"
                                    class="nav-link {{ $currentReport === $report['slug'] ? 'active' : '' }}">

                                    <i class="nav-icon bi bi-circle"></i>

                                    <p>{{ $report['name'] }}</p>

                                </a>

                            </li>
                        @endforeach

                    </ul>

                </li>

                {{-- <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="nav-icon bi bi-circle-fill"></i>
                        <p>Laporan</p>
                    </a>
                </li> --}}
            </ul>

        </nav>

    </div>

</aside>
