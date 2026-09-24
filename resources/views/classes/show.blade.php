@extends('layouts.app-bs')

@section('title', 'Class Details')

@section('content')

<div class="container-fluid" style="max-width: 1100px;">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold"><i class="fa-solid fa-school me-2"></i>Class: {{ $schoolClass->class_label }}</h4>
            <a href="{{ route('classes.index') }}" class="btn btn-sm btn-light">Back</a>
        </div>
        <div class="card-body p-4">
            <table class="table table-bordered">
                <tr>
                    <th class="bg-light" style="width: 40%;">ID</th>
                    <td>{{ $schoolClass->id }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Class Number</th>
                    <td>{{ $schoolClass->class_number }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Section</th>
                    <td><span class="badge bg-{{ $schoolClass->section == 'A' ? 'primary' : 'success' }}">{{ $schoolClass->section }}</span></td>
                </tr>
                <tr>
                    <th class="bg-light">Class Label</th>
                    <td><strong>{{ $schoolClass->class_label }}</strong></td>
                </tr>
                <tr>
                    <th class="bg-light">Class Teacher</th>
                    <td>{{ $schoolClass->class_teacher ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Capacity</th>
                    <td>{{ $schoolClass->capacity }} students</td>
                </tr>
                <tr>
                    <th class="bg-light">Created At</th>
                    <td>{{ $schoolClass->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            </table>

            <div class="d-flex gap-2 mb-4">
                <a href="{{ route('classes.edit', $schoolClass->id) }}" class="btn btn-warning text-white">
                    <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                </a>
                <form action="{{ route('classes.destroy', $schoolClass->id) }}" method="POST" onsubmit="return confirm('Ma hubtaa inaad tirtirto fasalkan?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash me-1"></i>Delete
                    </button>
                </form>
            </div>

            <h5 class="fw-bold mb-3"><i class="fa-solid fa-users me-2 text-primary"></i>Students in {{ $schoolClass->class_label }}</h5>
            <div class="mb-3">
                <a href="{{ route('students.create', ['class_name' => $schoolClass->class_label, 'section' => $schoolClass->section]) }}" class="btn btn-primary">
                    <i class="fa-solid fa-user-plus me-1"></i>Add Student to {{ $schoolClass->class_label }}
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Email</th>
                            <th>Parent</th>
                            <th>Section</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schoolClass->students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td class="fw-semibold">{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->parent?->name ?? 'N/A' }}</td>
                                <td>{{ $student->section ?? $schoolClass->section }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No students are assigned to this class.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
