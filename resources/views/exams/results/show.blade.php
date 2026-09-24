@extends('layouts.app-bs')

@section('title', 'Exam Result Details')

@section('content')
@php
    $percentage = $examResult->total_marks > 0
        ? round(($examResult->marks_obtained / $examResult->total_marks) * 100, 2)
        : 0;
    $grade = match (true) {
        $percentage >= 80 => 'A',
        $percentage >= 70 => 'B',
        $percentage >= 60 => 'C',
        $percentage >= 50 => 'D',
        default => 'F',
    };
    $passed = $percentage >= 50;
@endphp

<div class="container-fluid p-0" style="max-width: 760px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('exams.results', $exam) }}" class="text-decoration-none text-muted small fw-bold no-print">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Results
            </a>
            <h3 class="fw-bold mt-2 mb-0">Exam Result Details</h3>
        </div>
        <div class="d-flex gap-2 no-print">
            <a href="{{ route('exams.results.edit', [$exam, $examResult]) }}" class="btn btn-warning">
                <i class="fa-solid fa-pen me-1"></i> Edit
            </a>
            <button type="button" class="btn btn-light border" onclick="window.print()" title="Print result">
                <i class="fa-solid fa-print"></i>
            </button>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-header bg-primary text-white p-4">
            <h4 class="mb-1">{{ $examResult->student->name }}</h4>
            <div>{{ $exam->name }} · {{ $examResult->subject }}</div>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="text-muted small">Marks Obtained</div>
                    <div class="fs-4 fw-bold">{{ number_format($examResult->marks_obtained, 2) }} / {{ number_format($examResult->total_marks, 2) }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Percentage</div>
                    <div class="fs-4 fw-bold text-primary">{{ number_format($percentage, 2) }}%</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Grade</div>
                    <span class="badge bg-info text-dark fs-6">{{ $grade }}</span>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Status</div>
                    <span class="badge bg-{{ $passed ? 'success' : 'danger' }} fs-6">{{ $passed ? 'Passed' : 'Failed' }}</span>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent text-muted small">
            Exam date: {{ $exam->exam_date }}
        </div>
    </div>
</div>
@endsection
