<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SIJALA | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="#" />
    <meta name="keywords" content="#" />
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{ asset('css/adminlte.css') }}" as="style" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media = 'all'" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary" style="font-family: Poppins;">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ asset('images/adminlte/user2-160x160.jpg') }}"
                                class="user-image rounded-circle shadow" alt="User Image" />
                            <span class="d-none d-md-inline">Alexander Pierce</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <img src="{{ asset('images/adminlte/user2-160x160.jpg') }}"
                                    class="rounded-circle shadow" alt="User Image" />
                                <p>
                                    Alexander Pierce - Web Developer
                                    <small>Member since Nov. 2023</small>
                                </p>
                            </li>
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                            </li>
                            <li class="user-footer">
                                <a href="#" class="btn btn-outline-secondary">Profile</a>
                                <a href="#" class="btn btn-outline-danger float-end">Sign out</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="/" class="brand-link">
                    <!--begin::Brand Image-->
                    <img src="{{ asset('images/adminlte/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                        class="brand-image opacity-75 shadow" />
                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">SIJALA</span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2" aria-label="Main navigation">
                    <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false"
                        id="navigation">
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>
                                    Dashboard
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./index.html" class="nav-link active">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Dashboard v1</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./index2.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Dashboard v2</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./index3.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Dashboard v3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="./generate/theme.html" class="nav-link">
                                <i class="nav-icon bi bi-palette"></i>
                                <p>Theme Generate</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-box-seam-fill"></i>
                                <p>
                                    Widgets
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./widgets/small-box.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Small Box</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./widgets/info-box.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>info Box</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./widgets/cards.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Cards</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>
                                    Layout Options
                                    <span class="nav-badge badge text-bg-secondary me-3">7</span>
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./layout/unfixed-sidebar.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Default Sidebar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./layout/fixed-sidebar.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Fixed Sidebar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./layout/fixed-header.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Fixed Header</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./layout/fixed-footer.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Fixed Footer</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./layout/fixed-complete.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Fixed Complete</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./layout/layout-custom-area.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Layout <small>+ Custom Area </small></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./layout/sidebar-mini.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Sidebar Mini</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./layout/collapsed-sidebar.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Sidebar Mini <small>+ Collapsed</small></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./layout/collapsed-sidebar-without-hover.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Sidebar Mini <small>+ Collapsed + No Hover</small></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./layout/logo-switch.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Sidebar Mini <small>+ Logo Switch</small></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./layout/layout-rtl.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Layout RTL</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-tree-fill"></i>
                                <p>
                                    UI Elements
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./UI/general.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>General</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./UI/icons.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Icons</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./UI/timeline.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Timeline</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-envelope"></i>
                                <p>
                                    Mailbox
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./mailbox/inbox.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Inbox</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./mailbox/read.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Read Message</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./mailbox/compose.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Compose</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-pencil-square"></i>
                                <p>
                                    Forms
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./forms/elements.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Elements</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./forms/layout.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Layout</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./forms/validation.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Validation</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./forms/wizard.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Wizard</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-table"></i>
                                <p>
                                    Tables
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./tables/simple.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Simple Tables</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./tables/data.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Data Tables</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header">PAGES</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>
                                    Pages
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./pages/profile.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Profile</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./pages/settings.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Settings</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./pages/invoice.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Invoice</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./pages/calendar.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Calendar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./pages/kanban.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Kanban</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./pages/chat.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Chat</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./pages/file-manager.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>File Manager</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./pages/projects.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Projects</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./pages/pricing.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Pricing</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./pages/faq.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>FAQ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>
                                            Error
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="./pages/404.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>404</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./pages/500.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>500</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./pages/maintenance.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Maintenance</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header">EXAMPLES</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-box-arrow-in-right"></i>
                                <p>
                                    Auth
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-box-arrow-in-right"></i>
                                        <p>
                                            Version 1
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="./examples/login.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Login</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./examples/register.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Register</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-box-arrow-in-right"></i>
                                        <p>
                                            Version 2
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="./examples/login-v2.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Login</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./examples/register-v2.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Register</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="./examples/lockscreen.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Lockscreen</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header">MULTI LEVEL EXAMPLE</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-circle-fill"></i>
                                <p>Level 1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-circle-fill"></i>
                                <p>
                                    Level 1
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Level 2</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>
                                            Level 2
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="nav-icon bi bi-record-circle-fill"></i>
                                                <p>Level 3</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="nav-icon bi bi-record-circle-fill"></i>
                                                <p>Level 3</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="nav-icon bi bi-record-circle-fill"></i>
                                                <p>Level 3</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Level 2</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-circle-fill"></i>
                                <p>Level 1</p>
                            </a>
                        </li>

                        <li class="nav-header">LABELS</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-circle text-danger"></i>
                                <p class="text">Important</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-circle text-warning"></i>
                                <p>Warning</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-circle text-info"></i>
                                <p>Informational</p>
                            </a>
                        </li>
                    </ul>
                    <!--end::Sidebar Menu-->

                    <!-- Docs CTA (bottom of sidebar) -->
                    <div class="p-3 mt-3 border-top border-secondary border-opacity-25">
                        <a href="./docs/introduction.html"
                            class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-book" aria-hidden="true"></i>
                            View documentation
                        </a>
                    </div>
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Dashboard</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
            <!--begin::App Content-->
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                    
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">Anything you want</div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; 2014-2026&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('js/adminlte.js') }}"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

            // Disable OverlayScrollbars on mobile devices to prevent touch interference
            const isMobile = window.innerWidth <= 992;

            if (
                sidebarWrapper &&
                OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
                !isMobile
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--end::OverlayScrollbars Configure--><!--begin::Color Mode Toggle (#6010)-->
    <script>
        (() => {
            'use strict';

            const STORAGE_KEY = 'lte-theme';

            const getStoredTheme = () => localStorage.getItem(STORAGE_KEY);
            const setStoredTheme = (theme) => localStorage.setItem(STORAGE_KEY, theme);

            const prefersDark = () => globalThis.matchMedia('(prefers-color-scheme: dark)').matches;

            const getPreferredTheme = () => {
                const stored = getStoredTheme();
                if (stored) return stored;
                return prefersDark() ? 'dark' : 'light';
            };

            const setTheme = (theme) => {
                const resolved = theme === 'auto' ? (prefersDark() ? 'dark' : 'light') : theme;
                document.documentElement.setAttribute('data-bs-theme', resolved);
            };

            setTheme(getPreferredTheme());

            const showActiveTheme = (theme) => {
                // Highlight the active dropdown option
                document.querySelectorAll('[data-bs-theme-value]').forEach((el) => {
                    el.classList.remove('active');
                    el.setAttribute('aria-pressed', 'false');
                    const check = el.querySelector('.bi-check-lg');
                    if (check) check.classList.add('d-none');
                });
                const active = document.querySelector(`[data-bs-theme-value="${theme}"]`);
                if (active) {
                    active.classList.add('active');
                    active.setAttribute('aria-pressed', 'true');
                    const check = active.querySelector('.bi-check-lg');
                    if (check) check.classList.remove('d-none');
                }
                // Sync the topbar trigger icon
                document.querySelectorAll('[data-lte-theme-icon]').forEach((icon) => {
                    icon.classList.toggle('d-none', icon.dataset.lteThemeIcon !== theme);
                });
            };

            globalThis.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                const stored = getStoredTheme();
                if (!stored || stored === 'auto') setTheme(getPreferredTheme());
            });

            document.addEventListener('DOMContentLoaded', () => {
                showActiveTheme(getPreferredTheme());
                document.querySelectorAll('[data-bs-theme-value]').forEach((toggle) => {
                    toggle.addEventListener('click', () => {
                        const theme = toggle.getAttribute('data-bs-theme-value');
                        setStoredTheme(theme);
                        setTheme(theme);
                        showActiveTheme(theme);
                    });
                });
            });
        })();
    </script>
    <!--end::Color Mode Toggle-->

    <!-- OPTIONAL SCRIPTS -->

    <!-- sortablejs -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" crossorigin="anonymous"></script>

    <!-- sortablejs -->
    <script>
        new Sortable(document.querySelector('.connectedSortable'), {
            group: 'shared',
            handle: '.card-header',
        });

        const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
        cardHeaders.forEach((cardHeader) => {
            cardHeader.style.cursor = 'move';
        });
    </script>

    <!-- apexcharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>

    <!-- ChartJS -->
    <script>
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        const sales_chart_options = {
            series: [{
                    name: 'Digital Goods',
                    data: [28, 48, 40, 19, 86, 27, 90],
                },
                {
                    name: 'Electronics',
                    data: [65, 59, 80, 81, 56, 55, 40],
                },
            ],
            chart: {
                height: 300,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            legend: {
                show: false,
            },
            colors: ['#0d6efd', '#20c997'],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: 'smooth',
            },
            xaxis: {
                type: 'datetime',
                categories: [
                    '2023-01-01',
                    '2023-02-01',
                    '2023-03-01',
                    '2023-04-01',
                    '2023-05-01',
                    '2023-06-01',
                    '2023-07-01',
                ],
            },
            tooltip: {
                x: {
                    format: 'MMMM yyyy',
                },
            },
        };

        const sales_chart = new ApexCharts(
            document.querySelector('#revenue-chart'),
            sales_chart_options,
        );
        sales_chart.render();
    </script>

    <!-- jsvectormap -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
        integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
        integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script>

    <!-- jsvectormap -->
    <script>
        // World map by jsVectorMap
        new jsVectorMap({
            selector: '#world-map',
            map: 'world',
        });

        // Sparkline charts
        const option_sparkline1 = {
            series: [{
                data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
            }, ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparkline1 = new ApexCharts(document.querySelector('#sparkline-1'), option_sparkline1);
        sparkline1.render();

        const option_sparkline2 = {
            series: [{
                data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
            }, ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparkline2 = new ApexCharts(document.querySelector('#sparkline-2'), option_sparkline2);
        sparkline2.render();

        const option_sparkline3 = {
            series: [{
                data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
            }, ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparkline3 = new ApexCharts(document.querySelector('#sparkline-3'), option_sparkline3);
        sparkline3.render();
    </script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>
