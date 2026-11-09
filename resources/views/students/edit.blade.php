@extends('layouts.app-bs')

@section('title', 'Edit Student')

@section('content')

<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold">Edit Student Info</h4>
            @php
                $returnToUnpaid = old('return_to', request('return_to')) === 'unpaid';
                $returnSearch = old('search', request('search', ''));
            @endphp
            <a href="{{ $returnToUnpaid ? route('students.unpaid', ['search' => $returnSearch]) : route('students.index') }}" class="btn btn-sm btn-light">Back</a>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('students.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="subject_selection_present" value="1">
                @if($returnToUnpaid)
                    <input type="hidden" name="return_to" value="unpaid">
                    <input type="hidden" name="search" value="{{ $returnSearch }}">
                    <input type="hidden" name="payment_date" value="{{ old('payment_date', request('payment_date', '')) }}">
                @endif

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $student->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" value="{{ old('age', $student->age) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Class</label>
                    <select name="school_class_id" class="form-select @error('school_class_id') is-invalid @enderror" required>
                        <option value="">-- Select Class and Section --</option>
                        @php
                            $selectedClassId = $student->school_class_id
                                ?? $classes->first(fn ($availableClass) => strcasecmp($availableClass->class_label, $student->class_name) === 0
                                    && strcasecmp($availableClass->section, (string) $student->section) === 0)?->id;
                        @endphp
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" @selected(old('school_class_id', $selectedClassId) == $class->id)>
                                {{ $class->class_label }} (Section {{ $class->section }})
                            </option>
                        @endforeach
                    </select>
                    @error('school_class_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Parent</label>
                    <select name="parent_id" class="form-select">
                        <option value="">No parent assigned</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id', $student->parent_id) == $parent->id)>{{ $parent->name }} ({{ $parent->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <fieldset>
                        <legend class="form-label">Subjects <span class="text-muted fw-normal">(optional)</span></legend>
                        <div class="row">
                            @foreach($subjects as $subjectSlug => $subjectName)
                                <div class="col-sm-6">
                                    <div class="form-check mb-2">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="subjects[]"
                                            id="subject-{{ $subjectSlug }}"
                                            value="{{ $subjectName }}"
                                            @checked(in_array($subjectName, (array) old('subjects', $selectedSubjectNames), true))
                                        >
                                        <label class="form-check-label" for="subject-{{ $subjectSlug }}">{{ $subjectName }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </fieldset>
                    @foreach($errors->getMessages() as $key => $messages)
                        @if($key === 'subjects' || str_starts_with($key, 'subjects.'))
                            <div class="text-danger small">{{ $messages[0] }}</div>
                        @endif
                    @endforeach
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold text-white py-2">Update Student</button>
            </form>
        </div>
    </div>
</div>

@endsection