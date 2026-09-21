<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container" style="max-width: 650px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold"><i class="fa-solid fa-money-bill-wave me-2"></i>Add New Payment</h4>
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

            <form action="{{ route('payments.store') }}" method="POST">
                @csrf

                <!-- Student -->
                <div class="mb-3">
                    <label class="form-label">Student</label>
                    <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                        <option value="">-- Select Student --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->class_name }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Class -->
                <div class="mb-3">
                    <label class="form-label">Class (Optional)</label>
                    <select name="school_class_id" class="form-select">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('school_class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->class_label }} - {{ $class->class_teacher ?? 'No Teacher' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Total Fee -->
                <div class="mb-3">
                    <label class="form-label">Total Fee ($)</label>
                    <input type="number" step="0.01" name="total_fee" class="form-control @error('total_fee') is-invalid @enderror" value="{{ old('total_fee') }}" required placeholder="e.g. 500.00">
                    @error('total_fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Amount Paid -->
                <div class="mb-3">
                    <label class="form-label">Amount Paid ($)</label>
                    <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required placeholder="e.g. 300.00">
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Payment Date -->
                <div class="mb-3">
                    <label class="form-label">Payment Date</label>
                    <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                    @error('payment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="E.g. Tuition fee, Term 1">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Save Payment</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
