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
        if ($user && $user->role === 'teacher' && !$request->has('all')) {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            if ($teacher) {
                $grades = \App\Models\Grade::where('class_teacher_id', $teacher->id)->get();
                if ($grades->isNotEmpty()) {
                    $query->whereHas('student', function ($q) use ($grades) {
                        $q->where(function ($sq) use ($grades) {
                            foreach ($grades as $idx => $g) {
                                $rawGrade = trim(str_ireplace('Grade ', '', $g->name));
                                if ($idx === 0) {
                                    $sq->where(function($q1) use ($rawGrade, $g) {
                                        $q1->where('grade', $rawGrade)
                                           ->orWhere('grade', $g->name);
                                    });
                                } else {
                                    $sq->orWhere(function ($ssq) use ($rawGrade, $g) {
                                        $ssq->where(function($q1) use ($rawGrade, $g) {
                                            $q1->where('grade', $rawGrade)
                                               ->orWhere('grade', $g->name);
                                        });
                                    });
                                }
                            }
                        });
                    });
                }
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