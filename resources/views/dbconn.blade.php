@extends('layouts.app-bs')

@section('title', 'School Database Setup')

@section('content')

<div class="container-fluid p-0">
    <div class="card card-custom mx-auto" style="max-width: 520px;">
        <div class="card-body p-4">
            <h2 class="h4 fw-bold text-center mb-4">School Database Setup</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('school.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="student_name" class="form-label">Magaca Ardayga (Student Name):</label>
                    <input type="text" id="student_name" name="student_name" class="form-control" required placeholder="Geli magaca ardayga">
                </div>

                <div class="mb-3">
                    <label for="class" class="form-label">Fasalka (Class):</label>
                    <input type="text" id="class" name="class" class="form-control" required placeholder="Geli fasalka">
                </div>

                <button type="submit" class="btn btn-success w-100">Kaydi Xogta (Save Data)</button>
            </form>
        </div>
    </div>
</div>
@endsection
