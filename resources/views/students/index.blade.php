@extends('layouts.app-bs')

@section('title', 'Students')

@section('content')

<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h3 class="fw-bold text-slate-800 m-0"><i class="fa-solid fa-user-graduate text-primary me-2"></i>Students List</h3>
        <a href="{{ route('students.create') }}" class="btn btn-primary fw-semibold shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> Add New Student
        </a>
    </div>

    <form method="GET" action="{{ route('students.index') }}" class="row g-2 mb-4">
        <div class="col-md-10">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search students by name, email, class, section, or subject">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Search</button>
            @if(request('search'))
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            @endif
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Email</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Parent</th>
                            <th>Subject</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td class="fw-bold text-muted">#{{ $student->id }}</td>
                                <td class="fw-semibold text-dark">{{ $student->name }}</td>
                                <td>{{ $student->age }}</td>
                                <td class="text-secondary">{{ $student->email }}</td>
                                <td>{{ $student->class_name }}</td>
                                <td>{{ $student->section ?? 'N/A' }}</td>
                                <td>{{ $student->parent?->name ?? 'N/A' }}</td>
                                <td>{{ $student->subject ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-light border text-info" title="View Student">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-light border text-warning" title="Edit Student">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma weydiinaysaa inaad tirtirto ardaygan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Student">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">Lama helin wax arday ah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection