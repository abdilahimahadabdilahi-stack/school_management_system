<section class="mb-4" aria-label="{{ $title }}">
    <div class="d-flex justify-content-between align-items-baseline mb-2">
        <h4 class="fw-bold mb-0">{{ $title }}</h4>
        <span class="text-muted small">{{ $students->count() }} ranked</span>
    </div>
    <div class="card card-custom">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Rank</th>
                        <th scope="col">Student</th>
                        @if($showClass)
                            <th scope="col">Class</th>
                        @endif
                        <th scope="col" class="text-end">Cumulative Marks</th>
                        <th scope="col" class="text-end">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="fw-bold">{{ $student->rank }}</td>
                            <td class="fw-semibold">{{ $student->name }}</td>
                            @if($showClass)
                                <td>{{ $student->class_name }}{{ $student->section ? ' · '.$student->section : '' }}</td>
                            @endif
                            <td class="text-end">{{ number_format((float) $student->total_obtained, 2) }} / {{ number_format((float) $student->total_possible_marks, 2) }}</td>
                            <td class="text-end fw-bold">{{ number_format((float) $student->percentage, 2) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $showClass ? 5 : 4 }}" class="text-center text-muted py-4">No exam results are available for this leaderboard.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>