<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FeeController extends Controller
{
    public static function ensureMonthlyFeesExist()
    {
        $year  = now()->year;
        $month = now()->month;

        // Generate fees for all months 1..currentMonth + 1 next month preview
        $upTo = min($month + 1, 12);

        for ($m = 1; $m <= $upTo; $m++) {
            $date      = Carbon::create($year, $m, 20);
            $monthName = $date->format('F Y');
            $title     = 'Tuition Fee - ' . $monthName;

            if (!Fee::where('title', $title)->exists()) {
                Fee::create([
                    'title'       => $title,
                    'description' => 'Monthly tuition fee for ' . $monthName,
                    'amount'      => 3000.00,
                    'type'        => 'tuition',
                    'due_date'    => $date->toDateString(),
                ]);
            }
        }
    }

    public function index(Request $request)
    {
        self::ensureMonthlyFeesExist();

        $validator = Validator::make($request->all(), [
            'student_id' => 'nullable|exists:students,id',
            'status'     => 'nullable|in:pending,completed,failed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $now          = now();
        $currentYear  = $now->year;
        $currentMonth = $now->month;
        $nextMonth    = $currentMonth === 12 ? 1  : $currentMonth + 1;
        $nextYear     = $currentMonth === 12 ? $currentYear + 1 : $currentYear;

        // Fees visible from the 1st of their month:
        //   - current month & earlier  → normal (pending/paid)
        //   - next month               → upcoming (shown so student can plan ahead)
        $fees = Fee::where(function ($q) use ($currentYear, $currentMonth, $nextYear, $nextMonth) {
                    // All fees up to and including next month
                    $q->whereRaw('YEAR(due_date) < ?', [$nextYear])
                      ->orWhere(function ($q2) use ($nextYear, $nextMonth) {
                          $q2->whereRaw('YEAR(due_date) = ?', [$nextYear])
                             ->whereRaw('MONTH(due_date) <= ?', [$nextMonth]);
                      });
                })
                ->whereRaw('YEAR(due_date) >= ?', [$currentYear - 1]) // safety: not ancient fees
                ->orderBy('due_date', 'asc')
                ->get();

        $studentId = $request->query('student_id');

        $result = $fees->map(function ($fee) use ($studentId, $currentYear, $currentMonth) {
            $feeYear  = (int) $fee->due_date->format('Y');
            $feeMonth = (int) $fee->due_date->format('m');

            // Fee is "upcoming" if it's NEXT month (not yet due this month)
            $isUpcoming = ($feeYear > $currentYear)
                       || ($feeYear === $currentYear && $feeMonth > $currentMonth);

            $data = [
                'id'             => $fee->id,
                'title'          => $fee->title,
                'description'    => $fee->description,
                'amount'         => $fee->amount,
                'type'           => $fee->type,
                'due_date'       => $fee->due_date->toDateString(),
                // Visible from 1st of each month:
                'visible_from'   => Carbon::create($feeYear, $feeMonth, 1)->toDateString(),
                'payment_status' => $isUpcoming ? 'upcoming' : 'pending',
                'is_upcoming'    => $isUpcoming,
                'payment'        => null,
            ];

            if ($studentId && !$isUpcoming) {
                $payment = Payment::where('student_id', $studentId)
                    ->where('fee_id', $fee->id)
                    ->where('status', 'completed')
                    ->first();

                if ($payment) {
                    $data['payment_status'] = 'completed';
                    $data['payment'] = [
                        'id'             => $payment->id,
                        'student_id'     => $payment->student_id,
                        'fee_id'         => $payment->fee_id,
                        'amount'         => $payment->amount,
                        'payment_method' => $payment->payment_method,
                        'transaction_id' => $payment->transaction_id,
                        'status'         => $payment->status,
                        'payment_date'   => $payment->payment_date
                            ? $payment->payment_date->toDateTimeString()
                            : null,
                    ];
                }
            }

            return $data;
        });

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:tuition,exam,sports,library,other',
            'due_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $fee = Fee::create($request->all());

        return response()->json([
            'message' => 'Fee created successfully',
            'fee' => $fee
        ], 201);
    }

    public function show($id)
    {
        $fee = Fee::find($id);

        if (!$fee) {
            return response()->json(['error' => 'Fee not found'], 404);
        }

        return response()->json($fee);
    }

    public function update(Request $request, $id)
    {
        $fee = Fee::find($id);

        if (!$fee) {
            return response()->json(['error' => 'Fee not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'sometimes|numeric|min:0',
            'type' => 'sometimes|in:tuition,exam,sports,library,other',
            'due_date' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $fee->update($request->all());

        return response()->json([
            'message' => 'Fee updated successfully',
            'fee' => $fee
        ]);
    }

    public function destroy($id)
    {
        $fee = Fee::find($id);

        if (!$fee) {
            return response()->json(['error' => 'Fee not found'], 404);
        }

        $fee->delete();

        return response()->json(['message' => 'Fee deleted successfully']);
    }

    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'fee_id' => 'required|exists:fees,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:card,bank_transfer,cash,digital_wallet',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if already paid
        $existingPayment = Payment::where('student_id', $request->student_id)
            ->where('fee_id', $request->fee_id)
            ->where('status', 'completed')
            ->first();

        if ($existingPayment) {
            return response()->json(['error' => 'This fee has already been paid'], 422);
        }

        // Generate transaction ID
        $transactionId = 'TXN' . Str::upper(Str::random(10));

        $payment = Payment::create([
            'student_id' => $request->student_id,
            'fee_id' => $request->fee_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $transactionId,
            'status' => 'completed',
            'payment_date' => now(),
        ]);

        return response()->json([
            'message' => 'Payment processed successfully',
            'payment' => $payment->load('fee')
        ], 201);
    }

    public function getPaymentHistory($student_id)
    {
        $payments = Payment::where('student_id', $student_id)
            ->with('fee')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($payments);
    }

    public function toggleStudentFeeStatus(Request $request, $student_id, $fee_id)
    {
        $student = Student::find($student_id);
        $fee = Fee::find($fee_id);

        if (!$student || !$fee) {
            return response()->json(['error' => 'Student or Fee not found'], 404);
        }

        $existingPayment = Payment::where('student_id', $student_id)
            ->where('fee_id', $fee_id)
            ->first();

        if ($existingPayment) {
            if ($existingPayment->status === 'completed') {
                $existingPayment->delete();
                return response()->json([
                    'success' => true,
                    'status' => 'unpaid',
                    'message' => 'Fee marked as unpaid successfully'
                ]);
            } else {
                $existingPayment->update([
                    'status' => 'completed',
                    'payment_date' => now(),
                    'transaction_id' => 'TXN' . Str::upper(Str::random(10)),
                    'payment_method' => 'cash'
                ]);
                return response()->json([
                    'success' => true,
                    'status' => 'paid',
                    'message' => 'Fee marked as paid successfully'
                ]);
            }
        } else {
            Payment::create([
                'student_id' => $student_id,
                'fee_id' => $fee_id,
                'amount' => $fee->amount,
                'payment_method' => 'cash',
                'transaction_id' => 'TXN' . Str::upper(Str::random(10)),
                'status' => 'completed',
                'payment_date' => now()
            ]);
            return response()->json([
                'success' => true,
                'status' => 'paid',
                'message' => 'Fee marked as paid successfully'
            ]);
        }
    }

    /**
     * GET /api/admin/fees/monthly-status?month=6&year=2026&status=all|paid|unpaid
     * Returns all students with payment status for the given month's tuition fee.
     */
    public function getMonthlyStatus(Request $request)
    {
        self::ensureMonthlyFeesExist();

        $month  = (int) $request->query('month', now()->month);
        $year   = (int) $request->query('year',  now()->year);
        $filter = $request->query('status', 'all'); // all | paid | unpaid

        $date      = Carbon::create($year, $month, 20);
        $monthName = $date->format('F Y');
        $title     = 'Tuition Fee - ' . $monthName;

        $fee = Fee::where('title', $title)->first();

        $students = Student::with('user')->get();

        $result = $students->map(function ($student) use ($fee) {
            $isPaid = false;
            if ($fee) {
                $isPaid = Payment::where('student_id', $student->id)
                    ->where('fee_id', $fee->id)
                    ->where('status', 'completed')
                    ->exists();
            }

            return [
                'student_id'     => $student->id,
                'student_code'   => $student->student_id,
                'name'           => optional($student->user)->name ?? '—',
                'email'          => optional($student->user)->email ?? '—',
                'grade'          => $student->grade,
                'class'          => $student->class,
                'payment_status' => $fee ? ($isPaid ? 'paid' : 'unpaid') : 'no_fee',
                'fee_id'         => $fee ? $fee->id : null,
                'fee_amount'     => $fee ? $fee->amount : null,
                'due_date'       => $fee ? $fee->due_date->toDateString() : null,
            ];
        });

        // Apply status filter
        if ($filter === 'paid') {
            $result = $result->filter(fn($s) => $s['payment_status'] === 'paid');
        } elseif ($filter === 'unpaid') {
            $result = $result->filter(fn($s) => $s['payment_status'] === 'unpaid');
        }

        return response()->json([
            'success'    => true,
            'month_name' => $monthName,
            'fee'        => $fee ? [
                'id'       => $fee->id,
                'amount'   => $fee->amount,
                'due_date' => $fee->due_date->toDateString(),
            ] : null,
            'data' => $result->values(),
        ]);
    }
}