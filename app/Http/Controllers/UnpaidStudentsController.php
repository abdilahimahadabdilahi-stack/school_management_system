<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UnpaidStudentsController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'payment_date' => ['nullable', 'date_format:Y-m-d'],
        ]);
        $paymentDate = $validated['payment_date'] ?? '';

        $students = Student::query()
            ->with(['parent', 'payments' => fn ($query) => $query->orderByDesc('payment_date')->orderByDesc('id')])
            ->get()
            ->map(function (Student $student): ?array {
                $latestPayment = $student->payments->first();
                $status = strtolower((string) ($latestPayment->status ?? 'unpaid'));
                $balance = (float) ($latestPayment->balance ?? 0);

                if ($latestPayment === null) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'class_name' => $student->class_name ?? 'N/A',
                        'fee_amount_due' => '0.00',
                        'status' => 'Unpaid',
                        'payment_date' => '',
                        'phone' => $student->parent?->phone ?? 'N/A',
                    ];
                }

                if ($status === 'paid' && $balance <= 0) {
                    return null;
                }

                if ($status !== 'unpaid' && $status !== 'partial' && $balance <= 0) {
                    return null;
                }

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'class_name' => $student->class_name ?? 'N/A',
                    'fee_amount_due' => number_format(max($balance, 0), 2),
                    'status' => match ($status) {
                        'partial' => 'Partial',
                        'unpaid' => 'Unpaid',
                        default => ucfirst($status),
                    },
                    'payment_date' => $latestPayment->payment_date?->format('Y-m-d') ?? '',
                    'phone' => $student->parent?->phone ?? 'N/A',
                ];
            })
            ->filter()
            ->values();

        if ($paymentDate !== '') {
            $students = $students->filter(
                fn (array $student): bool => $student['payment_date'] === $paymentDate
            )->values();
        }

        $search = trim($request->string('search')->toString());

        if ($search !== '') {
            $students = $students->filter(function (array $student) use ($search): bool {
                foreach ($student as $value) {
                    if (Str::contains((string) $value, $search, true)) {
                        return true;
                    }
                }

                return false;
            })->values();
        }

        return view('students.unpaid', compact('students', 'search', 'paymentDate'));
    }
}
