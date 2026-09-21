@extends('layouts.app-bs')

@section('title', 'Managers List')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-slate-800 m-0"><i class="fa-solid fa-user-tie text-info me-2"></i>Managers List</h3>
            <p class="text-muted small m-0 mt-1">Manage system administrator and department manager accounts</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('managers.create') }}" class="btn btn-info text-white fw-semibold shadow-sm px-3">
                <i class="fa-solid fa-plus me-1"></i> Add New Manager
            </a>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Manager Name</th>
                            <th>Email Address</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($managers as $manager)
                            <tr>
                                <td class="fw-bold text-muted">#{{ $manager->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 36px; height: 36px;">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                        <div class="fw-bold text-dark">{{ $manager->name }}</div>
                                    </div>
                                </td>
                                <td class="text-secondary"><i class="fa-regular fa-envelope me-1 text-muted"></i> {{ $manager->email }}</td>
                                <td class="text-secondary"><i class="fa-solid fa-phone me-1 text-muted"></i> {{ $manager->phone ?? 'N/A' }}</td>
                                <td><span class="badge badge-soft-info">{{ $manager->department ?? 'General' }}</span></td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('managers.show', $manager->id) }}" class="btn btn-sm btn-light border text-info" title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('managers.edit', $manager->id) }}" class="btn btn-sm btn-light border text-warning" title="Edit Manager">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('managers.destroy', $manager->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma meel marisaa tirida manager-kan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Manager">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-user-tie fs-1 text-slate-300 d-block mb-3"></i>
                                    <h5>No Managers Found</h5>
                                    <p class="small text-muted mb-3">Click the button below to add your first manager record.</p>
                                    <a href="{{ route('managers.create') }}" class="btn btn-sm btn-info text-white fw-bold">
                                        <i class="fa-solid fa-plus me-1"></i> Add Manager
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($managers->hasPages())
            <div class="card-footer bg-transparent border-0 p-3">
                {{ $managers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection