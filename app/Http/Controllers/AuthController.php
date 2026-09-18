<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,teacher,student,parent',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'dob' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'dob' => $request->dob,
        ]);

        // Create role-specific record
        if ($request->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'student_id' => 'STU' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'grade' => $request->grade ?? '1',
                'class' => $request->class ?? 'A',
            ]);
        } elseif ($request->role === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'employee_id' => 'TCH' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'qualification' => $request->qualification ?? '',
                'subject_specialization' => $request->subject_specialization ?? '',
            ]);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();

        if ($user->role === 'student') {
            $user->load('student');
        } elseif ($user->role === 'teacher') {
            $user->load('teacher');
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
            'role' => $user->role
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me()
    {
        $user = auth()->user();

        if ($user->role === 'student') {
            $user->load('student');
        } elseif ($user->role === 'teacher') {
            $user->load('teacher');
        }

        return response()->json($user);
    }

    public function refresh()
    {
        return response()->json([
            'token' => JWTAuth::refresh(JWTAuth::getToken())
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user->update([
            'fcm_token' => $request->input('fcm_token')
        ]);

        return response()->json([
            'message' => 'FCM Token updated successfully',
            'fcm_token' => $user->fcm_token
        ]);
    }

    public function getUserDetails(Request $request)
    {
        $userIdInput = $request->input('user_id');
        $resolvedUserId = null;
        if (is_string($userIdInput)) {
            if (str_starts_with($userIdInput, 'student:')) {
                $studentId = (int) substr($userIdInput, strlen('student:'));
                $student = \App\Models\Student::find($studentId);
                if ($student) {
                    $resolvedUserId = $student->user_id;
                }
            } elseif (str_starts_with($userIdInput, 'teacher:')) {
                $resolvedUserId = (int) substr($userIdInput, strlen('teacher:'));
            } else {
                $resolvedUserId = (int) $userIdInput;
            }
        } else {
            $resolvedUserId = (int) $userIdInput;
        }
        $request->merge(['user_id' => $resolvedUserId]);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($request->user_id);

        if ($user->role === 'student') {
            $user->load('student');
        } elseif ($user->role === 'teacher') {
            $user->load('teacher');
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}