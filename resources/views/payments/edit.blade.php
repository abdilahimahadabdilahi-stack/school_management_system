<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container" style="max-width: 650px;">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold">Edit Payment: {{ $payment->receipt_number }}</h4>
            <a href="{{ route('payments.index') }}" class="btn btn-sm btn-light">Back</a>
        </div>
        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Student -->
                <div class="mb-3">
                    <label class="form-label">Student</label>
                    <select name="student_id" class="form-select" required>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id', $payment->student_id) == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->class_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Class -->
                <div class="mb-3">
                    <label class="form-label">Class (Optional)</label>
                    <select name="school_class_id" class="form-select">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('school_class_id', $payment->school_class_id) == $class->id ? 'selected' : '' }}>
                                {{ $class->class_label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Total Fee -->
                <div class="mb-3">
                    <label class="form-label">Total Fee ($)</label>
                    <input type="number" step="0.01" name="total_fee" class="form-control" value="{{ old('total_fee', $payment->total_fee) }}" required>
                </div>

                <!-- Amount Paid -->
                <div class="mb-3">
                    <label class="form-label">Amount Paid ($)</label>
                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $payment->amount) }}" required>
                </div>

                <!-- Payment Method -->
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="mobile_money" {{ old('payment_method', $payment->payment_method) == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    </select>
                </div>

                <!-- Payment Date -->
                <div class="mb-3">
                    <label class="form-label">Payment Date</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $payment->description) }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold py-2 text-white">Update Payment</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
