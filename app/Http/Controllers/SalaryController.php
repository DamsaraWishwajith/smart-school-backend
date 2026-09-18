<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\SalaryPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SalaryController extends Controller
{
    /**
     * GET /api/admin/salaries/monthly-status
     * Fetch all teachers with salary payment status for a specific month & year.
     */
    public function getMonthlyStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month'  => 'nullable|integer|min:1|max:12',
            'year'   => 'nullable|integer',
            'status' => 'nullable|in:all,paid,unpaid',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $month  = (int) $request->query('month', now()->month);
        $year   = (int) $request->query('year', now()->year);
        $filter = $request->query('status', 'all');

        $date = Carbon::create($year, $month, 1);
        $monthName = $date->format('F Y');

        // Ensure all teacher role users have a corresponding record in teachers table
        $missingTeacherUsers = User::where('role', 'teacher')
            ->whereNotExists(function ($query) {
                $query->select(\DB::raw(1))
                      ->from('teachers')
                      ->whereColumn('teachers.user_id', 'users.id');
            })->get();

        foreach ($missingTeacherUsers as $u) {
            Teacher::create([
                'user_id' => $u->id,
                'employee_id' => 'TCH' . str_pad($u->id, 5, '0', STR_PAD_LEFT),
                'qualification' => 'N/A',
                'subject_specialization' => 'N/A',
                'salary' => 50000.00,
            ]);
        }

        // Fetch teachers with their user accounts, filtering to only users with 'teacher' role
        $teachers = Teacher::whereHas('user', function($query) {
            $query->where('role', 'teacher');
        })->with('user')->get();

        $result = $teachers->map(function ($teacher) use ($month, $year) {
            $payment = SalaryPayment::where('teacher_id', $teacher->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            return [
                'teacher_id'     => $teacher->id,
                'employee_id'    => $teacher->employee_id,
                'name'           => optional($teacher->user)->name ?? '—',
                'email'          => optional($teacher->user)->email ?? '—',
                'qualification'  => $teacher->qualification,
                'specialization' => $teacher->subject_specialization,
                'basic_salary'   => $teacher->salary ?? 0.00,
                'payment_status' => $payment ? 'paid' : 'unpaid',
                'payment_details'=> $payment ? [
                    'id'             => $payment->id,
                    'amount'         => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'payment_date'   => $payment->payment_date ? $payment->payment_date->toDateString() : null,
                    'reference'      => $payment->reference,
                    'notes'          => $payment->notes,
                ] : null,
            ];
        });

        // Filter by status
        if ($filter === 'paid') {
            $result = $result->filter(fn($t) => $t['payment_status'] === 'paid');
        } elseif ($filter === 'unpaid') {
            $result = $result->filter(fn($t) => $t['payment_status'] === 'unpaid');
        }

        return response()->json([
            'success'    => true,
            'month_name' => $monthName,
            'month'      => $month,
            'year'       => $year,
            'data'       => $result->values(),
        ]);
    }

    /**
     * POST /api/admin/salaries/toggle
     * Toggle a teacher's salary payment status for a specific month & year.
     */
    public function toggleSalaryStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id'     => 'required|exists:teachers,id',
            'month'          => 'required|integer|min:1|max:12',
            'year'           => 'required|integer',
            'payment_method' => 'nullable|in:cash,bank_transfer,cheque',
            'amount'         => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $teacherId = $request->input('teacher_id');
        $month     = (int) $request->input('month');
        $year      = (int) $request->input('year');

        $teacher = Teacher::findOrFail($teacherId);

        $existing = SalaryPayment::where('teacher_id', $teacherId)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success' => true,
                'status'  => 'unpaid',
                'message' => 'Salary payment deleted (marked as unpaid).'
            ]);
        } else {
            $amount = $request->input('amount') ?? $teacher->salary ?? 0.00;
            $method = $request->input('payment_method', 'bank_transfer');

            SalaryPayment::create([
                'teacher_id'     => $teacherId,
                'month'          => $month,
                'year'           => $year,
                'amount'         => $amount,
                'payment_method' => $method,
                'status'         => 'paid',
                'payment_date'   => now(),
                'reference'      => 'SAL' . Str::upper(Str::random(8)),
                'notes'          => $request->input('notes'),
            ]);

            return response()->json([
                'success' => true,
                'status'  => 'paid',
                'message' => 'Salary paid successfully.'
            ]);
        }
    }

    /**
     * PUT /api/admin/salaries/basic-salary
     * Update a teacher's basic salary amount.
     */
    public function updateBasicSalary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'salary'     => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $teacher = Teacher::findOrFail($request->input('teacher_id'));
        $teacher->salary = $request->input('salary');
        $teacher->save();

        return response()->json([
            'success' => true,
            'message' => 'Basic salary updated successfully.',
            'salary'  => $teacher->salary,
        ]);
    }

    /**
     * GET /api/teacher/salaries
     * Fetch logged-in teacher's salary history.
     */
    public function getTeacherSalaryHistory(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized: Only teachers can access salary details.'], 403);
        }

        $teacher = $user->teacher ?: Teacher::where('user_id', $user->id)->first();
        if (!$teacher) {
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => 'TCH' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'qualification' => 'N/A',
                'subject_specialization' => 'N/A',
                'salary' => 50000.00,
            ]);
        }

        $payments = SalaryPayment::where('teacher_id', $teacher->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $payments
        ]);
    }
}
