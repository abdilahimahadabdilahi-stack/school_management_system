@extends('layouts.app-bs')

@section('title', 'KG Progress Reports')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Kindergarten Progress Reports</h2>
            <p class="text-muted mb-0">Skill-based progress alongside the primary exam marksheets.</p>
        </div>
        <a href="{{ route('kg-evaluations.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Record Evaluation
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="GET" action="{{ route('kg-evaluations.index') }}" class="row g-3 mb-4">
        <div class="col-md-5">
            <label for="class_id" class="form-label">Kindergarten Class</label>
            <select id="class_id" name="class_id" class="form-select">
                <option value="">Select a KG class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected($schoolClass?->id === $class->id)>
                        {{ $class->class_label }} (Section {{ $class->section }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5">
            <label for="report_period" class="form-label">Report Period</label>
            <input id="report_period" name="report_period" class="form-control" value="{{ $reportPeriod }}" placeholder="e.g. Term 1 2026">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-outline-primary w-100">View Report</button>
        </div>
    </form>

    @if($schoolClass)
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                {{ $schoolClass->class_label }} — {{ $reportPeriod !== '' ? $reportPeriod : 'All report periods' }}
            </div>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Skill / Learning Area</th>
                            <th>Rating</th>
                            <th>Teacher Notes</th>
                            <th>Assessment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $evaluation)
                            <tr>
                                <td class="fw-semibold">{{ $evaluation->student->name }}</td>
                                <td>{{ $evaluation->skill }}</td>
                                <td>
                                    <span class="badge bg-{{ $evaluation->rating === 'Needs Improvement' ? 'warning text-dark' : ($evaluation->rating === 'Satisfactory' ? 'success' : 'info text-dark') }}">
                                        {{ $evaluation->rating }}
                                    </span>
                                </td>
                                <td>{{ $evaluation->notes ?: '—' }}</td>
                                <td>{{ $evaluation->evaluated_on->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No evaluations found for this class and period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
