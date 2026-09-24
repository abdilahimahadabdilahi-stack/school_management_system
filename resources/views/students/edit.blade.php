@extends('layouts.app-bs')

@section('title', 'Edit Student')

@section('content')

<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold">Edit Student Info</h4>
            <a href="{{ route('students.index') }}" class="btn btn-sm btn-light">Back</a>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('students.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $student->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" value="{{ old('age', $student->age) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Class</label>
                    <input type="text" name="class_name" class="form-control" value="{{ old('class_name', $student->class_name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Section</label>
                    <input type="text" name="section" class="form-control" value="{{ old('section', $student->section) }}">
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

                <!-- Input-ka Subject oo la soo kordhiyay -->
                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject', $student->subject) }}" placeholder="E.g. Mathematics">
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold text-white py-2">Update Student</button>
            </form>
        </div>
    </div>
</div>

@endsection