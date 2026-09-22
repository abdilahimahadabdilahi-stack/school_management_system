<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management - Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container-fluid bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-money-bill-wave me-2"></i>Fee Payments</h2>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2"><i class="fa-solid fa-arrow-left me-1"></i>Dashboard</a>
            <a href="{{ route('payments.create') }}" class="btn btn-primary">+ Add Payment</a>
        </div>
    </div>

    <form method="GET" action="{{ route('payments.index') }}" class="row g-2 mb-4">
        <div class="col-md-10">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by ID, student name, or payment date">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Search</button>
            @if(request('search'))
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            @endif
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-start border-success border-4 p-3">
                <div class="text-muted small">Total Paid</div>
                <h4 class="fw-bold text-success">${{ number_format($payments->where('status', 'paid')->sum('amount'), 2) }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-warning border-4 p-3">
                <div class="text-muted small">Partial Payments</div>
                <h4 class="fw-bold text-warning">{{ $payments->where('status', 'partial')->count() }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-danger border-4 p-3">
                <div class="text-muted small">Total Balance Due</div>
                <h4 class="fw-bold text-danger">${{ number_format($payments->sum('balance'), 2) }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-primary border-4 p-3">
                <div class="text-muted small">Total Payments</div>
                <h4 class="fw-bold text-primary">{{ $payments->count() }}</h4>
            </div>
        </div>
    </div>

    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Receipt</th>
                <th>Student</th>
                <th>Class</th>
                <th>Total Fee</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Method</th>
                <th>Date</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td><code>{{ $payment->receipt_number }}</code></td>
                    <td>{{ $payment->student->name ?? 'N/A' }}</td>
                    <td>{{ $payment->schoolClass->class_label ?? 'N/A' }}</td>
                    <td>${{ number_format($payment->total_fee, 2) }}</td>
                    <td class="text-success fw-bold">${{ number_format($payment->amount, 2) }}</td>
                    <td class="text-danger fw-bold">${{ number_format($payment->balance, 2) }}</td>
                    <td>
                        @if($payment->status == 'paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($payment->status == 'partial')
                            <span class="badge bg-warning">Partial</span>
                        @else
                            <span class="badge bg-danger">Unpaid</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-secondary text-capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                    </td>
                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                    <td class="text-center">
                        <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-info btn-sm text-white" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning btn-sm text-white" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ma hubtaa inaad tirtirto lacagtan?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Lama helin wax lacag ah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
