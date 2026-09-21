@extends('layouts.app-bs')

@section('title', 'Parent Profile')

@section('content')
<div class="container-fluid p-0" style="max-width: 700px;">
    <div class="mb-4">
        <a href="{{ route('parents.index') }}" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Parents List
        </a>
        <h3 class="fw-bold text-slate-800 m-0 mt-2"><i class="fa-solid fa-id-card text-warning me-2"></i>Parent Profile Details</h3>
    </div>

    <div class="card card-custom overflow-hidden mb-4">
        <div class="bg-warning bg-opacity-10 p-4 border-bottom border-warning border-opacity-20 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3 shadow-sm" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-slate-900 m-0">{{ $parent->name }}</h4>
                    <span class="badge badge-soft-info mt-1">{{ $parent->occupation ?? 'Parent / Guardian' }}</span>
                </div>
            </div>
            <span class="badge badge-soft-secondary">ID #{{ $parent->id }}</span>
        </div>

        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Email Address</label>
                    <div class="fw-semibold text-dark"><i class="fa-regular fa-envelope me-2 text-warning"></i>{{ $parent->email }}</div>
                </div>

                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Phone Contact</label>
                    <div class="fw-semibold text-dark"><i class="fa-solid fa-phone me-2 text-warning"></i>{{ $parent->phone ?? 'N/A' }}</div>
                </div>

                <div class="col-12">
                    <hr class="my-2 opacity-10">
                </div>

                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Occupation / Business</label>
                    <div class="fw-semibold text-dark"><i class="fa-solid fa-briefcase me-2 text-warning"></i>{{ $parent->occupation ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Registration Date</label>
                    <div class="fw-semibold text-dark"><i class="fa-regular fa-calendar-check me-2 text-warning"></i>{{ $parent->created_at ? $parent->created_at->format('M d, Y H:i') : 'N/A' }}</div>
                </div>

                <div class="col-12">
                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Residential Address</label>
                    <div class="fw-semibold text-dark bg-light p-3 rounded-3"><i class="fa-solid fa-location-dot me-2 text-warning"></i>{{ $parent->address ?? 'No address recorded' }}</div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="{{ route('parents.index') }}" class="btn btn-light border px-3 fw-semibold">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to List
                </a>
                <a href="{{ route('parents.edit', $parent->id) }}" class="btn btn-warning text-white px-4 fw-semibold shadow-sm">
                    <i class="fa-solid fa-pen me-1"></i> Edit Details
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
