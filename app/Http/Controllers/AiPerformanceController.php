<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\ExamResult;
use App\Models\SubjectSubmission;
use App\Models\Attendence;
use App\Models\AiPerformanceReport;
use App\Models\Grade;
use App\Models\Teacher;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiPerformanceController extends Controller
{
    private $geminiApiKey = ''; 
    private $geminiApiUrl = 'https://generativelanguage.googleapis.com/v1beta/interactions';
    private $geminiModel = 'gemini-3.6-flash';

    /**
     * Generate an AI Performance Report for a student
     */
    public function generateReport(Request $request)
    {
        try {
            $user = auth('api')->user() ?: auth()->user();
            $studentIdInput = $request->input('student_id');
            $academicYear = $request->input('academic_year', date('Y'));
            $term = $request->input('term', 'All Terms');

            if (!$studentIdInput) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student ID is required.'
                ], 400);
            }

            // Resolve student
            $student = Student::with('user')
                ->where('id', $studentIdInput)
                ->orWhere('user_id', $studentIdInput)
                ->orWhere('student_id', $studentIdInput)
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found.'
                ], 404);
            }

            // Authorization & Privacy Check
            if ($user && $user->role === 'student' && $user->id !== $student->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only view and generate your own AI performance report.'
                ], 403);
            }

            if ($user && $user->role === 'teacher') {
                $teacher = Teacher::where('user_id', $user->id)->first();
                if ($teacher) {
                    $assignedGrades = Grade::where('class_teacher_id', $teacher->id)
                        ->orWhere('class_teacher_id', $user->id)
                        ->pluck('name')
                        ->map(fn($g) => trim(str_ireplace('Grade ', '', $g)))
                        ->toArray();

                    $studentGrade = trim(str_ireplace('Grade ', '', (string)$student->grade));
                    if (!empty($assignedGrades) && !in_array($studentGrade, $assignedGrades)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Unauthorized. You can only generate reports for students in your assigned class.'
                        ], 403);
                    }
                }
            }

            // Gather Student Academic Data
            $examResultsQuery = ExamResult::with('subject')
                ->where('student_id', $student->id);
            if ($academicYear && $academicYear !== 'all') {
                $examResultsQuery->where('academic_year', $academicYear);
            }
            if ($term && $term !== 'All Terms' && $term !== 'all') {
                $examResultsQuery->where('term', $term);
            }
            $currentExamResults = $examResultsQuery->get();

            // All historical exams for growth & progress comparison
            $allExamResults = ExamResult::with('subject')
                ->where('student_id', $student->id)
                ->orderBy('academic_year', 'asc')
                ->orderBy('term', 'asc')
                ->get();

            // Submissions & assignments
            $submissions = SubjectSubmission::with('subject')
                ->where('user_id', $student->user_id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Attendance
            $attendance = Attendence::where('user_id', $student->user_id)->get();
            $totalAttendance = $attendance->count();
            $presentAttendance = $attendance->whereIn('status', ['present', 'Present', 'late', 'Late'])->count();
            $attendancePct = $totalAttendance > 0 ? round(($presentAttendance / $totalAttendance) * 100, 1) : null;

            // Metrics calculation
            $metrics = $this->calculateMetrics($currentExamResults, $allExamResults, $submissions, $attendancePct);

            // Generate AI Report via Gemini Interactions API
            $aiData = $this->callGeminiApi($student, $currentExamResults, $allExamResults, $submissions, $metrics, $academicYear, $term);

            // Save report in database
            $report = AiPerformanceReport::create([
                'student_id' => $student->id,
                'user_id' => $student->user_id,
                'generated_by' => $user ? $user->id : null,
                'academic_year' => (string)$academicYear,
                'term' => (string)$term,
                'overall_performance' => $aiData['overall_performance'] ?? '',
                'strengths' => $aiData['strengths'] ?? [],
                'weaknesses' => $aiData['weaknesses'] ?? [],
                'improvement_suggestions' => $aiData['improvement_suggestions'] ?? [],
                'growth_progress' => $aiData['growth_progress'] ?? '',
                'summary' => $aiData['summary'] ?? '',
                'metrics' => $metrics,
                'raw_response' => json_encode($aiData)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'AI Performance Report generated successfully.',
                'data' => $report
            ]);
        } catch (\Exception $e) {
            Log::error('AI Performance Report Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all reports for a student (latest + previous)
     */
    public function getStudentReports(Request $request, $studentId)
    {
        try {
            $user = auth('api')->user() ?: auth()->user();

            $student = Student::with('user')
                ->where('id', $studentId)
                ->orWhere('user_id', $studentId)
                ->orWhere('student_id', $studentId)
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found.'
                ], 404);
            }

            // Authorization & Privacy Check
            if ($user && $user->role === 'student' && $user->id !== $student->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only view your own report.'
                ], 403);
            }

            $reports = AiPerformanceReport::with('generator')
                ->where('student_id', $student->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'student' => [
                        'id' => $student->id,
                        'name' => $student->user ? $student->user->name : 'Student',
                        'student_id' => $student->student_id,
                        'grade' => $student->grade,
                        'class' => $student->class
                    ],
                    'latest' => $reports->first(),
                    'history' => $reports
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reports: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Student App endpoint: Get own latest & past reports
     */
    public function getOwnReport(Request $request)
    {
        $user = auth('api')->user() ?: auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $student = Student::where('user_id', $user->id)->first() ?? Student::where('id', $user->id)->first();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student record not found.'], 404);
        }

        return $this->getStudentReports($request, $student->id);
    }

    /**
     * Student App endpoint: Generate report for self
     */
    public function generateOwnReport(Request $request)
    {
        $user = auth('api')->user() ?: auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $student = Student::where('user_id', $user->id)->first() ?? Student::where('id', $user->id)->first();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student record not found.'], 404);
        }

        $request->merge(['student_id' => $student->id]);
        return $this->generateReport($request);
    }

    /**
     * Calculate summary metrics from raw marks and submissions
     */
    private function calculateMetrics($currentExams, $allExams, $submissions, $attendancePct)
    {
        $examCount = $currentExams->count();
        $averageMarks = $examCount > 0 ? round($currentExams->avg('marks'), 1) : null;

        $subjectMarks = [];
        foreach ($currentExams as $exam) {
            $sName = $exam->subject ? $exam->subject->subject_name : 'Subject';
            $subjectMarks[$sName] = $exam->marks;
        }

        arsort($subjectMarks);
        $highestSubject = !empty($subjectMarks) ? array_key_first($subjectMarks) : null;
        $lowestSubject = !empty($subjectMarks) ? array_key_last($subjectMarks) : null;

        // Historical term averages for growth tracking
        $termAverages = [];
        foreach ($allExams as $exam) {
            $key = ($exam->academic_year ?? 'Year') . ' - ' . ($exam->term ?? 'Term');
            if (!isset($termAverages[$key])) {
                $termAverages[$key] = ['sum' => 0, 'count' => 0];
            }
            $termAverages[$key]['sum'] += (float)$exam->marks;
            $termAverages[$key]['count']++;
        }

        $termTrends = [];
        foreach ($termAverages as $termKey => $data) {
            $termTrends[$termKey] = round($data['sum'] / ($data['count'] ?: 1), 1);
        }

        // Submissions metrics
        $totalSubmissions = $submissions->count();
        $gradedSubmissions = $submissions->whereNotNull('grade')->count();

        return [
            'total_exams' => $examCount,
            'average_marks' => $averageMarks,
            'subject_marks' => $subjectMarks,
            'highest_subject' => $highestSubject,
            'lowest_subject' => $lowestSubject,
            'historical_term_averages' => $termTrends,
            'total_assignments_submitted' => $totalSubmissions,
            'graded_assignments' => $gradedSubmissions,
            'attendance_percentage' => $attendancePct
        ];
    }

    /**
     * Call Gemini Interactions API
     */
    private function callGeminiApi($student, $currentExams, $allExams, $submissions, $metrics, $academicYear, $term)
    {
        $studentName = $student->user ? $student->user->name : 'Student';
        $gradeName = 'Grade ' . trim(str_ireplace('Grade ', '', (string)$student->grade));

        // Format exam marks
        $examLines = [];
        foreach ($currentExams as $res) {
            $sName = $res->subject ? $res->subject->subject_name : 'Subject';
            $examLines[] = "- {$sName}: {$res->marks}/100 (Grade: {$res->grade})";
        }
        $examDataText = !empty($examLines) ? implode("\n", $examLines) : "No exam marks recorded yet for this selection.";

        // Format historical term trends
        $historyLines = [];
        foreach (($metrics['historical_term_averages'] ?? []) as $termKey => $avg) {
            $historyLines[] = "- {$termKey}: Average {$avg}%";
        }
        $historyText = !empty($historyLines) ? implode("\n", $historyLines) : "No previous terms recorded.";

        // Format assignments
        $assignmentLines = [];
        foreach ($submissions->take(8) as $sub) {
            $sName = $sub->subject ? $sub->subject->subject_name : 'Assignment';
            $gradeVal = $sub->grade ? "Grade: {$sub->grade}" : "Ungraded";
            $fb = $sub->feedback ? "Feedback: \"{$sub->feedback}\"" : "";
            $assignmentLines[] = "- {$sName} ({$gradeVal}) {$fb}";
        }
        $assignmentText = !empty($assignmentLines) ? implode("\n", $assignmentLines) : "No assignment submissions recorded.";

        $attendanceText = $metrics['attendance_percentage'] !== null ? "{$metrics['attendance_percentage']}%" : "Not recorded";

        $prompt = <<<EOT
You are an expert academic counselor, teacher, and child educational psychologist analyzing a student's performance.
Student Profile:
- Name: {$studentName}
- Grade: {$gradeName}
- Academic Year: {$academicYear}
- Term: {$term}

Current Exam Results:
{$examDataText}

Historical Term Performance Trends:
{$historyText}

Assignment Submissions & Homework:
{$assignmentText}

TASK:
Generate a thorough, constructive, encouraging, and easy-to-understand Academic Performance Insight Report for the student and their parents.
Focus strictly on the student's exam achievements, subject mastery, homework submissions, and study improvements. Do NOT mention attendance or numerical percentage averages.
Use simple, clear, professional yet warm language that parents and students can easily comprehend.

You MUST respond strictly with a valid JSON object without any other text, matching this exact JSON structure:
{
  "overall_performance": "A detailed 1-2 paragraph description of the student's overall performance, academic dedication, and current standing.",
  "strengths": [
    "Highlight specific strong subjects or areas with marks/grades and reasoning",
    "Praise homework submission consistency or good habits",
    "Additional academic strengths"
  ],
  "weaknesses": [
    "Identify specific subjects or topics where marks were low or need attention",
    "Identify study habit or submission gaps if any",
    "Specific areas requiring focus"
  ],
  "improvement_suggestions": [
    "Specific actionable recommendation 1 for student & parent",
    "Specific actionable recommendation 2 (e.g. daily practice in weak subjects)",
    "Specific actionable recommendation 3 (e.g. revision strategies or asking teachers for help)"
  ],
  "growth_progress": "A clear comparison with previous results: whether performance improved, maintained consistent, or declined, with positive encouragement for future trajectory.",
  "summary": "A concise concluding summary encouraging the student."
}
EOT;

        try {
            $ch = curl_init($this->geminiApiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'x-goog-api-key: ' . $this->geminiApiKey,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'model' => $this->geminiModel,
                'input' => $prompt
            ]));

            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                Log::warning("AI API curl error: {$err}. Using fallback generator.");
                return $this->generateFallbackReport($student, $metrics, $currentExams, $academicYear, $term);
            }

            $decoded = json_decode($response, true);
            $rawText = null;

            if (isset($decoded['steps']) && is_array($decoded['steps'])) {
                foreach ($decoded['steps'] as $step) {
                    if (($step['type'] ?? '') === 'model_output' && !empty($step['content'])) {
                        foreach ($step['content'] as $c) {
                            if (($c['type'] ?? '') === 'text' && !empty($c['text'])) {
                                $rawText = $c['text'];
                                break 2;
                            }
                        }
                    }
                }
            }

            if (!$rawText && isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
                $rawText = $decoded['candidates'][0]['content']['parts'][0]['text'];
            }

            if ($rawText) {
                // Strip markdown code fences if model wrapped response in ```json ... ```
                $cleanJson = preg_replace('/^```(?:json)?\s*/i', '', trim($rawText));
                $cleanJson = preg_replace('/\s*```$/', '', $cleanJson);

                $parsed = json_decode($cleanJson, true);
                if (is_array($parsed) && isset($parsed['overall_performance'])) {
                    return $parsed;
                }
            }

            Log::warning("AI raw text was not JSON. Using fallback generator.");
            return $this->generateFallbackReport($student, $metrics, $currentExams, $academicYear, $term);
        } catch (\Exception $e) {
            Log::error("AI API Exception: " . $e->getMessage());
            return $this->generateFallbackReport($student, $metrics, $currentExams, $academicYear, $term);
        }
    }

    /**
     * Smart fallback analysis if AI API is temporarily unavailable
     */
    private function generateFallbackReport($student, $metrics, $currentExams, $academicYear, $term)
    {
        $name = $student->user ? $student->user->name : 'Student';
        $highest = $metrics['highest_subject'];
        $lowest = $metrics['lowest_subject'];

        $overall = "{$name} has demonstrated commendable dedication across {$academicYear}" . ($term ? " ({$term})" : "") . ". "
            . "{$name} is participating actively in school coursework, exams, and assignment submissions with clear potential to achieve higher grades with targeted revision.";

        $strengths = [];
        if ($highest) {
            $strengths[] = "Strong performance in {$highest}" . (!empty($metrics['subject_marks'][$highest]) ? " ({$metrics['subject_marks'][$highest]} marks)" : "") . ".";
        }
        if (($metrics['total_assignments_submitted'] ?? 0) > 0) {
            $strengths[] = "Active homework and assignment submission record ({$metrics['total_assignments_submitted']} assignments submitted).";
        }
        $strengths[] = "Shows enthusiasm and regular participation in class coursework.";

        $weaknesses = [];
        if ($lowest && $lowest !== $highest) {
            $weaknesses[] = "Lower marks recorded in {$lowest}" . (!empty($metrics['subject_marks'][$lowest]) ? " ({$metrics['subject_marks'][$lowest]} marks)" : "") . ", requiring dedicated practice.";
        }
        $weaknesses[] = "Room for improvement in exam preparation and timed revision exercises.";

        $suggestions = [
            "Dedicate 30-45 minutes daily to review subject topics" . ($lowest ? " especially in {$lowest}" : "") . ".",
            "Review corrected exam papers and assignment feedback to learn from mistakes.",
            "Maintain an active study timetable and consult subject teachers when difficult questions arise."
        ];

        $growth = "Compared to historical benchmarks, {$name} has demonstrated a stable learning trajectory. With focused practice in target subjects, the student is well-positioned for continuous grade advancement.";

        $summary = "{$name} possesses significant potential. Consistent practice and supportive guidance at home and school will ensure continuous academic success.";

        return [
            'overall_performance' => $overall,
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
            'improvement_suggestions' => $suggestions,
            'growth_progress' => $growth,
            'summary' => $summary
        ];
    }
}
