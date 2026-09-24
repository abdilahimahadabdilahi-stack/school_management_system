@extends('layouts.app-bs')

@section('title', 'Edit Attendance')

@section('content')

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fa-solid fa-pen-to-square me-2 text-warning"></i>Edit Attendance Record</h4>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
    </div>

    <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-bold">Student Name:</label>
            <input type="text" class="form-control" value="{{ $attendance->student->name ?? 'N/A' }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Date:</label>
            <input type="text" class="form-control" value="{{ $attendance->attendance_date }}" disabled>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Status:</label>
            <select name="status" class="form-select">
                <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Present</option>
                <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Late</option>
                <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Absent</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning w-100 fw-bold"><i class="fa-solid fa-arrows-rotate me-1"></i> Update Attendance</button>
    </form>
</div>

@endsection