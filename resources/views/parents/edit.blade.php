@extends('layouts.app-bs')

@section('title', 'Edit Parent')

@section('content')
<div class="container-fluid p-0" style="max-width: 700px;">
    <div class="mb-4">
        <a href="{{ route('parents.index') }}" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Parents List
        </a>
        <h3 class="fw-bold text-slate-800 m-0 mt-2"><i class="fa-solid fa-pen-to-square text-warning me-2"></i>Edit Parent Details</h3>
    </div>

    <div class="card card-custom p-4">
        <form action="{{ route('parents.update', $parent->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold text-slate-700">Full Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                    <input type="text" name="name" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name', $parent->name) }}" required>
                </div>
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-slate-700">Email Address <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email', $parent->email) }}" required>
                </div>
                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-slate-700">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-phone text-muted"></i></span>
                        <input type="text" name="phone" class="form-control border-start-0 @error('phone') is-invalid @enderror" value="{{ old('phone', $parent->phone) }}">
                    </div>
                    @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-slate-700">Occupation</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-briefcase text-muted"></i></span>
                        <input type="text" name="occupation" class="form-control border-start-0 @error('occupation') is-invalid @enderror" value="{{ old('occupation', $parent->occupation) }}">
                    </div>
                    @error('occupation') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-slate-700">Residential Address</label>
                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $parent->address) }}</textarea>
                @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 pt-2">
                <a href="{{ route('parents.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                <button type="submit" class="btn btn-warning text-white px-4 fw-semibold shadow-sm">
                    <i class="fa-solid fa-save me-1"></i> Update Parent
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
