<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Manager - SchoolSMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-white fw-bold">
            <i class="fa-solid fa-pen-to-square me-2"></i> Edit Manager Details
        </div>
        <div class="card-body">
            <!-- Action-kan wuxuu toos ugu dhacayaa Update function-ka -->
            <form action="{{ route('managers.update', $manager->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $manager->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $manager->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $manager->phone) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department', $manager->department) }}">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('managers.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Cancel</a>
                    <button type="submit" class="btn btn-warning text-white"><i class="fa-solid fa-rotate me-1"></i> Update Manager</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>