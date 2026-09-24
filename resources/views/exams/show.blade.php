@extends('layouts.app-bs')

@section('title', 'Exam Details')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold m-0"><i class="fa-solid fa-file-lines text-primary me-2"></i>Exam Details</h3>
                <div>
                    <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
                    <a href="{{ route('exams.results', $exam) }}" class="btn btn-primary me-2"><i class="fa-solid fa-chart-line me-1"></i> View Results</a>
                    <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square me-1"></i> Edit Exam</a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="m-0 fw-bold"><i class="fa-solid fa-graduation-cap me-2"></i>{{ $exam->name }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Subject</label>
                            <span class="fs-5 fw-semibold text-dark">{{ $exam->subject }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Total Marks</label>
                            <span class="badge bg-info text-dark fs-6">{{ $exam->total_marks }} Marks</span>
                        </div>
                        <hr class="my-3 text-muted">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Exam Date</label>
                            <span class="fw-bold text-secondary"><i class="fa-regular fa-calendar me-1"></i> {{ $exam->exam_date }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Start Time</label>
                            <span class="fw-bold text-secondary"><i class="fa-regular fa-clock me-1"></i> {{ $exam->start_time ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light p-3 text-end">
                    <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma xaqiijinaysaa inaad tirtirto imtixaankan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash me-1"></i> Delete Exam
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection