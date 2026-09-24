@extends('layouts.app-bs')

@section('title', 'Dashboard Overview')

@section('content')
<div class="container-fluid p-0">
    
    <!-- Hero Banner -->
    <div class="p-4 p-md-5 mb-4 text-white rounded-4 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border: 1px solid rgba(255,255,255,0.08);">
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col-lg-8">
                <span class="badge bg-primary bg-opacity-20 text-primary-light px-3 py-2 rounded-pill fw-semibold mb-3 border border-primary border-opacity-20" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-shield-halved me-1"></i> Alhuda Primary and Intermediate School
                </span>
                <h1 class="display-6 fw-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
                <div class="display-1 text-primary opacity-25 me-3">
                    <i class="fa-solid fa-school-flag"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Students Card -->
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card stat-card p-3 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Students</span>
                        <h2 class="fw-bold text-slate-900 m-0 mt-1">{{ $students_count ?? 0 }}</h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                        <i class="fa-solid fa-user-graduate fs-4"></i>
                    </div>
                </div>
                <hr class="my-2 opacity-10">
                <a href="{{ route('students.index') }}" class="text-primary text-decoration-none small fw-semibold d-flex align-items-center justify-content-between">
                    <span>Manage Students</span> <i class="fa-solid fa-arrow-right fs-6"></i>
                </a>
            </div>
        </div>

        <!-- Teachers Card -->
        @auth
            @if(in_array(Auth::user()->role, ['admin', 'manager']))
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="card stat-card p-3 border-start border-4 border-success">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold">Teachers</span>
                                <h2 class="fw-bold text-slate-900 m-0 mt-1">{{ $teachers_count ?? 0 }}</h2>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success p-3 rounded-3">
                                <i class="fa-solid fa-chalkboard-user fs-4"></i>
                            </div>
                        </div>
                        <hr class="my-2 opacity-10">
                        <a href="{{ route('teachers.index') }}" class="text-success text-decoration-none small fw-semibold d-flex align-items-center justify-content-between">
                            <span>Manage Teachers</span> <i class="fa-solid fa-arrow-right fs-6"></i>
                        </a>
                    </div>
                </div>

                <!-- Managers Card -->
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="card stat-card p-3 border-start border-4 border-info">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold">Managers</span>
                                <h2 class="fw-bold text-slate-900 m-0 mt-1">{{ $managers_count ?? 0 }}</h2>
                            </div>
                            <div class="bg-info bg-opacity-10 text-info p-3 rounded-3">
                                <i class="fa-solid fa-user-tie fs-4"></i>
                            </div>
                        </div>
                        <hr class="my-2 opacity-10">
                        <a href="{{ route('managers.index') }}" class="text-info text-decoration-none small fw-semibold d-flex align-items-center justify-content-between">
                            <span>Manage Managers</span> <i class="fa-solid fa-arrow-right fs-6"></i>
                        </a>
                    </div>
                </div>

                <!-- Parents Card -->
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="card stat-card p-3 border-start border-4 border-warning">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold">Parents</span>
                                <h2 class="fw-bold text-slate-900 m-0 mt-1">{{ $parents_count ?? 0 }}</h2>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3">
                                <i class="fa-solid fa-people-roof fs-4"></i>
                            </div>
                        </div>
                        <hr class="my-2 opacity-10">
                        <a href="{{ route('parents.index') }}" class="text-warning text-decoration-none small fw-semibold d-flex align-items-center justify-content-between">
                            <span>Manage Parents</span> <i class="fa-solid fa-arrow-right fs-6"></i>
                        </a>
                    </div>
                </div>
            @endif
        @endauth

        <!-- Classes Card -->
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card stat-card p-3 border-start border-4 border-secondary">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Classes</span>
                        <h2 class="fw-bold text-slate-900 m-0 mt-1">{{ $classes_count ?? 0 }}</h2>
                    </div>
                    <div class="bg-secondary bg-opacity-10 text-secondary p-3 rounded-3">
                        <i class="fa-solid fa-school fs-4"></i>
                    </div>
                </div>
                <hr class="my-2 opacity-10">
                <a href="{{ route('classes.index') }}" class="text-secondary text-decoration-none small fw-semibold d-flex align-items-center justify-content-between">
                    <span>Manage Classes</span> <i class="fa-solid fa-arrow-right fs-6"></i>
                </a>
            </div>
        </div>

        <!-- Exams Card -->
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card stat-card p-3 border-start border-4 border-danger">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Exams</span>
                        <h2 class="fw-bold text-slate-900 m-0 mt-1">{{ $exams_count ?? 0 }}</h2>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3">
                        <i class="fa-solid fa-file-pen fs-4"></i>
                    </div>
                </div>
                <hr class="my-2 opacity-10">
                <a href="{{ route('exams.index') }}" class="text-danger text-decoration-none small fw-semibold d-flex align-items-center justify-content-between">
                    <span>View Exams &amp; Results</span> <i class="fa-solid fa-arrow-right fs-6"></i>
                </a>
            </div>
        </div>

        <!-- Payments Card -->
        @auth
            @if(in_array(Auth::user()->role, ['admin', 'manager']))
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="card stat-card p-3 border-start border-4 border-dark">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold">Payments</span>
                                <h2 class="fw-bold text-slate-900 m-0 mt-1">{{ $payments_count }}</h2>
                            </div>
                            <div class="bg-dark bg-opacity-10 text-dark p-3 rounded-3">
                                <i class="fa-solid fa-money-bill-wave fs-4"></i>
                            </div>
                        </div>
                        <hr class="my-2 opacity-10">
                        <a href="{{ route('payments.index') }}" class="text-dark text-decoration-none small fw-semibold d-flex align-items-center justify-content-between">
                            <span>View Payments</span> <i class="fa-solid fa-arrow-right fs-6"></i>
                        </a>
                    </div>
                </div>
            @endif
        @endauth

        <!-- Staff Card -->
        @auth
            @if(in_array(Auth::user()->role, ['admin', 'manager']))
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="card stat-card p-3 border-start border-4 border-primary">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-semibold">Total Staff</span>
                                <h2 class="fw-bold text-slate-900 m-0 mt-1">{{ $staff_count ?? 0 }}</h2>
                            </div>
                            <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                                <i class="fa-solid fa-users fs-4"></i>
                            </div>
                        </div>
                        <hr class="my-2 opacity-10">
                        <a href="{{ route('staff.index') }}" class="text-primary text-decoration-none small fw-semibold d-flex align-items-center justify-content-between">
                            <span>Manage Staff</span> <i class="fa-solid fa-arrow-right fs-6"></i>
                        </a>
                    </div>
                </div>
            @endif
        @endauth
    </div>

    <!-- Quick Actions & Security Activity Log -->
    <div class="row g-4">
        <!-- Quick Actions Panel -->
        <div class="col-lg-5">
            <div class="card card-custom h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-slate-800 m-0">
                        <i class="fa-solid fa-bolt text-warning me-2"></i>Quick Actions
                    </h5>
                    <p class="text-muted small m-0 mt-1">Direct access shortcuts to add records</p>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        @auth
                            @if(in_array(Auth::user()->role, ['admin', 'manager']))
                                <a href="{{ route('managers.create') }}" class="btn btn-outline-info text-start p-3 rounded-3 d-flex align-items-center">
                                    <i class="fa-solid fa-user-tie fs-5 me-3"></i>
                                    <div>
                                        <div class="fw-bold">Add New Manager</div>
                                        <small class="text-muted">Register a management member</small>
                                    </div>
                                </a>

                                <a href="{{ route('parents.create') }}" class="btn btn-outline-warning text-start p-3 rounded-3 d-flex align-items-center">
                                    <i class="fa-solid fa-people-roof fs-5 me-3"></i>
                                    <div>
                                        <div class="fw-bold">Add New Parent</div>
                                        <small class="text-muted">Register a parent/guardian</small>
                                    </div>
                                </a>

                                <a href="{{ route('teachers.create') }}" class="btn btn-outline-success text-start p-3 rounded-3 d-flex align-items-center">
                                    <i class="fa-solid fa-chalkboard-user fs-5 me-3"></i>
                                    <div>
                                        <div class="fw-bold">Add New Teacher</div>
                                        <small class="text-muted">Register a teacher</small>
                                    </div>
                                </a>
                            @endif
                        @endauth

                        <a href="{{ route('students.create') }}" class="btn btn-outline-primary text-start p-3 rounded-3 d-flex align-items-center">
                            <i class="fa-solid fa-user-plus fs-5 me-3"></i>
                            <div>
                                <div class="fw-bold">Add New Student</div>
                                <small class="text-muted">Enroll a new student</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection