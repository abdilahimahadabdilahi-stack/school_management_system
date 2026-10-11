@extends('layouts.app-bs')

@section('title', 'Unpaid Students')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <div class="text-uppercase text-muted small fw-bold mb-1">Reports</div>
                <h2 class="mb-0 fw-bold">Ardayda aan Lacagta Bixin - Warbixin</h2>
            </div>

            <button type="button" class="btn btn-primary px-4 no-print" onclick="window.print()">
                <i class="fa-solid fa-print me-2"></i>Print List / Daabac Liiska
            </button>
        </div>

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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-circle-check fa-2x mb-3 d-block text-success"></i>
                                    No unpaid students found.
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
