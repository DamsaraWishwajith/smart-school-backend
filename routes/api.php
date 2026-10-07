<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\SimpleTimetableController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubjectSubmissionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\MealPlanController;

// Temporary migration route
Route::get('/temp-migrate', function() {
    if (!\Illuminate\Support\Facades\Schema::hasColumn('subjects', 'teacher_id')) {
        \Illuminate\Support\Facades\Schema::table('subjects', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->nullable();
        });
        return response()->json(['status' => 'Migrated teacher_id to subjects table.']);
    }
    return response()->json(['status' => 'Column already exists.']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/attendence/mark', [AttendenceController::class, 'mark']);
Route::post('/attendence/user', [AttendenceController::class, 'getUserAttendance']);

Route::post('/simple-timetables', [SimpleTimetableController::class, 'store']);
Route::get('/simple-timetables', [SimpleTimetableController::class, 'index']);
Route::get('/simple-timetables/{day}', [SimpleTimetableController::class, 'show']);

// Teacher timetable from simple_timetables (same source as admin web panel)
Route::get('/teacher-timetable/{userId}', [SimpleTimetableController::class, 'getByTeacher']);
Route::post('/user-details', [AuthController::class, 'getUserDetails']);

Route::post('/subjects', [SubjectController::class, 'store']);
Route::get('/subjects', [SubjectController::class, 'index']);
Route::put('/subjects/{id}', [SubjectController::class, 'update']);
Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']);

Route::post('/subject-submissions', [SubjectSubmissionController::class, 'store']);
Route::get('/subject-submissions', [SubjectSubmissionController::class, 'index']);
Route::put('/subject-submissions/{id}/grade', [SubjectSubmissionController::class, 'grade']);

// Gallery Routes (Event Media)
Route::get('/galleries', [\App\Http\Controllers\GalleryController::class, 'index']);
Route::post('/galleries', [\App\Http\Controllers\GalleryController::class, 'store']);
Route::post('/galleries/{id}/add-images', [\App\Http\Controllers\GalleryController::class, 'addImages']);
Route::get('/galleries/{id}', [\App\Http\Controllers\GalleryController::class, 'show']);
Route::delete('/galleries/{id}', [\App\Http\Controllers\GalleryController::class, 'destroy']);
Route::delete('/gallery-images/{id}', [\App\Http\Controllers\GalleryController::class, 'deleteImage']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/user/fcm-token', [AuthController::class, 'updateFcmToken']);

    // Students
    Route::get('/students/{id}/dashboard', [StudentController::class, 'getDashboard']);
    Route::get('/students/{id}/attendance', [StudentController::class, 'getAttendance']);
    Route::get('/students/{id}/timetable', [StudentController::class, 'getTimetable']);
    Route::get('/students/{id}/results', [StudentController::class, 'getResults']);
    Route::get('/students/{id}/payments', [StudentController::class, 'getPayments']);

    // Attendance
    Route::apiResource('attendance', AttendanceController::class);
    Route::post('/attendance/mark-qr', [AttendanceController::class, 'markQR']);

    // Timetable

// API routes
Route::get('/timetables', [TimetableController::class, 'getTimetables']);
Route::get('/timetables/formatted', [TimetableController::class, 'getTimetablesFormatted']);
Route::get('/timetables/day/{day}', [TimetableController::class, 'getTimetablesByDay']);
Route::get('/timetables/grade/{gradeId}', [TimetableController::class, 'getTimetablesByGrade']);

    // Materials
    Route::apiResource('materials', MaterialController::class);

    // Assignments
    Route::apiResource('assignments', AssignmentController::class);
    Route::post('/assignments/{id}/submit', [AssignmentController::class, 'submit']);
    Route::put('/submissions/{id}/grade', [AssignmentController::class, 'grade']);

    // Fees
    Route::apiResource('fees', FeeController::class);
    Route::post('/fees/pay', [FeeController::class, 'processPayment']);
    Route::get('/payments/student/{student_id}', [FeeController::class, 'getPaymentHistory']);
    Route::post('/admin/students/{id}/toggle-fee/{fee_id}', [FeeController::class, 'toggleStudentFeeStatus']);
    Route::get('/admin/fees/monthly-status', [FeeController::class, 'getMonthlyStatus']);

    // Notices & Emergency Alerts
    Route::apiResource('notices', NoticeController::class);
    Route::post('/emergency-alert', [NoticeController::class, 'sendEmergencyAlert']);
    Route::get('/emergency-alerts', [NoticeController::class, 'getEmergencyAlerts']);

    // Events
    Route::apiResource('events', EventController::class);

    // Meal Plan
    Route::get('/meal-plans', [MealPlanController::class, 'index']);
    Route::post('/meal-plans', [MealPlanController::class, 'store']);
    Route::get('/meal-plans/{day}', [MealPlanController::class, 'show']);

    // Complaints
    Route::apiResource('complaints', ComplaintController::class);

    // Leave Requests
    Route::get('/leaves', [\App\Http\Controllers\LeaveRequestController::class, 'index']);
    Route::get('/leaves/my', [\App\Http\Controllers\LeaveRequestController::class, 'myRequests']);
    Route::post('/leaves', [\App\Http\Controllers\LeaveRequestController::class, 'store']);
    Route::put('/leaves/{id}/respond', [\App\Http\Controllers\LeaveRequestController::class, 'respond']);

    // Messages
    Route::get('/messages/conversations', [MessageController::class, 'getConversations']);
    Route::get('/messages/with/{other_user_id}', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::put('/messages/{id}/read', [MessageController::class, 'markAsRead']);

    // Exam Results
    Route::apiResource('exam-results', ExamResultController::class);
    Route::get('/exam-results/student/{studentId}/{academicYear}', [ExamResultController::class, 'getStudentReport']);

    // Teacher salaries
    Route::get('/teacher/salaries', [SalaryController::class, 'getTeacherSalaryHistory']);

    // Admin
    Route::get('/admin/users', [AdminController::class, 'getUsers']);
    Route::post('/admin/users', [AdminController::class, 'createUser']);
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
    Route::put('/admin/users/{id}/password', [AdminController::class, 'changeUserPassword']);
    Route::get('/admin/teachers', [AdminController::class, 'getTeachers']);
    Route::put('/admin/teachers/{id}', [AdminController::class, 'updateTeacher']);
    Route::get('/admin/attendance', [AdminController::class, 'getAttendanceRecords']);
    Route::get('/admin/grades', [AdminController::class, 'getGrades']);
    Route::put('/admin/grades/{id}/assign-teacher', [AdminController::class, 'assignTeacherToGrade']);
    Route::get('/admin/salaries/monthly-status', [SalaryController::class, 'getMonthlyStatus']);
    Route::post('/admin/salaries/toggle', [SalaryController::class, 'toggleSalaryStatus']);
    Route::put('/admin/salaries/basic-salary', [SalaryController::class, 'updateBasicSalary']);

    // AI Performance Reports
    Route::get('/ai-performance/reports/{studentId}', [\App\Http\Controllers\AiPerformanceController::class, 'getStudentReports']);
    Route::post('/ai-performance/generate', [\App\Http\Controllers\AiPerformanceController::class, 'generateReport']);
    Route::get('/student/ai-performance', [\App\Http\Controllers\AiPerformanceController::class, 'getOwnReport']);
    Route::post('/student/ai-performance/generate', [\App\Http\Controllers\AiPerformanceController::class, 'generateOwnReport']);
});

