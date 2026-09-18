<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendence;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Retrieve all users with their details.
     */
    public function getUsers(Request $request)
    {
        try {
            $user = auth('api')->user();
            $query = User::with(['student.parent', 'teacher'])->orderBy('name', 'asc');

            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            $users = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve all teachers with their specialized profiles.
     */
    public function getTeachers()
    {
        try {
            // Retrieve users whose role is teacher, eager-load the teacher model
            $teachers = User::where('role', 'teacher')
                ->with('teacher')
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Teachers retrieved successfully',
                'data' => $teachers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving teachers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve all attendance records including the user detail.
     * Supports optional ?grade=X query parameter to filter by student grade.
     */
    public function getAttendanceRecords(Request $request)
    {
        try {
            $user = auth('api')->user();
            $query = Attendence::with(['user.student', 'user.teacher']);

            if ($user && $user->role === 'teacher') {
                $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
                if ($teacher) {
                    $grades = \App\Models\Grade::where('class_teacher_id', $teacher->id)->get();
                    if ($grades->isEmpty()) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Attendance records retrieved successfully',
                            'data' => []
                        ], 200);
                    }

                    $query->whereHas('user.student', function ($q) use ($grades) {
                        $q->where(function ($sq) use ($grades) {
                            foreach ($grades as $idx => $g) {
                                // Extract raw grade number/name without 'Grade ' prefix
                                $rawGrade = trim(str_ireplace('Grade ', '', $g->name));

                                if ($idx === 0) {
                                    $sq->where(function($q1) use ($rawGrade, $g) {
                                        $q1->where('grade', $rawGrade)
                                           ->orWhere('grade', $g->name);
                                    })->where('class', $g->section);
                                } else {
                                    $sq->orWhere(function ($ssq) use ($rawGrade, $g) {
                                        $ssq->where(function($q1) use ($rawGrade, $g) {
                                            $q1->where('grade', $rawGrade)
                                               ->orWhere('grade', $g->name);
                                        })->where('class', $g->section);
                                    });
                                }
                            }
                        });
                    });
                }
            }

            // Filter by grade if provided (students only)
            if ($request->filled('grade')) {
                $gradeFilter = $request->grade;
                $query->whereHas('user.student', function ($q) use ($gradeFilter) {
                    $q->where('grade', $gradeFilter);
                });
                // Only return student attendance when filtering by grade
                $query->whereHas('user', function ($q) {
                    $q->where('role', 'student');
                });
            }

            $records = $query->orderBy('date', 'desc')
                ->orderBy('in_time', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Attendance records retrieved successfully',
                'data' => $records
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving attendance records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a teacher's profile details (including salary).
     */
    public function updateTeacher(Request $request, $id)
    {
        try {
            $user = User::where('role', 'teacher')->findOrFail($id);
            
            // Validate incoming request
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string',
                'address' => 'nullable|string',
                'qualification' => 'sometimes|string|max:255',
                'subject_specialization' => 'sometimes|string|max:255',
                'salary' => 'sometimes|numeric|min:0',
            ]);

            // Update user details
            $user->update($request->only(['name', 'email', 'phone', 'address']));

            // Update teacher specific details
            if ($user->teacher) {
                $user->teacher->update($request->only(['qualification', 'subject_specialization', 'salary']));
            }

            return response()->json([
                'success' => true,
                'message' => 'Teacher profile updated successfully',
                'data' => $user->load('teacher')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating teacher profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve all grades. If no grades exist, seed Grades 1-12 dynamically.
     */
    public function getGrades()
    {
        try {
            $grades = \App\Models\Grade::with(['classTeacher.user'])->orderByRaw('CAST(SUBSTRING(name, 7) AS UNSIGNED) ASC')->get();

            if ($grades->isEmpty()) {
                // Initialize default grades if table is empty
                for ($i = 1; $i <= 12; $i++) {
                    \App\Models\Grade::create([
                        'name' => 'Grade ' . $i,
                        'section' => 'A',
                        'class_teacher_id' => null
                    ]);
                }
                $grades = \App\Models\Grade::with(['classTeacher.user'])->orderByRaw('CAST(SUBSTRING(name, 7) AS UNSIGNED) ASC')->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'Grades retrieved successfully',
                'data' => $grades
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving grades',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign a class teacher to a specific grade.
     */
    public function assignTeacherToGrade(Request $request, $id)
    {
        try {
            $request->validate([
                'class_teacher_id' => 'nullable|exists:teachers,id'
            ]);

            $grade = \App\Models\Grade::findOrFail($id);
            $grade->class_teacher_id = $request->class_teacher_id;
            $grade->save();

            return response()->json([
                'success' => true,
                'message' => 'Class teacher assigned successfully',
                'data' => $grade->load(['classTeacher.user'])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error assigning class teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new user (admin panel).
     */
    public function createUser(Request $request)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role'     => 'required|in:admin,teacher,student',
                'phone'    => 'nullable|string',
                'address'  => 'nullable|string',
                'dob'      => 'nullable|date',
                // student fields
                'grade'          => 'nullable|string',
                'class'          => 'nullable|string',
                'parent_name'    => 'nullable|string',
                'parent_email'   => 'nullable|email',
                'parent_phone'   => 'nullable|string',
                // teacher fields
                'qualification'          => 'nullable|string',
                'subject_specialization' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role'     => $request->role,
                'phone'    => $request->phone,
                'address'  => $request->address,
                'dob'      => $request->dob,
            ]);

            if ($request->role === 'student') {
                \App\Models\Student::create([
                    'user_id'      => $user->id,
                    'student_id'   => 'STU' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'grade'        => $request->grade ?? '1',
                    'class'        => $request->class ?? 'A',
                    'parent_name'  => $request->parent_name,
                    'parent_email' => $request->parent_email,
                    'parent_phone' => $request->parent_phone,
                ]);
            } elseif ($request->role === 'teacher') {
                \App\Models\Teacher::create([
                    'user_id'                => $user->id,
                    'employee_id'            => 'TCH' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'qualification'          => $request->qualification ?? '',
                    'subject_specialization' => $request->subject_specialization ?? '',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data'    => $user->load(['student', 'teacher'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a user's basic profile.
     */
    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'name'    => 'sometimes|string|max:255',
                'email'   => 'sometimes|email|unique:users,email,' . $id,
                'phone'   => 'nullable|string',
                'address' => 'nullable|string',
                'dob'     => 'nullable|date',
                // student specific
                'grade'                  => 'nullable|string',
                'class'                  => 'nullable|string',
                'parent_name'            => 'nullable|string',
                'parent_email'           => 'nullable|email',
                'parent_phone'           => 'nullable|string',
                // teacher specific
                'qualification'          => 'nullable|string',
                'subject_specialization' => 'nullable|string',
                'salary'                 => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $user->update($request->only(['name', 'email', 'phone', 'address', 'dob']));

            if ($user->role === 'student' && $user->student) {
                $updateData = $request->only(['grade', 'class', 'parent_name', 'parent_email', 'parent_phone']);
                $user->student->update($updateData);
            } elseif ($user->role === 'teacher' && $user->teacher) {
                $user->teacher->update($request->only(['qualification', 'subject_specialization', 'salary']));
            }

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data'    => $user->load(['student', 'teacher'])
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a user.
     */
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $currentUser = auth('api')->user();

            if ($currentUser && $currentUser->id == $id) {
                return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
            }

            $user->delete();

            return response()->json(['success' => true, 'message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Change a user's password (admin action).
     */
    public function changeUserPassword(Request $request, $id)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'new_password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $user = User::findOrFail($id);
            $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->new_password)]);

            return response()->json(['success' => true, 'message' => 'Password changed successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

