<aside class="app-sidebar text-white">
    <div class="sidebar-brand">

        <a href="{{ route('dashboard') }}" class="brand-link d-flex align-items-center justify-content-center">

            <img src="{{ asset('images/logo.png') }}" alt="Logo SIJALA" class="brand-logo-circle">

            <span class="fw-bold text-white ms-2">
                SIJALA
            </span>

        </a>

    </div>

    <div class="sidebar-wrapper">

        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false" role="menu">

                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle-fill"></i>
                        <p>Beranda</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('users.*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle-fill"></i>
                        <p>
                            Pengguna
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.counselees') }}" class="nav-link {{ request()->routeIs('users.counselees') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Konseli</p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>
                                    Level 2
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="javascript:void(0)" class="nav-link">
                                        <i class="nav-icon bi bi-record-circle-fill"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0)" class="nav-link">
                                        <i class="nav-icon bi bi-record-circle-fill"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0)" class="nav-link">
                                        <i class="nav-icon bi bi-record-circle-fill"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}
                        <li class="nav-item">
                            <a href="{{ route('users.counselors') }}" class="nav-link {{ request()->routeIs('users.counselors') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Konselor</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="nav-icon bi bi-circle-fill"></i>
                        <p>
                            Konseling
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Data Konseling</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Hasil Skrining</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Hasil Evaluasi</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="nav-icon bi bi-circle-fill"></i>
                        <p>Laporan</p>
                    </a>
                </li>


            </ul>

        </nav>

    </div>

</aside>
