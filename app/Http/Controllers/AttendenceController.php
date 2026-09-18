<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\FcmService;
use Illuminate\Support\Facades\Log;

class AttendenceController extends Controller
{
    /**
     * Mark or update attendance.
     */
    public function mark(Request $request)
    {
        $userIdInput = $request->input('user_id');
        $resolvedUserId = null;
        if (is_string($userIdInput)) {
            if (str_starts_with($userIdInput, 'student:')) {
                $studentId = (int) substr($userIdInput, strlen('student:'));
                $student = \App\Models\Student::find($studentId);
                if ($student) {
                    $resolvedUserId = $student->user_id;
                }
            } elseif (str_starts_with($userIdInput, 'teacher:')) {
                $resolvedUserId = (int) substr($userIdInput, strlen('teacher:'));
            } else {
                $resolvedUserId = (int) $userIdInput;
            }
        } else {
            $resolvedUserId = (int) $userIdInput;
        }
        $request->merge(['user_id' => $resolvedUserId]);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'date' => 'required|string',
            'status' => 'required|string|in:in,out',
            'in_time' => 'required_if:status,in|string',
            'out_time' => 'required_if:status,out|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find existing record for the same user and date
        $attendance = Attendence::where('user_id', $request->user_id)
                                ->where('date', $request->date)
                                ->first();

        if ($attendance) {
            // Update existing record
            if ($request->status == 'out') {
                $attendance->update([
                    'out_time' => $request->out_time,
                    'status' => 'out'
                ]);

                $this->sendFcmNotification($request->user_id, 'out');

                return response()->json([
                    'success' => true,
                    'message' => 'Attendance updated (Clock Out)',
                    'data' => $attendance
                ]);
            }
            
            // If it's another "in" for the same day, we could either ignore or update in_time
            // For now, let's just return the existing one
            return response()->json([
                'success' => true,
                'message' => 'Attendance already exists for this date',
                'data' => $attendance
            ]);
        }

        // Create new record
        $newAttendance = Attendence::create([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'in_time' => $request->in_time,
            'out_time' => $request->out_time, // might be null if status is 'in'
            'status' => $request->status,
        ]);

        $this->sendFcmNotification($request->user_id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully',
            'data' => $newAttendance
        ], 201);
    }

    /**
     * Get attendance history for a specific user.
     */
    public function getUserAttendance(Request $request)
    {
        $userIdInput = $request->input('user_id');
        $resolvedUserId = null;
        if (is_string($userIdInput)) {
            if (str_starts_with($userIdInput, 'student:')) {
                $studentId = (int) substr($userIdInput, strlen('student:'));
                $student = \App\Models\Student::find($studentId);
                if ($student) {
                    $resolvedUserId = $student->user_id;
                }
            } elseif (str_starts_with($userIdInput, 'teacher:')) {
                $resolvedUserId = (int) substr($userIdInput, strlen('teacher:'));
            } else {
                $resolvedUserId = (int) $userIdInput;
            }
        } else {
            $resolvedUserId = (int) $userIdInput;
        }
        $request->merge(['user_id' => $resolvedUserId]);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $attendance = Attendence::with('user:id,name,email')
                                ->where('user_id', $request->user_id)
                                ->orderBy('date', 'desc')
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    /**
     * Send FCM push notification to user after attendance is marked.
     */
    private function sendFcmNotification(int $userId, string $status): void
    {
        try {
            $user = \App\Models\User::find($userId);
            if (!$user || empty($user->fcm_token)) {
                return;
            }

            $timeLabel = now()->format('h:i A');

            if ($status === 'in') {
                $title = '🏫 Arrived at School';
                $body  = "You have successfully arrived at school at {$timeLabel}. Have a great day!";
            } else {
                $title = '🚪 Left School';
                $body  = "You have exited school at {$timeLabel}. See you tomorrow!";
            }

            $extraData = [
                'type'     => 'attendance',
                'sub_type' => $status,
                'time'     => $timeLabel,
            ];

            dispatch(function () use ($user, $title, $body, $extraData) {
                FcmService::sendToToken($user->fcm_token, $title, $body, $extraData);
            })->afterResponse();

        } catch (\Exception $e) {
            Log::error('Attendance FCM Notification Error: ' . $e->getMessage());
        }
    }
}
