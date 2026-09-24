@extends('layouts.app-bs')

@section('title', 'Payment Details')

@push('styles')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #ffffff !important;
                padding: 0 !important;
            }

            .container {
                max-width: none !important;
            }

            .card {
                border: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush

@section('content')

<div class="container" style="max-width: 700px;">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold"><i class="fa-solid fa-receipt me-2"></i>Payment: {{ $payment->receipt_number }}</h4>
            <div class="no-print d-flex gap-2">
                <button type="button" class="btn btn-sm btn-light" onclick="window.print()">
                    <i class="fa-solid fa-print me-1"></i>Print
                </button>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-light">Back</a>
            </div>
        </div>
        <div class="card-body p-4">
            <table class="table table-bordered">
                <tr>
                    <th class="bg-light" style="width: 40%;">Receipt Number</th>
                    <td><code>{{ $payment->receipt_number }}</code></td>
                </tr>
                <tr>
                    <th class="bg-light">Student</th>
                    <td>{{ $payment->student->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Class</th>
                    <td>{{ $payment->schoolClass->class_label ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Total Fee</th>
                    <td>${{ number_format($payment->total_fee, 2) }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Amount Paid</th>
                    <td class="text-success fw-bold">${{ number_format($payment->amount, 2) }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Balance</th>
                    <td class="text-danger fw-bold">${{ number_format($payment->balance, 2) }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Status</th>
                    <td>
                        @if($payment->status == 'paid')
                            <span class="badge bg-success fs-6">Paid</span>
                        @elseif($payment->status == 'partial')
                            <span class="badge bg-warning fs-6">Partial</span>
                        @else
                            <span class="badge bg-danger fs-6">Unpaid</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="bg-light">Payment Method</th>
                    <td class="text-capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Payment Date</th>
                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Description</th>
                    <td>{{ $payment->description ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Created At</th>
                    <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            </table>

            <div class="no-print d-flex gap-2">
                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning text-white">
                    <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                </a>
                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Ma hubtaa inaad tirtirto lacagtan?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
