@extends('layouts.app-bs')

@section('title', 'Create Report')

@section('content')

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-plus-circle text-primary me-2"></i>Create New Report</h2>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Reports
        </a>
    </div>

    <div class="card shadow-sm p-4">
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="mb-3">
                <label for="title" class="form-label fw-bold">Report Title</label>
                <input type="text" class="form-control" id="title" placeholder="Enter report title" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label fw-bold">Report Type</label>
                <select class="form-select" id="type">
                    <option value="students">Student Report</option>
                    <option value="teachers">Teacher Report</option>
                    <option value="attendance">Attendance Report</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description</label>
                <textarea class="form-control" id="description" rows="4" placeholder="Write report details..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary fw-bold">
                <i class="fa-solid fa-paper-plane me-1"></i> Submit Report
            </button>
        </form>
    </div>
</div>

@endsection