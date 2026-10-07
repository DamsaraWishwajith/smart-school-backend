<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    // GET /api/subjects
    public function index(Request $request)
    {
        $query = Subject::whereNotNull('subject_name')
            ->whereNotNull('grade')
            ->where('grade', '!=', '');

        if ($request->has('grade')) {
            $query->where('grade', $request->input('grade'));
        }

        $user = auth('api')->user() ?: auth()->user();
        if (!$user && $request->has('user_id')) {
            $user = \App\Models\User::find($request->user_id);
        }

        if ($user) {
            if ($user->role === 'student' && $user->student) {
                $student = $user->student;
                $studentGradeClass = trim($student->grade . ' ' . $student->class);
                
                $gradePrefix = stripos($student->grade, 'Grade') === false ? 'Grade ' : '';
                $formattedGradeClass = $gradePrefix . $studentGradeClass;
                $formattedGrade = $gradePrefix . $student->grade;

                $query->where(function($q) use ($studentGradeClass, $student, $formattedGradeClass, $formattedGrade) {
                    $q->where('grade', $studentGradeClass)
                      ->orWhere('grade', $student->grade)
                      ->orWhere('grade', $formattedGradeClass)
                      ->orWhere('grade', $formattedGrade);
                });
            }
        }

        $subjects = $query->get();

        $userId = $request->input('user_id');
        if (!$userId && $user) {
            $userId = $user->id;
        }

        if ($userId) {
            foreach ($subjects as $subject) {
                $submission = \App\Models\SubjectSubmission::where('user_id', $userId)
                    ->where('subject_id', $subject->id)
                    ->first();
                
                $subject->is_submitted = $submission ? true : false;
                $subject->submitted_at = $submission ? $submission->created_at : null;
                $subject->submission_file = $submission ? $submission->assignment_pdf : null;
                $subject->submission_grade = $submission ? $submission->grade : null;
                $subject->submission_feedback = $submission ? $submission->feedback : null;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $subjects
        ]);
    }

    // POST /api/subjects
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grade'         => 'required|string',
            'subject_name'  => 'required|string',
            'topic'         => 'nullable|string',
            'pdf'           => 'nullable', // Can be file or string (URL)
            'assignment'    => 'nullable', // Can be file or string
            'due_time'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $pdfPath = $request->input('pdf');
        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('subjects/pdfs', 'public');
            $pdfPath = Storage::url($path);
        }

        $assignmentPath = $request->input('assignment');
        if ($request->hasFile('assignment')) {
            $path = $request->file('assignment')->store('subjects/assignments', 'public');
            $assignmentPath = Storage::url($path);
        }

        $teacherId = null;
        $user = auth('api')->user() ?: auth()->user();
        if ($user && $user->role === 'teacher' && $user->teacher) {
            $teacherId = $user->teacher->id;
        }

        $subject = Subject::create([
            'grade'        => $request->input('grade'),
            'subject_name' => $request->input('subject_name'),
            'topic'        => $request->input('topic'),
            'pdf'          => $pdfPath,
            'assignment'   => $assignmentPath,
            'due_time'     => $request->input('due_time'),
            'teacher_id'   => $teacherId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subject created successfully',
            'data'    => $subject
        ], 201);
    }

    // PUT /api/subjects/{id}
    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(['success' => false, 'message' => 'Subject not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'subject_name' => 'required|string',
            'grade'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $subject->subject_name = $request->input('subject_name');
        if ($request->filled('grade')) {
            $subject->grade = $request->input('grade');
        }
        $subject->save();

        return response()->json(['success' => true, 'message' => 'Subject updated successfully', 'data' => $subject]);
    }

    // DELETE /api/subjects/{id}
    public function destroy(Request $request, $id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(['success' => false, 'message' => 'Subject not found'], 404);
        }

        $field = $request->query('field');
        if ($field === 'pdf') {
            if ($subject->pdf) {
                $path = str_replace('/storage/', '', parse_url($subject->pdf, PHP_URL_PATH));
                Storage::disk('public')->delete($path);
            }
            $subject->pdf = null;
            if (empty($subject->assignment)) {
                $subject->delete();
            } else {
                $subject->save();
            }
            return response()->json(['success' => true, 'message' => 'Material deleted successfully']);
        }

        if ($field === 'assignment') {
            if ($subject->assignment) {
                $path = str_replace('/storage/', '', parse_url($subject->assignment, PHP_URL_PATH));
                Storage::disk('public')->delete($path);
            }
            $subject->assignment = null;
            if (empty($subject->pdf)) {
                $subject->delete();
            } else {
                $subject->save();
            }
            return response()->json(['success' => true, 'message' => 'Assignment deleted successfully']);
        }

        if ($subject->pdf) {
            $path = str_replace('/storage/', '', parse_url($subject->pdf, PHP_URL_PATH));
            Storage::disk('public')->delete($path);
        }
        if ($subject->assignment) {
            $path = str_replace('/storage/', '', parse_url($subject->assignment, PHP_URL_PATH));
            Storage::disk('public')->delete($path);
        }

        $subject->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
