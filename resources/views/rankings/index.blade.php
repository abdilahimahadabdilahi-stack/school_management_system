@extends('layouts.app-bs')

@section('title', 'Student Rankings')

@section('content')
@php
    /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\SchoolClass> $classes */
@endphp

<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Student Rankings</h2>
            <p class="text-muted mb-0">Cumulative performance across all recorded exam results.</p>
        </div>
        <form method="GET" action="{{ route('rankings.index') }}" class="d-flex flex-column flex-sm-row gap-2">
            <label class="visually-hidden" for="class_id">Class leaderboard</label>
            <select id="class_id" name="class_id" class="form-select">
                <option value="">Select a class leaderboard</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected($selectedClass?->id === $class->id)>{{ $class->class_label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary text-nowrap">View Class Top 10</button>
        </form>
    </div>

    @include('rankings.partials.leaderboard', [
        'title' => 'Overall School Top 10',
        'students' => $overallTopStudents,
        'showClass' => true,
    ])

    @if($selectedClass)
        @include('rankings.partials.leaderboard', [
            'title' => $selectedClass->class_label.' Top 10',
            'students' => $classTopStudents,
            'showClass' => false,
        ])
    @endif
</div>
@endsection