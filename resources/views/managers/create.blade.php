@extends('layouts.app-bs')

@section('title', 'Add New Manager')

@section('content')
<div class="container-fluid p-0" style="max-width: 700px;">
    <div class="mb-4">
        <a href="{{ route('managers.index') }}" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Managers List
        </a>
        <h3 class="fw-bold text-slate-800 m-0 mt-2"><i class="fa-solid fa-user-plus text-info me-2"></i>Add New Manager</h3>
    </div>

    <div class="card card-custom p-4">
        <form action="{{ route('managers.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold text-slate-700">Full Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                    <input type="text" name="name" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter manager name" required>
                </div>
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-slate-700">Email Address <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="manager@school.com" required>
                </div>
                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-slate-700">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-phone text-muted"></i></span>
                        <input type="text" name="phone" class="form-control border-start-0 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+252 ...">
                    </div>
                    @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-slate-700">Department</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-building text-muted"></i></span>
                        <input type="text" name="department" class="form-control border-start-0 @error('department') is-invalid @enderror" value="{{ old('department') }}" placeholder="e.g. HR, IT, Academic">
                    </div>
                    @error('department') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-2">
                <a href="{{ route('managers.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                <button type="submit" class="btn btn-info text-white px-4 fw-semibold shadow-sm">
                    <i class="fa-solid fa-save me-1"></i> Save Manager
                </button>
            </div>
        </form>
    </div>
</div>
@endsection