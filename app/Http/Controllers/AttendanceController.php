<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date',
            'grade' => 'nullable|string',
            'student_id' => 'nullable|exists:students,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Attendance::with('student.user');

        $user = auth()->user();
        if ($user && $user->role === 'teacher') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            if ($teacher) {
                $grades = \App\Models\Grade::where('class_teacher_id', $teacher->id)->get();
                if ($grades->isEmpty()) {
                    return response()->json([]);
                }
                
                $query->whereHas('student', function ($q) use ($grades) {
                    $q->where(function ($sq) use ($grades) {
                        foreach ($grades as $idx => $g) {
                            if ($idx === 0) {
                                $sq->where('grade', $g->name)->where('class', $g->section);
                            } else {
                                $sq->orWhere(function ($ssq) use ($g) {
                                    $ssq->where('grade', $g->name)->where('class', $g->section);
                                });
                            }
                        }
                    });
                });
            }
        }

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->has('grade')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('grade', $request->grade);
            });
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $attendance = $query->orderBy('date', 'desc')->get();

        return response()->json($attendance);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'entry_time' => 'nullable|date_format:H:i:s',
            'exit_time' => 'nullable|date_format:H:i:s',
            'gate' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'date' => $request->date,
            ],
            [
                'status' => $request->status,
                'entry_time' => $request->entry_time,
                'exit_time' => $request->exit_time,
                'gate' => $request->gate,
            ]
        );

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'attendance' => $attendance
        ], 201);
    }

    public function show($id)
    {
        $attendance = Attendance::with('student.user')->find($id);

        if (!$attendance) {
            return response()->json(['error' => 'Attendance record not found'], 404);
        }

        return response()->json($attendance);
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json(['error' => 'Attendance record not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:present,absent,late,excused',
            'entry_time' => 'nullable|date_format:H:i:s',
            'exit_time' => 'nullable|date_format:H:i:s',
            'gate' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $attendance->update($request->only(['status', 'entry_time', 'exit_time', 'gate']));

        return response()->json([
            'message' => 'Attendance updated successfully',
            'attendance' => $attendance
        ]);
    }

    public function destroy($id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json(['error' => 'Attendance record not found'], 404);
        }

        $attendance->delete();

        return response()->json(['message' => 'Attendance record deleted successfully']);
    }

    public function markQR(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'gate' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $today = now()->toDateString();
        $currentTime = now()->format('H:i:s');

        $attendance = Attendance::firstOrNew([
            'student_id' => $request->student_id,
            'date' => $today,
        ]);

        if (!$attendance->exists) {
            // First scan of the day (entry)
            $attendance->entry_time = $currentTime;
            $attendance->status = 'present';
            $attendance->gate = $request->gate;
            $attendance->save();

            // 🔔 Notify user: arrived at school
            $this->sendAttendanceNotification($attendance->student, 'entry', $currentTime);

            return response()->json([
                'message' => 'Entry recorded successfully',
                'type' => 'entry',
                'attendance' => $attendance
            ]);
        } elseif (!$attendance->exit_time) {
            // Second scan of the day (exit)
            $attendance->exit_time = $currentTime;
            $attendance->save();

            // 🔔 Notify user: left school
            $this->sendAttendanceNotification($attendance->student, 'exit', $currentTime);

            return response()->json([
                'message' => 'Exit recorded successfully',
                'type' => 'exit',
                'attendance' => $attendance
            ]);
        } else {
            return response()->json([
                'message' => 'Already recorded both entry and exit for today',
                'attendance' => $attendance
            ]);
        }
    }

    /**
     * Send an FCM attendance notification to a student.
     */
    private function sendAttendanceNotification($student, string $type, string $time): void
    {
        try {
            if (!$student || !$student->user) {
                return;
            }
            $user = $student->user;
            if (empty($user->fcm_token)) {
                return;
            }

            $timeLabel = date('h:i A', strtotime($time));

            if ($type === 'entry') {
                $title = '🏫 Arrived at School';
                $body  = "You have successfully arrived at school at {$timeLabel}. Have a great day!";
            } else {
                $title = '🚪 Left School';
                $body  = "You have exited school at {$timeLabel}. See you tomorrow!";
            }

            $extraData = [
                'type'     => 'attendance',
                'sub_type' => $type,
                'time'     => $timeLabel,
            ];

            dispatch(function () use ($user, $title, $body, $extraData) {
                FcmService::sendToToken($user->fcm_token, $title, $body, $extraData);
            })->afterResponse();

        } catch (\Exception $e) {
            Log::error('QR Attendance FCM Error: ' . $e->getMessage());
        }
    }
}