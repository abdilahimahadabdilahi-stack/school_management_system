@extends('layouts.app-bs')

@section('title', 'Edit Report')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-pen-to-square text-warning me-2"></i>Edit Report #{{ $id }}</h2>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
    </div>

    <div class="card shadow-sm p-4">
        <form action="{{ route('reports.update', $id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Report Title</label>
                <input type="text" class="form-control" value="Sample Report #{{ $id }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Report Type</label>
                <select class="form-select">
                    <option value="students" selected>Student Report</option>
                    <option value="teachers">Teacher Report</option>
                    <option value="attendance">Attendance Report</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea class="form-control" rows="4">Xogta warbixinta ee la doonayo in lagu sameeyo wax ka beddelka...</textarea>
            </div>

            <button type="submit" class="btn btn-warning fw-bold"><i class="fa-solid fa-arrows-rotate me-1"></i> Update Report</button>
        </form>
    </div>
</div>
@endsection