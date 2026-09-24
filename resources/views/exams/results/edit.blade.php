@extends('layouts.app-bs')

@section('title', 'Edit Exam Result')

@section('content')
<div class="container-fluid p-0" style="max-width: 760px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('exams.results', $exam) }}" class="text-decoration-none text-muted small fw-bold">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Results
            </a>
            <h3 class="fw-bold mt-2 mb-0">Edit Exam Result</h3>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body p-4">
            <div class="alert alert-light border mb-4">
                <strong>{{ $examResult->student->name }}</strong>
                <span class="text-muted"> · {{ $exam->name }}</span>
            </div>

            <form method="POST" action="{{ route('exams.results.update', [$exam, $examResult]) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="student_id" class="form-label">Student</label>
                    <select id="student_id" name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" @selected(old('student_id', $examResult->student_id) == $student->id)>{{ $student->name }}</option>
                        @endforeach
                    </select>
                    @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select id="subject_id" name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('subject_id', $examResult->subject_id) == $subject->id)>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="marks_obtained" class="form-label">Marks Obtained</label>
                        <input id="marks_obtained" name="marks_obtained" type="number" step="0.01" min="0" class="form-control @error('marks_obtained') is-invalid @enderror" value="{{ old('marks_obtained', $examResult->marks_obtained) }}" required>
                        @error('marks_obtained') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="total_marks" class="form-label">Total Marks</label>
                        <input id="total_marks" name="total_marks" type="number" step="0.01" min="1" class="form-control @error('total_marks') is-invalid @enderror" value="{{ old('total_marks', $examResult->total_marks) }}" required>
                        @error('total_marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('exams.results', $exam) }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Update Result</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
