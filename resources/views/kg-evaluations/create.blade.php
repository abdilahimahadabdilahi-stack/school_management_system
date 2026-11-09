@extends('layouts.app-bs')

@section('title', 'Record KG Progress')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold">Record KG Progress</h4>
            <a href="{{ route('kg-evaluations.index') }}" class="btn btn-sm btn-light">View Reports</a>
        </div>
        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="GET" action="{{ route('kg-evaluations.create') }}" class="row g-2 mb-4">
                <div class="col-md-9">
                    <label for="class_id" class="form-label">Kindergarten Class</label>
                    <select id="class_id" name="class_id" class="form-select" required>
                        <option value="">Select a KG class and section</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" @selected($schoolClass?->id === $class->id)>
                                {{ $class->class_label }} (Section {{ $class->section }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-outline-primary w-100" type="submit">Load Students</button>
                </div>
            </form>

            @if($schoolClass)
                <form method="POST" action="{{ route('kg-evaluations.store') }}">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $schoolClass->id }}">

                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student</label>
                        <select id="student_id" name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">Select student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="report_period" class="form-label">Report Period</label>
                            <input id="report_period" name="report_period" class="form-control @error('report_period') is-invalid @enderror" value="{{ old('report_period') }}" placeholder="e.g. Term 1 2026" required>
                            @error('report_period')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="evaluated_on" class="form-label">Assessment Date</label>
                            <input id="evaluated_on" type="date" name="evaluated_on" class="form-control @error('evaluated_on') is-invalid @enderror" value="{{ old('evaluated_on', today()->toDateString()) }}" required>
                            @error('evaluated_on')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="skill" class="form-label">Skill / Learning Area</label>
                        <input id="skill" name="skill" class="form-control @error('skill') is-invalid @enderror" value="{{ old('skill') }}" placeholder="e.g. Communication, Numeracy, Social Skills" maxlength="100" required>
                        @error('skill')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="rating" class="form-label">Progress Rating</label>
                        <select id="rating" name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                            <option value="">Select rating</option>
                            @foreach($ratings as $rating)
                                <option value="{{ $rating }}" @selected(old('rating') === $rating)>{{ $rating }}</option>
                            @endforeach
                        </select>
                        @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">Teacher Notes (Optional)</label>
                        <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" maxlength="2000">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">Save KG Evaluation</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
