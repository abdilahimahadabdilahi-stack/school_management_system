@extends('layouts.app-bs')

@section('title', $exam->name.' Results')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <a href="{{ route('exams.show', $exam) }}" class="text-decoration-none text-muted small fw-bold">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Exam
            </a>
            <h3 class="fw-bold text-slate-800 m-0 mt-2">
                <i class="fa-solid fa-chart-line text-primary me-2"></i>{{ $exam->name }} Results
            </h3>
            <p class="text-muted small m-0 mt-1">{{ $exam->subject }} · {{ $exam->exam_date }} · Pass threshold: {{ $passThreshold }}%</p>
        </div>
        <button type="button" class="btn btn-primary no-print" data-bs-toggle="modal" data-bs-target="#recordResultModal">
            <i class="fa-solid fa-plus me-1"></i> Record Result
        </button>
    </div>

    <form method="GET" action="{{ route('exams.results', $exam) }}" class="row g-2 mb-4 no-print">
        <div class="col-md-10">
            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Search student by ID or name">
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-search me-1"></i> Search</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-custom border-start border-4 border-success h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Passed</div>
                    <div class="fs-2 fw-bold text-success">{{ $passedCount }}</div>
                    <div class="small text-muted">{{ $studentSummaries->count() }} students with results</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom border-start border-4 border-danger h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Failed</div>
                    <div class="fs-2 fw-bold text-danger">{{ $failedCount }}</div>
                    <div class="small text-muted">Below {{ $passThreshold }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom border-start border-4 border-primary h-100">
                <div class="card-body">
                    <div class="text-muted small">Class Average</div>
                    <div class="fs-2 fw-bold text-primary">{{ number_format($classAverage, 2) }}%</div>
                    <div class="small text-muted">Across all recorded students</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-3 no-print">
        @foreach(['all' => 'All Students', 'passed' => 'Passed', 'failed' => 'Failed', 'average' => 'Average / Intermediate'] as $value => $label)
            <a href="{{ route('exams.results', [$exam, 'status' => $value, 'search' => $search]) }}" class="btn {{ ($filter ?: 'all') === $value ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="card card-custom">
        <div class="card-header bg-transparent border-0 p-4 pb-2">
            <h5 class="fw-bold mb-0">Student Subject Breakdown</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Subject</th>
                        <th>Marks Obtained</th>
                        <th>Total Marks</th>
                        <th>Overall %</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th class="text-end no-print">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($filteredSummaries as $summary)
                        @foreach($summary['results'] as $result)
                            <tr>
                                <td class="fw-semibold">{{ $summary['student']->name }}</td>
                                <td>{{ $result['subject']->name }}</td>
                                <td>{{ number_format($result['marks_obtained'], 2) }}</td>
                                <td>{{ number_format($result['total_marks'], 2) }}</td>
                                <td>
                                    <span class="fw-bold">{{ number_format($summary['percentage'], 2) }}%</span>
                                    <div class="small text-muted">{{ number_format($summary['total_obtained'], 2) }} / {{ number_format($summary['total_marks'], 2) }}</div>
                                </td>
                                <td><span class="badge bg-info text-dark">{{ $summary['grade'] }}</span></td>
                                <td>
                                    <span class="badge bg-{{ $summary['status'] === 'Passed' ? 'success' : 'danger' }}">{{ $summary['status'] }}</span>
                                    @if($summary['band'] === 'Average')
                                        <span class="badge bg-warning text-dark">Average</span>
                                    @endif
                                </td>
                                <td class="text-end no-print">
                                    @if($result['record'])
                                        <a href="{{ route('exams.results.show', [$exam, $result['record']]) }}" class="btn btn-sm btn-light border text-info" title="View result">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('exams.results.edit', [$exam, $result['record']]) }}" class="btn btn-sm btn-light border text-warning" title="Edit result">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('exams.results.destroy', [$exam, $result['record']]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this exam result?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete result">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">No results match this filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade no-print" id="recordResultModal" tabindex="-1" aria-labelledby="recordResultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recordResultModalLabel">Record Student Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('exams.results.store', $exam) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student</label>
                        <select id="student_id" name="student_id" class="form-select" required>
                            <option value="">Select student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Subject</label>
                        <select id="subject_id" name="subject_id" class="form-select" required>
                            <option value="">Select subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="marks_obtained" class="form-label">Marks Obtained</label>
                            <input id="marks_obtained" name="marks_obtained" type="number" step="0.01" min="0" class="form-control" value="{{ old('marks_obtained') }}" required>
                        </div>
                        <div class="col-6">
                            <label for="total_marks" class="form-label">Total Marks</label>
                            <input id="total_marks" name="total_marks" type="number" step="0.01" min="1" class="form-control" value="{{ old('total_marks', $exam->total_marks) }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Result</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
