<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Class</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold">Add New Class</h4>
            <a href="{{ route('classes.index') }}" class="btn btn-sm btn-light">Back</a>
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

            <form action="{{ route('classes.store') }}" method="POST">
                @csrf

                <!-- Class Number -->
                <div class="mb-3">
                    <label class="form-label">Class Number (1-8)</label>
                    <select name="class_number" class="form-select @error('class_number') is-invalid @enderror" required>
                        <option value="">-- Select Class --</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('class_number') == $i ? 'selected' : '' }}>Class {{ $i }}</option>
                        @endfor
                    </select>
                    @error('class_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Section -->
                <div class="mb-3">
                    <label class="form-label">Section</label>
                    <select name="section" class="form-select @error('section') is-invalid @enderror" required>
                        <option value="">-- Select Section --</option>
                        <option value="A" {{ old('section') == 'A' ? 'selected' : '' }}>Section A</option>
                        <option value="B" {{ old('section') == 'B' ? 'selected' : '' }}>Section B</option>
                    </select>
                    @error('section')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Class Teacher -->
                <div class="mb-3">
                    <label class="form-label">Class Teacher (Optional)</label>
                    <input type="text" name="class_teacher" class="form-control @error('class_teacher') is-invalid @enderror" value="{{ old('class_teacher') }}" placeholder="E.g. Mr. Ahmed">
                    @error('class_teacher')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Capacity -->
                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', 40) }}" min="1" max="100">
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Save Class</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
