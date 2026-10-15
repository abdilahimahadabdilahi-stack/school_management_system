@extends('layouts.app-bs')

@section('title', 'Unpaid Students')

@section('content')
    <div class="container-fluid px-4 py-4">
        @php
            $studentPageContext = ['return_to' => 'unpaid'];
            if ($search !== '') {
                $studentPageContext['search'] = $search;
            }
            if ($paymentDate !== '') {
                $studentPageContext['payment_date'] = $paymentDate;
            }
        @endphp
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <div class="text-uppercase text-muted small fw-bold mb-1">Reports</div>
                <h2 class="mb-0 fw-bold">Ardayda aan Lacagta Bixin - Warbixin</h2>
            </div>

            <div class="d-flex flex-wrap gap-2 no-print">
                <a href="{{ route('students.create', $studentPageContext) }}" class="btn btn-success px-4">
                    <i class="fa-solid fa-plus me-2"></i>Add Student
                </a>
                <button type="button" class="btn btn-primary px-4" onclick="window.print()">
                    <i class="fa-solid fa-print me-2"></i>Print List / Daabac Liiska
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success no-print">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('students.unpaid') }}" class="row g-2 mb-4 no-print">
            <div class="col-md-6">
                <label for="unpaid-search" class="visually-hidden">Search unpaid students</label>
                <input id="unpaid-search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Search by student ID, name, class, status, or phone number">
            </div>
            <div class="col-md-3">
                <label for="payment-date" class="visually-hidden">Filter by payment date</label>
                <input id="payment-date" type="date" name="payment_date" value="{{ $paymentDate }}" class="form-control" aria-label="Filter by payment date">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>Search
                </button>
                @if($search !== '' || $paymentDate !== '')
                    <a href="{{ route('students.unpaid') }}" class="btn btn-outline-secondary w-100">Clear</a>
                @endif
            </div>
        </form>
        @error('payment_date')
            <div class="alert alert-danger no-print">{{ $message }}</div>
        @enderror

        <div class="card card-custom shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Class / Grade</th>
                            <th>Fee Amount Due</th>
                            <th>Payment Status</th>
                            <th>Contact / Phone Number</th>
                            <th class="text-end no-print">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student['id'] }}</td>
                                <td class="fw-semibold">{{ $student['name'] }}</td>
                                <td>{{ $student['class_name'] }}</td>
                                <td class="fw-semibold text-danger">$ {{ $student['fee_amount_due'] }}</td>
                                <td>
                                    @php $statusClass = match ($student['status']) { 'Partial' => 'bg-warning text-dark', 'Unpaid' => 'bg-danger text-white', default => 'bg-secondary text-white' }; @endphp
                                    <span class="badge {{ $statusClass }} px-2 py-2 rounded-pill">{{ $student['status'] }}</span>
                                </td>
                                <td>{{ $student['phone'] }}</td>
                                <td class="text-end no-print">
                                    <div class="btn-group" role="group" aria-label="Actions for {{ $student['name'] }}">
                                        <a href="{{ route('students.edit', ['student' => $student['id']] + $studentPageContext) }}" class="btn btn-sm btn-light border text-warning" title="Edit Student">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('students.destroy', $student['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma weydiinaysaa inaad tirtirto ardaygan?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="return_to" value="unpaid">
                                            @if($search !== '')
                                                <input type="hidden" name="search" value="{{ $search }}">
                                            @endif
                                            @if($paymentDate !== '')
                                                <input type="hidden" name="payment_date" value="{{ $paymentDate }}">
                                            @endif
                                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Student">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-circle-check fa-2x mb-3 d-block text-success"></i>
                                    @if($search !== '' || $paymentDate !== '')
                                        No unpaid students match your search.
                                    @else
                                        No unpaid students found.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 1.2cm;
            }

            body {
                background: #fff !important;
                color: #000 !important;
                font-size: 12pt;
            }

            #sidebar,
            .navbar-main,
            footer,
            .no-print,
            .btn,
            .dropdown,
            .alert,
            .pagination,
            .d-print-none {
                display: none !important;
            }

            #wrapper,
            #content,
            main,
            .container-fluid,
            .card {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            .table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 11pt;
            }

            .table th,
            .table td {
                border: 1px solid #000 !important;
                padding: 8px !important;
                color: #000 !important;
                background: #fff !important;
            }

            .badge {
                border: 1px solid #000 !important;
                color: #000 !important;
                background: #fff !important;
            }

            .card {
                border: 1px solid #ddd !important;
            }

            h2 {
                font-size: 20pt !important;
                margin-bottom: 12px !important;
            }
        }
    </style>
@endsection
