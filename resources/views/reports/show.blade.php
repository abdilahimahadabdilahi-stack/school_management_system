<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Show Report - SchoolSMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-eye text-primary me-2"></i>Report Details #{{ $id }}</h2>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
    </div>

    <div class="card shadow-sm p-4">
        <h4 class="fw-bold text-dark">System Summary Report #{{ $id }}</h4>
        <hr>
        <p><strong>Type:</strong> Student Report</p>
        <p><strong>Date:</strong> {{ date('Y-m-d') }}</p>
        <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
        <p><strong>Details:</strong> Halkan waxaa ku xusan dhammaan xogta faahfaahsan ee warbixinta No. {{ $id }}.</p>
        
        <div class="mt-3">
            <a href="{{ route('reports.edit', $id) }}" class="btn btn-warning fw-bold"><i class="fa-solid fa-pen-to-square me-1"></i> Edit Report</a>
        </div>
    </div>
</div>
</body>
</html>