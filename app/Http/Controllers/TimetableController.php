<?php
// app/Http/Controllers/TimetableController.php
namespace App\Http\Controllers;

use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function getTimetables()
    {
        try {
            // Method 1: Get all timetables with relationships
            $timetables = Timetable::with(['grade', 'subject', 'teacher'])
                ->orderBy('day')
                ->orderBy('start_time')
                ->get();

            // Method 2: If you want exactly the same format as your phpMyAdmin display
            $timetables = Timetable::select(
                'id',
                'grade_id',
                'subject_id',
                'teacher_id',
                'day',
                'start_time',
                'end_time',
                'room',
                'created_at',
                'updated_at'
            )->orderBy('id')->get();

            // Return as JSON response
            return response()->json([
                'success' => true,
                'message' => 'Timetables retrieved successfully',
                'data' => $timetables,
                'total' => $timetables->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving timetables',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Alternative: Get timetables with specific formatting
    public function getTimetablesFormatted()
    {
        $timetables = Timetable::with(['grade', 'subject', 'teacher'])
            ->get()
            ->map(function ($timetable) {
                return [
                    'id' => $timetable->id,
                    'grade' => [
                        'id' => $timetable->grade->id ?? null,
                        'name' => $timetable->grade->name ?? null
                    ],
                    'subject' => [
                        'id' => $timetable->subject->id ?? null,
                        'name' => $timetable->subject->name ?? null,
                        'code' => $timetable->subject->code ?? null
                    ],
                    'teacher' => [
                        'id' => $timetable->teacher->id ?? null,
                        'name' => $timetable->teacher->name ?? null,
                        'email' => $timetable->teacher->email ?? null
                    ],
                    'day' => $timetable->day,
                    'start_time' => $timetable->start_time->format('H:i:s'),
                    'end_time' => $timetable->end_time->format('H:i:s'),
                    'room' => $timetable->room,
                    'created_at' => $timetable->created_at->toDateTimeString(),
                    'updated_at' => $timetable->updated_at->toDateTimeString()
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $timetables
        ]);
    }

    // Get timetables filtered by day
    public function getTimetablesByDay($day)
    {
        $timetables = Timetable::with(['grade', 'subject', 'teacher'])
            ->where('day', $day)
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'day' => $day,
            'data' => $timetables
        ]);
    }

    // Get timetables filtered by grade
    public function getTimetablesByGrade($gradeId)
    {
        $timetables = Timetable::with(['grade', 'subject', 'teacher'])
            ->where('grade_id', $gradeId)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'grade_id' => $gradeId,
            'data' => $timetables
        ]);
    }
}
