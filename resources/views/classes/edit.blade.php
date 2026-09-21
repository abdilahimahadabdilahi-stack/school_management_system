<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold">Edit Class: {{ $schoolClass->class_label }}</h4>
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

            <form action="{{ route('classes.update', $schoolClass->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Class Number -->
                <div class="mb-3">
                    <label class="form-label">Class Number (1-8)</label>
                    <select name="class_number" class="form-select" required>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('class_number', $schoolClass->class_number) == $i ? 'selected' : '' }}>Class {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Section -->
                <div class="mb-3">
                    <label class="form-label">Section</label>
                    <select name="section" class="form-select" required>
                        <option value="A" {{ old('section', $schoolClass->section) == 'A' ? 'selected' : '' }}>Section A</option>
                        <option value="B" {{ old('section', $schoolClass->section) == 'B' ? 'selected' : '' }}>Section B</option>
                    </select>
                </div>

                <!-- Class Teacher -->
                <div class="mb-3">
                    <label class="form-label">Class Teacher (Optional)</label>
                    <input type="text" name="class_teacher" class="form-control" value="{{ old('class_teacher', $schoolClass->class_teacher) }}">
                </div>

                <!-- Capacity -->
                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $schoolClass->capacity) }}" min="1" max="100">
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold py-2 text-white">Update Class</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
