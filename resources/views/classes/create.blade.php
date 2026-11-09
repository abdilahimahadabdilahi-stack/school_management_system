@extends('layouts.app-bs')

@section('title', 'Add New Class')

@section('content')

<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold">Add New Class</h4>
            <a href="{{ route('classes.index') }}" class="btn btn-sm btn-light">Back</a>
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

            <form action="{{ route('classes.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select name="class_type" class="form-select @error('class_type') is-invalid @enderror" required>
                        <option value="primary" @selected(old('class_type', 'primary') === 'primary')>Primary</option>
                        <option value="kindergarten" @selected(old('class_type') === 'kindergarten')>Kindergarten</option>
                    </select>
                    @error('class_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Class Number -->
                <div class="mb-3">
                    <label class="form-label">Class Level (Primary 1-8, KG 1-3)</label>
                    <select name="class_number" class="form-select @error('class_number') is-invalid @enderror" required>
                        <option value="">-- Select Class --</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('class_number') == $i ? 'selected' : '' }}>Level {{ $i }}</option>
                        @endfor
                    </select>
                    @error('class_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Section</label>
                    <input type="text" name="section" class="form-control @error('section') is-invalid @enderror" value="{{ old('section') }}" placeholder="A, B, C..." maxlength="50" required>
                    @error('section')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Class Teacher -->
                <div class="mb-3">
                    <label class="form-label">Class Teacher (Optional)</label>
                    <input type="text" name="class_teacher" class="form-control @error('class_teacher') is-invalid @enderror" value="{{ old('class_teacher') }}" placeholder="E.g. Mr. Ahmed">
                    @error('class_teacher')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Capacity -->
                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', 40) }}" min="1" max="100">
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <fieldset class="mb-4">
                    <legend class="form-label fw-semibold">Subjects for this class <span class="text-muted fw-normal">(optional)</span></legend>
                    <div class="row g-2">
                        @foreach($subjects as $subject)
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" id="subject-{{ $subject->id }}" @checked(in_array($subject->id, old('subject_ids', [])))>
                                    <label class="form-check-label" for="subject-{{ $subject->id }}">{{ $subject->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('subject_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    @error('subject_ids.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </fieldset>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Save Class</button>
            </form>
        </div>
    </div>
</div>

@endsection
