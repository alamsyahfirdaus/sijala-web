<nav class="app-header navbar navbar-expand bg-white shadow-sm">

    <div class="container-fluid">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="javascript:void(0)">
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>

        @php
            $name = Auth::user()->name;

            $initials = collect(explode(' ', trim($name)))
                ->filter()
                ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp

        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle d-flex align-items-center" href="javascript:void(0)" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">

                    <div class="user-avatar me-2">
                        {{ $initials }}
                    </div>

                    <span>{{ $name }}</span>

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i>
                            Profil
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Logout
                            </button>
                        </form>
                    </li>

                </ul>

            </li>
        </ul>
    </div>
</nav>
