<?php

namespace App\Http\Controllers;

use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamResultController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'nullable|exists:students,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'term' => 'nullable|string',
            'academic_year' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = ExamResult::with(['student.user', 'subject']);

        $user = auth()->user();
        if ($user && $user->role === 'teacher') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            if ($teacher) {
                $grades = \App\Models\Grade::where('class_teacher_id', $teacher->id)->get();
                $teacherSubjectIds = \App\Models\Subject::where('teacher_id', $teacher->id)
                    ->orWhere('teacher_id', $user->id)
                    ->pluck('id')
                    ->toArray();

                // Check SimpleTimetable for subjects/grades assigned to teacher
                $teacherTimetables = \App\Models\SimpleTimetable::where('grade', 'like', "%teacher:{$user->id}%")
                    ->orWhere('grade', 'like', "%teacher:{$teacher->id}%")
                    ->get();
                $slots = [
                    't_8_00_8_30','t_8_30_9_00','t_9_00_9_30','t_9_30_10_00',
                    't_10_00_10_30','t_10_30_11_00','t_11_00_11_30','t_11_30_12_00',
                    't_12_00_12_30','t_12_30_1_00','t_1_00_1_30'
                ];
                $timetableSubjectNames = [];
                foreach ($teacherTimetables as $tt) {
                    foreach ($slots as $slot) {
                        $val = trim($tt->$slot ?? '');
                        if ($val) {
                            $subName = trim(preg_replace('/\s*\([^)]+\)/', '', explode('-', $val)[0]));
                            if ($subName) {
                                $timetableSubjectNames[] = strtolower($subName);
                            }
                        }
                    }
                }
                if (!empty($timetableSubjectNames)) {
                    $ttSubjectIds = \App\Models\Subject::where(function($sq) use ($timetableSubjectNames) {
                        foreach (array_unique($timetableSubjectNames) as $s) {
                            $sq->orWhere('subject_name', 'like', "%{$s}%");
                        }
                    })->pluck('id')->toArray();
                    $teacherSubjectIds = array_unique(array_merge($teacherSubjectIds, $ttSubjectIds));
                }

                if (!empty($teacher->subject_specialization)) {
                    $specs = array_map('trim', explode(',', $teacher->subject_specialization));
                    $specSubjectIds = \App\Models\Subject::where(function($sq) use ($specs) {
                        foreach ($specs as $s) {
                            $sq->orWhere('subject_name', 'like', "%{$s}%");
                        }
                    })->pluck('id')->toArray();
                    $teacherSubjectIds = array_unique(array_merge($teacherSubjectIds, $specSubjectIds));
                }

                $query->where(function ($q) use ($grades, $teacherSubjectIds) {
                    $hasCond = false;
                    if ($grades->isNotEmpty()) {
                        $q->whereHas('student', function ($sq) use ($grades) {
                            $sq->where(function ($ssq) use ($grades) {
                                foreach ($grades as $g) {
                                    $rawGrade = trim(str_ireplace('Grade ', '', $g->name));
                                    $ssq->orWhere('grade', $rawGrade)
                                        ->orWhere('grade', $g->name);
                                }
                            });
                        });
                        $hasCond = true;
                    }

                    if (!empty($teacherSubjectIds)) {
                        if ($hasCond) {
                            $q->orWhereIn('subject_id', $teacherSubjectIds);
                        } else {
                            $q->whereIn('subject_id', $teacherSubjectIds);
                        }
                        $hasCond = true;
                    }

                    if (!$hasCond) {
                        $q->whereRaw('1 = 0');
                    }
                });
            }
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->has('term')) {
            $query->where('term', $request->term);
        }

        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $results = $query->orderBy('academic_year', 'desc')
            ->orderBy('term', 'desc')
            ->get();

        return response()->json($results);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_name' => 'required|string|max:255',
            'marks_obtained' => 'required|integer|min:0',
            'total_marks' => 'required|integer|min:1',
            'term' => 'required|string',
            'academic_year' => 'required|integer|min:2000',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $percentage = ($request->marks_obtained / $request->total_marks) * 100;
        $grade = $this->calculateGrade($percentage);

        $result = ExamResult::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'exam_name' => $request->exam_name,
            'marks_obtained' => $request->marks_obtained,
            'total_marks' => $request->total_marks,
            'percentage' => $percentage,
            'grade' => $grade,
            'term' => $request->term,
            'academic_year' => $request->academic_year,
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'message' => 'Exam result added successfully',
            'result' => $result->load(['student.user', 'subject'])
        ], 201);
    }

    public function show($id)
    {
        $result = ExamResult::with(['student.user', 'subject'])->find($id);

        if (!$result) {
            return response()->json(['error' => 'Exam result not found'], 404);
        }

        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        $result = ExamResult::find($id);

        if (!$result) {
            return response()->json(['error' => 'Exam result not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'marks_obtained' => 'sometimes|integer|min:0',
            'total_marks' => 'sometimes|integer|min:1',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['marks_obtained', 'total_marks', 'remarks']);

        if (isset($data['marks_obtained']) || isset($data['total_marks'])) {
            $marksObtained = $data['marks_obtained'] ?? $result->marks_obtained;
            $totalMarks = $data['total_marks'] ?? $result->total_marks;
            $percentage = ($marksObtained / $totalMarks) * 100;
            $data['percentage'] = $percentage;
            $data['grade'] = $this->calculateGrade($percentage);
        }

        $result->update($data);

        return response()->json([
            'message' => 'Exam result updated successfully',
            'result' => $result->load(['student.user', 'subject'])
        ]);
    }

    public function destroy($id)
    {
        $result = ExamResult::find($id);

        if (!$result) {
            return response()->json(['error' => 'Exam result not found'], 404);
        }

        $result->delete();

        return response()->json(['message' => 'Exam result deleted successfully']);
    }

    public function getStudentReport($studentId, $academicYear)
    {
        $results = ExamResult::where('student_id', $studentId)
            ->where('academic_year', $academicYear)
            ->with('subject')
            ->get()
            ->groupBy('term');

        $summary = [];
        foreach ($results as $term => $termResults) {
            $totalPercentage = $termResults->sum('percentage');
            $averagePercentage = $totalPercentage / $termResults->count();

            $summary[$term] = [
                'average' => round($averagePercentage, 2),
                'grade' => $this->calculateGrade($averagePercentage),
                'subjects' => $termResults,
            ];
        }

        return response()->json([
            'student_id' => $studentId,
            'academic_year' => $academicYear,
            'summary' => $summary,
            'detailed_results' => $results
        ]);
    }

    private function calculateGrade($percentage)
    {
        if ($percentage >= 75)
            return 'A';
        if ($percentage >= 65)
            return 'B';
        if ($percentage >= 55)
            return 'C';
        if ($percentage >= 35)
            return 'S';
        return 'F';
    }
}