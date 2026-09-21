@extends('layouts.app-bs')

@section('title', $report['label'].' Report')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4"><div><a href="{{ route('reports.index') }}" class="text-decoration-none text-muted small fw-bold"><i class="fa-solid fa-arrow-left me-1"></i> All Reports</a><h3 class="fw-bold mt-2 mb-0">{{ $report['label'] }} Report</h3></div><form class="d-flex" method="GET"><input name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Search by name"><button class="btn btn-outline-primary"><i class="fa-solid fa-search"></i></button></form></div>
    <div class="card card-custom"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr>@foreach($report['columns'] as $column)<th>{{ str_replace('_', ' ', ucfirst($column)) }}</th>@endforeach</tr></thead><tbody>@forelse($records as $record)<tr>@foreach($report['columns'] as $column)<td>{{ is_object($record->{$column}) ? $record->{$column}->format('M d, Y H:i') : ($record->{$column} ?? 'N/A') }}</td>@endforeach</tr>@empty<tr><td colspan="{{ count($report['columns']) }}" class="text-center text-muted py-5">No records found.</td></tr>@endforelse</tbody></table></div><div class="p-3">{{ $records->links() }}</div></div>
</div>
@endsection
