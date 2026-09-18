<?php

namespace App\Http\Controllers;

use App\Models\SimpleTimetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SimpleTimetableController extends Controller
{
    // All valid time slot column names
    private array $timeSlots = [
        't_8_00_8_30',
        't_8_30_9_00',
        't_9_00_9_30',
        't_9_30_10_00',
        't_10_00_10_30',
        't_10_30_11_00',
        't_11_00_11_30',
        't_11_30_12_00',
        't_12_00_12_30',
        't_12_30_1_00',
        't_1_00_1_30',
    ];

    // POST /api/simple-timetables
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day'   => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'grade' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = [
            'day'   => $request->input('day'),
            'grade' => $request->input('grade'),
        ];

        // Pick only valid time slot fields from the request
        foreach ($this->timeSlots as $slot) {
            if ($request->has($slot)) {
                $data[$slot] = $request->input($slot);
            }
        }

        // Create or update the row for this day+grade
        $timetable = SimpleTimetable::updateOrCreate(
            ['day' => $data['day'], 'grade' => $data['grade']],
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Timetable saved successfully',
            'data' => $timetable
        ], 201);
    }

    // GET /api/simple-timetables
    public function index()
    {
        $timetables = SimpleTimetable::all();

        return response()->json([
            'success' => true,
            'data' => $timetables
        ]);
    }

    // GET /api/simple-timetables/{day}
    public function show($day)
    {
        $timetable = SimpleTimetable::where('day', $day)->first();

        if (!$timetable) {
            return response()->json(['success' => false, 'message' => 'No timetable found for ' . $day], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $timetable
        ]);
    }

    // GET /api/teacher-timetable/{userId}
    // Returns all simple_timetable rows assigned to this teacher (grade = 'Teacher:{userId}' or 'Teacher:{teacherId}')
    public function getByTeacher($userId)
    {
        $ids = [(string)$userId];

        // Find associated teacher record if available
        $teacher = \App\Models\Teacher::where('user_id', $userId)->orWhere('id', $userId)->first();
        if ($teacher) {
            $ids[] = (string)$teacher->user_id;
            $ids[] = (string)$teacher->id;
        }
        $ids = array_unique($ids);

        $timetables = SimpleTimetable::where(function($q) use ($ids) {
            foreach ($ids as $id) {
                $q->orWhere('grade', 'Teacher:' . $id)
                  ->orWhere('grade', 'LIKE', '%Teacher:' . $id . '%')
                  ->orWhere('grade', 'LIKE', '%teacher:' . $id . '%');
            }
        })
        ->orderByRaw("FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
        ->get();

        return response()->json([
            'success' => true,
            'data'    => $timetables,
        ]);
    }
}
