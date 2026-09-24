@extends('layouts.app-bs')

@section('title', 'Edit Staff')

@section('content')

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Staff Details</h2>
        <a href="{{ route('staff.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('staff.update', $staff->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $staff->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $staff->phone) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role / Position</label>
            <input type="text" name="role" class="form-control" value="{{ old('role', $staff->role) }}" required>
        </div>

        <button type="submit" class="btn btn-warning text-white w-100">Update Staff</button>
    </form>
</div>

@endsection