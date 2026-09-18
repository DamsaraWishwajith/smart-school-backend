<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function index(Request $request, $other_user_id)
    {
        $request->merge(['other_user_id' => $other_user_id]);

        $validator = Validator::make($request->all(), [
            'other_user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = auth()->id();
        $otherUserId = $other_user_id;

        // Get messages between the two users
        $messages = Message::where(function ($query) use ($userId, $otherUserId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $otherUserId);
        })->orWhere(function ($query) use ($userId, $otherUserId) {
            $query->where('sender_id', $otherUserId)
                ->where('receiver_id', $userId);
        })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages as read
        Message::where('sender_id', $otherUserId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->input('receiver_id'),
            'content' => $request->input('content'),
            'is_read' => false,
        ]);

        // Load relationships
        $message->load(['sender', 'receiver']);

        // Broadcast event for real-time (if using websockets)
        // broadcast(new NewMessage($message))->toOthers();

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message
        ], 201);
    }

    public function getConversations()
    {
        $userId = auth()->id();

        // Get all users the current user has chatted with
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($messages, $otherUserId) {
                $lastMessage = $messages->sortByDesc('created_at')->first();
                $otherUser = $lastMessage->sender_id == auth()->id()
                    ? $lastMessage->receiver
                    : $lastMessage->sender;

                $unreadCount = $messages->where('receiver_id', auth()->id())
                    ->where('is_read', false)
                    ->count();

                return [
                    'user' => $otherUser,
                    'last_message' => $lastMessage->content,
                    'last_message_time' => $lastMessage->created_at,
                    'unread_count' => $unreadCount,
                ];
            })
            ->values();

        return response()->json($conversations);
    }

    public function markAsRead($messageId)
    {
        $message = Message::find($messageId);

        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }

        if ($message->receiver_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->update(['is_read' => true]);

        return response()->json(['message' => 'Message marked as read']);
    }
}
