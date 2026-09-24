<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Alhuda Primary and Intermediate School</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bs-body-font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #3b82f6;
            --card-shadow: 0 4px 20px -2px rgba(0,0,0,0.05), 0 2px 6px -1px rgba(0,0,0,0.02);
            --card-shadow-hover: 0 10px 25px -3px rgba(0,0,0,0.08), 0 4px 10px -2px rgba(0,0,0,0.04);
            --page-bg: #f8fafc;
            --surface-bg: #ffffff;
            --surface-muted: #f1f5f9;
            --text-main: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            background-color: var(--page-bg);
            color: var(--text-main);
            font-family: var(--bs-body-font-family);
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        #wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background: var(--sidebar-bg);
            color: #94a3b8;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        #sidebar .sidebar-header {
            padding: 1.5rem 1.25rem;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        #sidebar ul.components {
            padding: 1rem 0;
        }

        #sidebar ul li a {
            padding: 0.75rem 1.25rem;
            font-size: 0.925rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            margin: 0.15rem 0;
        }

        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #ffffff;
            background: var(--sidebar-hover);
            border-left-color: var(--sidebar-active);
        }

        #sidebar ul li a i {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 0.75rem;
        }

        #content {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-main {
            background: var(--surface-bg);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02);
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            background: var(--surface-bg);
            box-shadow: var(--card-shadow);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-shadow-hover);
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            background: var(--surface-bg);
        }

        .badge-soft-primary { background-color: #e0e7ff; color: #4338ca; }
        .badge-soft-success { background-color: #d1fae5; color: #047857; }
        .badge-soft-warning { background-color: #fef3c7; color: #b45309; }
        .badge-soft-danger  { background-color: #fee2e2; color: #b91c1c; }
        .badge-soft-info    { background-color: #e0f2fe; color: #0369a1; }
        .badge-soft-secondary { background-color: #f1f5f9; color: #475569; }

        .table-custom {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 0.775rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-custom tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        [data-theme="dark"] {
            --page-bg: #0f172a;
            --surface-bg: #1e293b;
            --surface-muted: #334155;
            --text-main: #e2e8f0;
            --text-muted: #94a3b8;
            --border-color: #475569;
        }

        [data-theme="dark"] .text-dark,
        [data-theme="dark"] .text-slate-800,
        [data-theme="dark"] .text-slate-900 {
            color: var(--text-main) !important;
        }

        [data-theme="dark"] .text-muted,
        [data-theme="dark"] .text-secondary {
            color: var(--text-muted) !important;
        }

        [data-theme="dark"] .bg-light,
        [data-theme="dark"] .table-light,
        [data-theme="dark"] .navbar-main .btn-light,
        [data-theme="dark"] .dropdown-menu {
            background-color: var(--surface-muted) !important;
            color: var(--text-main);
        }

        [data-theme="dark"] .table,
        [data-theme="dark"] .table td,
        [data-theme="dark"] .table th,
        [data-theme="dark"] .border,
        [data-theme="dark"] .border-top,
        [data-theme="dark"] .border-bottom {
            border-color: var(--border-color) !important;
            color: var(--text-main);
        }

        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: var(--surface-muted);
            border-color: var(--border-color);
            color: var(--text-main);
        }

        [data-theme="dark"] .form-control::placeholder {
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -260px;
            }
            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<div id="wrapper">
    <!-- Sidebar Navigation -->
    <nav id="sidebar">
        <div class="sidebar-header d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="text-decoration-none d-flex align-items-center">
                <img src="{{ asset('images/alhuda-logo.svg') }}" alt="Alhuda Primary and Intermediate School" class="img-fluid" style="width: 190px; height: 72px; object-fit: contain;">
            </a>
        </div>

        <ul class="list-unstyled components">
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-gauge"></i> <span>Dashboard</span>
                </a>
            </li>

            <!-- Shared Resources (Admin, Manager, Teacher) -->
            <li class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
                <a href="{{ route('students.index') }}">
                    <i class="fa-solid fa-user-graduate"></i> <span>Students</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                <a href="{{ route('attendance.index') }}">
                    <i class="fa-solid fa-clipboard-user"></i> <span>Attendance</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('exams.*') ? 'active' : '' }}">
                <a href="{{ route('exams.index') }}">
                    <i class="fa-solid fa-file-pen"></i> <span>Exams</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('classes.*') ? 'active' : '' }}">
                <a href="{{ route('classes.index') }}">
                    <i class="fa-solid fa-school"></i> <span>Classes</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                <a href="{{ route('announcements.index') }}">
                    <i class="fa-solid fa-bullhorn"></i> <span>Announcements</span>
                </a>
            </li>
            @auth
                @if(in_array(Auth::user()->role, ['admin', 'manager']))
                    <li class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <a href="{{ route('payments.index') }}">
                            <i class="fa-solid fa-money-bill-wave"></i> <span>Payments</span>
                        </a>
                    </li>
                @endif
            @endauth

            <!-- Restricted Resources (Admin & Manager) -->
            @auth
                @if(in_array(Auth::user()->role, ['admin', 'manager']))
                    <div class="px-3 pt-3 pb-1 text-uppercase text-xs fw-bold text-slate-500 opacity-50" style="font-size: 0.7rem; letter-spacing: 0.05em;">Management</div>

                    <li class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                        <a href="{{ route('teachers.index') }}">
                            <i class="fa-solid fa-chalkboard-user"></i> <span>Teachers</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('staff.*') ? 'active' : '' }}">
                        <a href="{{ route('staff.index') }}">
                            <i class="fa-solid fa-users"></i> <span>Staff</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('managers.*') ? 'active' : '' }}">
                        <a href="{{ route('managers.index') }}">
                            <i class="fa-solid fa-user-tie"></i> <span>Managers</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('parents.*') ? 'active' : '' }}">
                        <a href="{{ route('parents.index') }}">
                            <i class="fa-solid fa-people-roof"></i> <span>Parents</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <a href="{{ route('reports.index') }}">
                            <i class="fa-solid fa-chart-line"></i> <span>Reports</span>
                        </a>
                    </li>
                @endif
            @endauth
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-main px-4 py-2">
            <div class="container-fluid p-0 d-flex justify-content-between align-items-center">
                <button type="button" id="sidebarCollapse" class="btn btn-light border-0 d-md-none me-2">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="d-flex align-items-center text-secondary">
                    <span class="small fw-semibold">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> Security Protected System
                    </span>
                </div>

                <div class="d-flex align-items-center">
                    <button type="button" id="themeToggle" class="btn btn-light border-0 me-2" title="Toggle dark mode" aria-label="Toggle dark mode">
                        <i class="fa-solid fa-moon"></i>
                    </button>
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-light border-0 dropdown-toggle d-flex align-items-center gap-2 py-1 px-3 rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold text-uppercase" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="text-start d-none d-sm-block">
                                    <div class="fw-bold text-dark lh-1" style="font-size: 0.875rem;">{{ Auth::user()->name }}</div>
                                    <small class="text-muted text-capitalize" style="font-size: 0.725rem;">{{ Auth::user()->role }}</small>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}">
                                        <i class="fa-solid fa-user-gear me-2 text-primary"></i> Profile Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i> Sign Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary fw-bold px-3">Log in</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Body Content -->
        <main class="p-4 flex-grow-1">
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')

            <footer class="text-center text-muted small pt-4 pb-2">
                Created by Abdirahman Mahad
                <br>
                saleban osman
                  <br>
                abdiwali mohamed
            </footer>
        </main>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const savedTheme = localStorage.getItem('schoolsms-theme');
    const preferredTheme = savedTheme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    document.documentElement.dataset.theme = preferredTheme;

    const themeToggle = document.getElementById('themeToggle');
    const updateThemeToggle = () => {
        const isDark = document.documentElement.dataset.theme === 'dark';
        themeToggle.innerHTML = `<i class="fa-solid fa-${isDark ? 'sun' : 'moon'}"></i>`;
        themeToggle.setAttribute('aria-label', isDark ? 'Use light mode' : 'Use dark mode');
        themeToggle.setAttribute('title', isDark ? 'Use light mode' : 'Use dark mode');
    };

    updateThemeToggle();
    themeToggle?.addEventListener('click', function () {
        const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
        document.documentElement.dataset.theme = nextTheme;
        localStorage.setItem('schoolsms-theme', nextTheme);
        updateThemeToggle();
    });

    document.getElementById('sidebarCollapse')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>
@stack('scripts')
</body>
</html>
