<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System - Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }
        /* Sidebar Styling */
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background: #212529;
            color: #fff;
            transition: all 0.3s;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #1b1e21;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 1.05em;
            display: block;
            color: #adb5bd;
            text-decoration: none;
            transition: 0.2s;
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: #0d6efd;
        }
        /* Main Content Styling */
        #content {
            width: 100%;
            padding: 20px;
            min-height: 100vh;
        }
        .stat-card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header d-flex align-items-center justify-content-between">
            <h4 class="m-0 fw-bold text-primary"><i class="fa-solid fa-graduation-cap me-2"></i>SchoolSMS</h4>
        </div>

        <ul class="list-unstyled components">
            <li class="active">
                <a href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
            </li>
            <li>
                <a href="{{ route('students.index') }}"><i class="fa-solid fa-user-graduate me-2"></i> Students</a>
            </li>
            <li>
                <a href="{{ route('teachers.index') }}"><i class="fa-solid fa-chalkboard-user me-2"></i> Teachers</a>
            </li>
            <li>
                <a href="{{ route('staff.index') }}"><i class="fa-solid fa-users me-2"></i> Staff</a>
            </li>
            <li>
                <a href="{{ route('attendance.index') }}"><i class="fa-solid fa-clipboard-user me-2"></i> Attendance</a>
            </li>
            <hr class="dropdown-divider bg-secondary my-3 mx-3">
            <li>
                <a href="#"><i class="fa-solid fa-gear me-2"></i> Settings</a>
            </li>

            @auth
            <li class="mt-3 px-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 fw-bold text-start">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </li>
            @endauth
        </ul>
    </nav>

    <!-- Main Content Area -->
    <div id="content">
        
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow-sm mb-4 px-3">
            <div class="container-fluid justify-content-between">
                <div>
                    @auth
                        <span class="navbar-text fw-semibold text-secondary">
                            Welcome back, <strong>{{ Auth::user()->name }}</strong>
                        </span>
                    @else
                        <span class="navbar-text fw-semibold text-secondary">
                            Welcome to <strong>SchoolSMS</strong>
                        </span>
                    @endauth
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border p-2 me-2">
                        <i class="fa-regular fa-calendar me-1"></i> {{ date('Y-m-d') }}
                    </span>

                    <!-- Auth buttons for Guest Users -->
                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary fw-bold">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Log in
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-sm btn-primary fw-bold">
                                <i class="fa-solid fa-user-plus me-1"></i> Register
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
        </nav>

        <!-- Welcome Banner -->
        <div class="p-4 mb-4 text-white rounded shadow-sm d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
            <div>
                <h2 class="fw-bold m-0">School Management Dashboard</h2>
                <p class="m-0 mt-1 opacity-75">Ku maamul xogta ardayda, macallimiinta, iyo shaqaalaha hab toos ah.</p>
            </div>
            <div class="d-none d-md-block fs-1">
                <i class="fa-solid fa-school text-white-50"></i>
            </div>
        </div>

        <!-- Statistics Cards -->
        <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-chart-pie me-2"></i>General Overview</h5>
        <div class="row g-4 mb-4">
            
            <!-- Students Count -->
            <div class="col-md-4">
                <div class="card stat-card shadow-sm bg-white p-3 border-start border-primary border-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small">Total Students</span>
                            <h2 class="fw-bold text-primary m-0 mt-1">{{ $students_count ?? 0 }}</h2>
                        </div>
                        <div class="bg-primary text-white p-3 rounded-circle">
                            <i class="fa-solid fa-user-graduate fs-4"></i>
                        </div>
                    </div>
                    <hr class="my-2 text-muted">
                    <a href="{{ route('students.index') }}" class="text-primary text-decoration-none small fw-bold">View List <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
            </div>

            <!-- Teachers Count -->
            <div class="col-md-4">
                <div class="card stat-card shadow-sm bg-white p-3 border-start border-success border-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small">Total Teachers</span>
                            <h2 class="fw-bold text-success m-0 mt-1">{{ $teachers_count ?? 0 }}</h2>
                        </div>
                        <div class="bg-success text-white p-3 rounded-circle">
                            <i class="fa-solid fa-chalkboard-user fs-4"></i>
                        </div>
                    </div>
                    <hr class="my-2 text-muted">
                    <a href="{{ route('teachers.index') }}" class="text-success text-decoration-none small fw-bold">View List <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
            </div>

            <!-- Staff Count -->
            <div class="col-md-4">
                <div class="card stat-card shadow-sm bg-white p-3 border-start border-warning border-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small">Total Staff</span>
                            <h2 class="fw-bold text-warning m-0 mt-1">{{ $staff_count ?? 0 }}</h2>
                        </div>
                        <div class="bg-warning text-white p-3 rounded-circle">
                            <i class="fa-solid fa-users fs-4"></i>
                        </div>
                    </div>
                    <hr class="my-2 text-muted">
                    <a href="{{ route('staff.index') }}" class="text-warning text-decoration-none small fw-bold">View List <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
            </div>

        </div>

        <!-- Quick Actions -->
        <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-bolt me-2"></i>Quick Actions</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('students.create') }}" class="btn btn-outline-primary w-100 py-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus-circle me-2"></i> Add New Student
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('teachers.create') }}" class="btn btn-outline-success w-100 py-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus-circle me-2"></i> Add New Teacher
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('staff.create') }}" class="btn btn-outline-warning w-100 py-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus-circle me-2"></i> Add New Staff
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attendance.create') }}" class="btn btn-outline-dark w-100 py-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-clipboard-user me-2"></i> Mark Attendance
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>