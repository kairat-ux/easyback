<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $role   = $request->input('role', 'student');
        $status = $role === 'teacher' ? 'pending' : 'approved';

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
            'role'     => $role,
            'status'   => $status,
        ]);

        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Throwable) {
            // mail failure must never block registration
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $message = $user->role === 'teacher'
            ? 'Registration successful. Wait for admin approval.'
            : 'Registration successful. You can login now.';

        return response()->json([
            'message' => $message,
            'token'   => $token,
            'user'    => [
                'id'                 => $user->id,
                'name'               => $user->name,
                'email'              => $user->email,
                'role'               => $user->role,
                'status'             => $user->status,
                'preferred_language' => $user->preferred_language,
            ],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if ($user->status === 'pending') {
            return response()->json(['error' => 'Account pending admin approval'], 403);
        }

        if ($user->status === 'rejected') {
            return response()->json(['error' => 'Account was rejected'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'                 => $user->id,
                'name'               => $user->name,
                'email'              => $user->email,
                'role'               => $user->role,
                'status'             => $user->status,
                'preferred_language' => $user->preferred_language,
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user()->only([
            'id', 'name', 'email', 'role', 'status', 'avatar',
            'preferred_language', 'points', 'streak_days', 'last_activity_date', 'created_at',
        ]));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function updateLanguage(Request $request): JsonResponse
    {
        $request->validate([
            'language' => 'required|in:en,ru,kz',
        ]);

        $request->user()->update(['preferred_language' => $request->language]);

        return response()->json(['message' => 'Language updated successfully']);
    }
}
