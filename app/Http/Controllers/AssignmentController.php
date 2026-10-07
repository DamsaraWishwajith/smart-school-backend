<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'nullable|exists:subjects,id',
            'student_id' => 'nullable|exists:students,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Assignment::with(['subject', 'teacher.user']);

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $assignments = $query->orderBy('due_date')->get();
        $assignmentsArr = $assignments->toArray();

        // Also include subjects with assignment uploaded
        $subjectQuery = \App\Models\Subject::with(['teacher.user'])->whereNotNull('assignment')->where('assignment', '!=', '');
        if ($request->has('subject_id')) {
            $subjectQuery->where('id', $request->subject_id);
        }
        $subjectsWithAsn = $subjectQuery->latest()->get();
        foreach ($subjectsWithAsn as $s) {
            $submission = null;
            $submissionStatus = 'pending';
            if ($request->has('student_id')) {
                $sub = \App\Models\SubjectSubmission::where('subject_id', $s->id)
                    ->where('user_id', function($q) use ($request) {
                        $st = \App\Models\Student::find($request->student_id);
                        return $st ? $st->user_id : null;
                    })->first();
                if ($sub) {
                    $submissionStatus = 'submitted';
                    $submission = $sub;
                }
            }

            $assignmentsArr[] = [
                'id'                => 'subject_' . $s->id,
                'title'             => $s->topic ? $s->topic : ($s->subject_name . ' - Assignment'),
                'topic'             => $s->topic,
                'description'       => 'Class Assignment',
                'due_date'          => $s->due_time,
                'total_marks'       => 100,
                'file_url'          => $s->assignment,
                'created_at'        => $s->created_at ? $s->created_at->toISOString() : null,
                'updated_at'        => $s->updated_at ? $s->updated_at->toISOString() : null,
                'teacher'           => $s->teacher ? $s->teacher->toArray() : null,
                'subject'           => [
                    'id'    => $s->id,
                    'name'  => $s->subject_name,
                    'grade' => $s->grade,
                ],
                'submission_status' => $submissionStatus,
                'submission'        => $submission,
            ];
        }

        return response()->json($assignmentsArr);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'due_date' => 'required|date|after:today',
            'total_marks' => 'nullable|integer|min:0',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'due_date' => $request->due_date,
            'total_marks' => $request->total_marks,
        ];

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('assignments', 'public');
            $data['file_url'] = Storage::url($path);
        }

        $assignment = Assignment::create($data);

        return response()->json([
            'message' => 'Assignment created successfully',
            'assignment' => $assignment->load(['subject', 'teacher.user'])
        ], 201);
    }

    public function show($id)
    {
        $assignment = Assignment::with(['subject', 'teacher.user', 'submissions.student.user'])->find($id);

        if (!$assignment) {
            return response()->json(['error' => 'Assignment not found'], 404);
        }

        return response()->json($assignment);
    }

    public function update(Request $request, $id)
    {
        $assignment = Assignment::find($id);

        if (!$assignment) {
            return response()->json(['error' => 'Assignment not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'subject_id' => 'sometimes|exists:subjects,id',
            'due_date' => 'sometimes|date|after:today',
            'total_marks' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $assignment->update($request->only(['title', 'description', 'subject_id', 'due_date', 'total_marks']));

        return response()->json([
            'message' => 'Assignment updated successfully',
            'assignment' => $assignment->load(['subject', 'teacher.user'])
        ]);
    }

    public function destroy($id)
    {
        $assignment = Assignment::find($id);

        if (!$assignment) {
            return response()->json(['error' => 'Assignment not found'], 404);
        }

        // Delete file from storage
        if ($assignment->file_url) {
            $path = str_replace('/storage/', '', parse_url($assignment->file_url, PHP_URL_PATH));
            Storage::disk('public')->delete($path);
        }

        $assignment->delete();

        return response()->json(['message' => 'Assignment deleted successfully']);
    }

    public function submit(Request $request, $id)
    {
        $assignment = Assignment::find($id);

        if (!$assignment) {
            return response()->json(['error' => 'Assignment not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'comments' => 'nullable|string',
            'file' => 'required|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if already submitted
        $existingSubmission = Submission::where('assignment_id', $id)
            ->where('student_id', $request->student_id)
            ->first();

        if ($existingSubmission) {
            return response()->json(['error' => 'Already submitted this assignment'], 422);
        }

        // Upload file
        $path = $request->file('file')->store('submissions', 'public');

        $submission = Submission::create([
            'assignment_id' => $id,
            'student_id' => $request->student_id,
            'file_url' => Storage::url($path),
            'comments' => $request->comments,
            'status' => now() > $assignment->due_date ? 'late' : 'submitted',
            'submitted_at' => now(),
        ]);

        return response()->json([
            'message' => 'Assignment submitted successfully',
            'submission' => $submission->load(['student.user'])
        ], 201);
    }

    public function grade(Request $request, $id)
    {
        $submission = Submission::find($id);

        if (!$submission) {
            return response()->json(['error' => 'Submission not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'marks' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $submission->update([
            'marks' => $request->marks,
            'status' => 'graded',
        ]);

        return response()->json([
            'message' => 'Assignment graded successfully',
            'submission' => $submission
        ]);
    }
}