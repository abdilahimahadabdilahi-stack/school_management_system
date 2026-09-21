@extends('layouts.app-bs')

@section('title', 'Reports')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-4"><h3 class="fw-bold mb-1">Reports</h3><p class="text-muted mb-0">Browse a report for every system table.</p></div>
    <div class="card card-custom"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Report</th><th>Source table</th><th class="text-end">Action</th></tr></thead><tbody>@foreach($reports as $slug => $report)<tr><td class="fw-semibold">{{ $report['label'] }}</td><td class="text-muted">{{ $slug }}</td><td class="text-end"><a href="{{ route('reports.show', $slug) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-chart-line me-1"></i> View report</a></td></tr>@endforeach</tbody></table></div></div>
</div>
@endsection
