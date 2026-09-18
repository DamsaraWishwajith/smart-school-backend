<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectSubmission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SubjectSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'        => 'required|integer',
            'subject_id'     => 'required|integer',
            'assignment_pdf' => 'required|file|mimes:pdf|max:10240', // Max 10MB PDF
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // Check for existing submission by this user for this subject
            $submission = SubjectSubmission::where('user_id', $request->user_id)
                ->where('subject_id', $request->subject_id)
                ->first();

            // Store the new file
            $path = $request->file('assignment_pdf')->store('submissions/assignments', 'public');
            $fileUrl = Storage::url($path);

            if ($submission) {
                // Delete the old file from storage to save space
                if ($submission->assignment_pdf) {
                    $oldPath = str_replace('/storage/', '', parse_url($submission->assignment_pdf, PHP_URL_PATH));
                    Storage::disk('public')->delete($oldPath);
                }

                // Update the existing record
                $submission->update([
                    'assignment_pdf' => $fileUrl,
                ]);
                $message = 'Assignment updated successfully!';
            } else {
                // Create a new record
                $submission = SubjectSubmission::create([
                    'user_id'        => $request->user_id,
                    'subject_id'     => $request->subject_id,
                    'assignment_pdf' => $fileUrl,
                ]);
                $message = 'Assignment submitted successfully!';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => $submission
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Submission failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $query = SubjectSubmission::with(['user', 'subject']);

        $user = auth('api')->user() ?: auth()->user();
        if (!$user && $request->has('user_id')) {
            $user = \App\Models\User::find($request->user_id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $teacherId = $request->input('teacher_id');
        if ($user && $user->role === 'teacher') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            if ($teacher) {
                $teacherId = $teacher->id;
            }
        }

        if ($teacherId) {
            $teacherObj = \App\Models\Teacher::find($teacherId);
            $userId = $teacherObj ? $teacherObj->user_id : null;

            $assignedGradeNames = \App\Models\Grade::where('class_teacher_id', $teacherId)
                ->orWhere('class_teacher_id', $userId)
                ->pluck('name')
                ->map(fn($n) => trim($n))
                ->toArray();

            $query->whereHas('subject', function($q) use ($teacherId, $userId, $assignedGradeNames) {
                $q->where(function($sq) use ($teacherId, $userId, $assignedGradeNames) {
                    if ($teacherId) $sq->where('teacher_id', $teacherId);
                    if ($userId) $sq->orWhere('teacher_id', $userId);
                    foreach ($assignedGradeNames as $g) {
                        if ($g) $sq->orWhere('grade', 'like', "%{$g}%");
                    }
                });
            });
        }

        return response()->json([
            'success' => true,
            'data'    => $query->get()
        ]);
    }

    public function grade(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'grade'    => 'required|string',
            'feedback' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $submission = SubjectSubmission::find($id);

        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'Submission not found'
            ], 404);
        }

        $submission->update([
            'grade'    => $request->input('grade'),
            'feedback' => $request->input('feedback'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Submission graded successfully',
            'data'    => $submission
        ]);
    }
}
