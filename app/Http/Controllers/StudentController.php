<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\ExamResult;
use App\Models\Payment;
use App\Models\Submission;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function getDashboard($id)
    {
        $student = Student::with('user')->find($id);

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Get today's attendance
        $todayAttendance = Attendance::where('student_id', $id)
            ->where('date', Carbon::today()->toDateString())
            ->first();

        // Get attendance summary
        $weeklyAttendance = Attendance::where('student_id', $id)
            ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();

        $monthlyAttendance = Attendance::where('student_id', $id)
            ->whereMonth('date', now()->month)
            ->get();

        // Get pending fees
        $pendingFees = Payment::where('student_id', $id)
            ->where('status', 'pending')
            ->with('fee')
            ->get();

        // Get upcoming assignments
        $upcomingAssignments = Submission::where('student_id', $id)
            ->where('status', 'submitted')
            ->with('assignment')
            ->get();

        // Get recent exam results
        $recentResults = ExamResult::where('student_id', $id)
            ->with('subject')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'student' => $student,
            'today_attendance' => $todayAttendance,
            'weekly_attendance' => $weeklyAttendance,
            'monthly_attendance' => $monthlyAttendance,
            'pending_fees' => $pendingFees,
            'upcoming_assignments' => $upcomingAssignments,
            'recent_results' => $recentResults
        ]);
    }

    public function getAttendance(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2020',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Attendance::where('student_id', $id);

        if ($request->has('month') && $request->has('year')) {
            $query->whereMonth('date', $request->month)
                ->whereYear('date', $request->year);
        }

        $attendance = $query->orderBy('date', 'desc')->get();

        return response()->json($attendance);
    }

    public function getTimetable($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $timetable = Timetable::where('grade_id', $student->grade)
            ->with(['subject', 'teacher.user'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return response()->json($timetable);
    }

    public function getResults($id)
    {
        $results = ExamResult::where('student_id', $id)
            ->with('subject')
            ->orderBy('academic_year', 'desc')
            ->orderBy('term', 'desc')
            ->get();

        return response()->json($results);
    }

    public function getPayments($id)
    {
        $payments = Payment::where('student_id', $id)
            ->with('fee')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($payments);
    }
}
