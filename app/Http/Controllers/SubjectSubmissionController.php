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
        $query = SubjectSubmission::with(['user', 'subject'])->latest();

        $user = auth('api')->user() ?: auth()->user();
        if (!$user && $request->has('user_id')) {
            $user = \App\Models\User::find($request->user_id);
        }

        $teacherId = $request->input('teacher_id');
        $userId = $request->input('user_id');

        $isTeacher = false;
        if ($user && $user->role === 'teacher') {
            $isTeacher = true;
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            if ($teacher) {
                $teacherId = $teacher->id;
                $userId = $user->id;
            }
        } elseif ($teacherId) {
            $isTeacher = true;
            if (!$userId) {
                $tObj = \App\Models\Teacher::find($teacherId);
                if ($tObj) $userId = $tObj->user_id;
            }
        } elseif ($userId && \App\Models\Teacher::where('user_id', $userId)->exists()) {
            $isTeacher = true;
            $tObj = \App\Models\Teacher::where('user_id', $userId)->first();
            if ($tObj) $teacherId = $tObj->id;
        }

        // Student-specific filtering: Only apply when explicitly requested or when caller is a student
        if ($request->has('student_id')) {
            $query->where('user_id', $request->student_id);
        } elseif ($request->has('student_user_id')) {
            $query->where('user_id', $request->student_user_id);
        } elseif (!$isTeacher && $request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        } elseif ($user && $user->role === 'student') {
            $query->where('user_id', $user->id);
        }

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($isTeacher && ($teacherId || $userId)) {
            $teacherObj = $teacherId ? \App\Models\Teacher::find($teacherId) : \App\Models\Teacher::where('user_id', $userId)->first();

            // SimpleTimetable assignments
            $teacherTimetables = \App\Models\SimpleTimetable::where(function($tq) use ($teacherId, $userId) {
                if ($userId) $tq->where('grade', 'like', "%teacher:{$userId}%");
                if ($teacherId) $tq->orWhere('grade', 'like', "%teacher:{$teacherId}%");
            })->get();

            $slots = [
                't_8_00_8_30','t_8_30_9_00','t_9_00_9_30','t_9_30_10_00',
                't_10_00_10_30','t_10_30_11_00','t_11_00_11_30','t_11_30_12_00',
                't_12_00_12_30','t_12_30_1_00','t_1_00_1_30'
            ];
            $timetableAssignments = [];
            foreach ($teacherTimetables as $tt) {
                foreach ($slots as $slot) {
                    $val = trim($tt->$slot ?? '');
                    if ($val) {
                        if (preg_match('/\((?:Grade\s*)?([^)]+)\)/i', $val, $gm)) {
                            $slotGrade = trim($gm[1]);
                            $subName = trim(preg_replace('/\s*\([^)]+\)/', '', explode('-', $val)[0]));
                            if ($slotGrade && $subName) {
                                $timetableAssignments[] = [
                                    'grade' => $slotGrade,
                                    'subject' => strtolower($subName)
                                ];
                            }
                        }
                    }
                }
            }

            $specSubjects = [];
            if ($teacherObj && !empty($teacherObj->subject_specialization)) {
                $specSubjects = array_filter(array_map('strtolower', array_map('trim', explode(',', $teacherObj->subject_specialization))));
            }

            $query->whereHas('subject', function($q) use ($teacherId, $userId, $timetableAssignments, $specSubjects) {
                // Must not be explicitly owned by another teacher
                $q->where(function($owQ) use ($teacherId, $userId) {
                    $owQ->whereNull('teacher_id')
                        ->orWhere('teacher_id', 0)
                        ->orWhere('teacher_id', '')
                        ->orWhere(function($tMatch) use ($teacherId, $userId) {
                            if ($teacherId) $tMatch->where('teacher_id', $teacherId);
                            if ($userId) $tMatch->orWhere('teacher_id', $userId);
                        });
                });

                $q->where(function($sq) use ($teacherId, $userId, $timetableAssignments, $specSubjects) {
                    $hasAny = false;

                    // Condition 1: Directly assigned teacher_id on subject
                    if ($teacherId) {
                        $sq->where('teacher_id', $teacherId);
                        $hasAny = true;
                    }
                    if ($userId) {
                        $sq->orWhere('teacher_id', $userId);
                        $hasAny = true;
                    }

                    // Condition 2: From timetable (specific subject in specific grade taught by teacher)
                    foreach ($timetableAssignments as $item) {
                        $rawG = trim(str_ireplace('Grade ', '', $item['grade']));
                        $sName = $item['subject'];
                        $cond = function($tsq) use ($rawG, $sName) {
                            $tsq->where('grade', 'like', "%{$rawG}%")
                                ->where('subject_name', 'like', "%{$sName}%");
                        };
                        if ($hasAny) {
                            $sq->orWhere($cond);
                        } else {
                            $sq->where($cond);
                            $hasAny = true;
                        }
                    }

                    // Condition 3: Specialization
                    foreach ($specSubjects as $spec) {
                        if ($spec) {
                            if ($hasAny) {
                                $sq->orWhere('subject_name', 'like', "%{$spec}%");
                            } else {
                                $sq->where('subject_name', 'like', "%{$spec}%");
                                $hasAny = true;
                            }
                        }
                    }

                    if (!$hasAny) {
                        $sq->whereRaw('1 = 0');
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
