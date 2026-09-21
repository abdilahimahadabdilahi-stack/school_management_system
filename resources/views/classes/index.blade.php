<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management - Classes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-school me-2"></i>List of Classes</h2>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2"><i class="fa-solid fa-arrow-left me-1"></i>Dashboard</a>
            <a href="{{ route('classes.create') }}" class="btn btn-primary">+ Add New Class</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        @forelse($classes as $class)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="text-muted small">Class {{ $class->class_number }}</div>
                                <h3 class="fw-bold mb-0">{{ $class->class_label }}</h3>
                            </div>
                            <span class="badge bg-{{ $class->section == 'A' ? 'primary' : 'success' }}">Section {{ $class->section }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-3">
                            <span><i class="fa-solid fa-users me-1"></i>{{ $class->students_count ?? 0 }} students</span>
                            <span>Capacity: {{ $class->capacity }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('classes.show', $class->id) }}" class="btn btn-primary flex-grow-1">
                                <i class="fa-solid fa-arrow-right me-1"></i> Open Class
                            </a>
                            <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-outline-warning" title="Edit class">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">Lama helin wax fasal ah.</div>
        @endforelse
    </div>
</div>

</body>
</html>
