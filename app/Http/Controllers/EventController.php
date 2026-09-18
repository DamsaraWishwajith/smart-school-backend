<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2020',
            'type' => 'nullable|in:upcoming,past',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Event::query();

        if ($request->has('type')) {
            if ($request->type === 'upcoming') {
                $query->where('event_date', '>=', now());
            } else {
                $query->where('event_date', '<', now());
            }
        }

        if ($request->has('month') && $request->has('year')) {
            $query->whereMonth('event_date', $request->month)
                ->whereYear('event_date', $request->year);
        }

        $events = $query->orderBy('event_date')->get();

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
            'location' => 'required|string',
            'what_to_bring' => 'nullable|string',
            'what_to_do' => 'nullable|string',
            'learning_goals' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event = Event::create($request->all());

        // 🔔 Trigger FCM Push Notification for New Event
        try {
            $title = "📅 New Event: " . $event->title;
            $body = "Date: " . $event->event_date . " | Location: " . $event->location;
            $extraData = [
                'event_id' => (string) $event->id,
                'type' => 'event'
            ];

            FcmService::sendToTopic('notice_all', $title, $body, $extraData);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("FCM Event Trigger Error: " . $e->getMessage());
        }

        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event
        ], 201);
    }

    public function show($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        return response()->json($event);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'event_date' => 'sometimes|date',
            'start_time' => 'sometimes|date_format:H:i:s',
            'end_time' => 'sometimes|date_format:H:i:s|after:start_time',
            'location' => 'sometimes|string',
            'what_to_bring' => 'nullable|string',
            'what_to_do' => 'nullable|string',
            'learning_goals' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event->update($request->all());

        return response()->json([
            'message' => 'Event updated successfully',
            'event' => $event
        ]);
    }

    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}