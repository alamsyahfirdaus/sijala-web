<nav class="app-header navbar navbar-expand bg-white shadow-sm">

    <div class="container-fluid">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link"
                   data-lte-toggle="sidebar"
                   href="javascript:void(0)">
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">

            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle"
                   data-bs-toggle="dropdown"
                   href="javascript:void(0)">

                    <i class="bi bi-person-circle me-2"></i>

                    {{ Auth::user()->name }}

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item"
                           href="javascript:void(0)">
                            <i class="bi bi-person me-2"></i>
                            Profil
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>

                        <form action="{{ route('logout') }}"
                              method="POST">

                            @csrf

                            <button class="dropdown-item text-danger">

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