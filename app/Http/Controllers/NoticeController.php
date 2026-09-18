<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\EmergencyAlert;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'nullable|in:academic,finance,event,general',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Notice::with(['postedBy', 'recipient']);

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Expiry date check
        $query->where(function ($q) {
            $q->whereNull('expiry_date')
                ->orWhere('expiry_date', '>=', now()->toDateString());
        });

        // Filter based on role of authenticated user
        $user = auth()->user();
        if ($user && $user->role !== 'admin') {
            $query->where(function ($q) use ($user) {
                // Show 'all' notices
                $q->where('target_audience', 'all');

                // Show role-specific notices
                if ($user->role === 'student') {
                    $q->orWhere('target_audience', 'students');
                } elseif ($user->role === 'teacher') {
                    $q->orWhere('target_audience', 'teachers');
                }

                // Show individual-specific notices
                $q->orWhere(function ($sq) use ($user) {
                    $sq->where('target_audience', 'individual')
                       ->where('recipient_id', $user->id);
                });
            });
        }

        $notices = $query->orderBy('created_at', 'desc')->get();

        return response()->json($notices);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:academic,finance,event,general',
            'expiry_date' => 'nullable|date|after:today',
            'target_audience' => 'nullable|in:all,students,teachers,individual',
            'recipient_id' => 'required_if:target_audience,individual|nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notice = Notice::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'category' => $request->input('category'),
            'posted_by' => auth()->id(),
            'expiry_date' => $request->input('expiry_date'),
            'target_audience' => $request->input('target_audience', 'all'),
            'recipient_id' => $request->input('recipient_id'),
        ]);

        // 🔔 Trigger FCM Push Notification (After Response for instant 10ms UI return!)
        dispatch(function () use ($notice) {
            try {
                $targetAudience = $notice->target_audience ?? 'all';
                $title = "📢 Notice: " . $notice->title;
                $body = mb_strimwidth(strip_tags($notice->content), 0, 120, "...");
                $extraData = [
                    'notice_id' => (string) $notice->id,
                    'category' => (string) $notice->category,
                    'type' => 'notice'
                ];

                if ($targetAudience === 'all') {
                    FcmService::sendToTopic('notice_all', $title, $body, $extraData);
                } elseif ($targetAudience === 'teachers') {
                    FcmService::sendToTopic('notice_teachers', $title, $body, $extraData);
                } elseif ($targetAudience === 'students') {
                    FcmService::sendToTopic('notice_students', $title, $body, $extraData);
                } elseif ($targetAudience === 'individual' && $notice->recipient_id) {
                    $recipient = User::find($notice->recipient_id);
                    if ($recipient && !empty($recipient->fcm_token)) {
                        FcmService::sendToToken($recipient->fcm_token, $title, $body, $extraData);
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("FCM Notice Trigger Error: " . $e->getMessage());
            }
        })->afterResponse();

        return response()->json([
            'message' => 'Notice created successfully',
            'notice' => $notice->load(['postedBy', 'recipient'])
        ], 201);
    }

    public function show($id)
    {
        $notice = Notice::with(['postedBy', 'recipient'])->find($id);

        if (!$notice) {
            return response()->json(['error' => 'Notice not found'], 404);
        }

        return response()->json($notice);
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return response()->json(['error' => 'Notice not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'category' => 'sometimes|in:academic,finance,event,general',
            'expiry_date' => 'nullable|date',
            'target_audience' => 'sometimes|in:all,students,teachers,individual',
            'recipient_id' => 'required_if:target_audience,individual|nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notice->update($request->all());

        return response()->json([
            'message' => 'Notice updated successfully',
            'notice' => $notice->load(['postedBy', 'recipient'])
        ]);
    }

    public function destroy($id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return response()->json(['error' => 'Notice not found'], 404);
        }

        $notice->delete();

        return response()->json(['message' => 'Notice deleted successfully']);
    }

    public function sendEmergencyAlert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'preset_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rawTitle = $request->input('title');
        $title = str_contains($rawTitle, 'EMERGENCY') ? $rawTitle : "🚨 EMERGENCY ALERT: " . $rawTitle;
        $content = $request->input('content');

        // Create dedicated EmergencyAlert record (NOT in Notice table)
        $alert = EmergencyAlert::create([
            'title' => $title,
            'content' => $content,
            'preset_type' => $request->input('preset_type', 'custom'),
            'posted_by' => auth()->id() ?? 1,
        ]);

        // Trigger High-Priority FCM Push Notification to all users (~200ms speed!)
        try {
            $extraData = [
                'alert_id' => (string) $alert->id,
                'type' => 'emergency',
                'sound' => 'emergency',
                'priority' => 'high'
            ];

            // Single clean broadcast to 'notice_all' topic
            FcmService::sendToTopic('notice_all', $title, $content, $extraData);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("FCM Emergency Alert Error: " . $e->getMessage());
        }

        return response()->json([
            'message' => '🚨 Emergency Alert Broadcasted to all Students & Teachers successfully!',
            'alert' => $alert->load('postedBy')
        ], 201);
    }

    public function getEmergencyAlerts()
    {
        $alerts = EmergencyAlert::with('postedBy')->orderBy('created_at', 'desc')->get();
        return response()->json($alerts);
    }
}
