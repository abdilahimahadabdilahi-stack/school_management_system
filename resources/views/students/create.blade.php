@extends('layouts.app-bs')

@section('title', 'Add New Student')

@section('content')

<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold">Add New Student</h4>
            @php
                $returnToUnpaid = old('return_to', request('return_to')) === 'unpaid';
                $returnSearch = old('search', request('search', ''));
            @endphp
            <a href="{{ $returnToUnpaid ? route('students.unpaid', ['search' => $returnSearch]) : route('students.index') }}" class="btn btn-sm btn-light">Back</a>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <input type="hidden" name="subject_selection_present" value="1">
                @if($returnToUnpaid)
                    <input type="hidden" name="return_to" value="unpaid">
                    <input type="hidden" name="search" value="{{ $returnSearch }}">
                    <input type="hidden" name="payment_date" value="{{ old('payment_date', request('payment_date', '')) }}">
                @endif
                
                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Age -->
                <div class="mb-3">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" value="{{ old('age') }}" required>
                    @error('age')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Class</label>
                    <select name="school_class_id" class="form-select @error('school_class_id') is-invalid @enderror" required>
                        <option value="">-- Select Class and Section --</option>
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
                            <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name }} ({{ $parent->email }})</option>
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
                                            @checked(in_array($subjectName, (array) old('subjects', []), true))
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

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Save Student</button>
            </form>
        </div>
    </div>
</div>

@endsection