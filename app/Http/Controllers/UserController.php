<?php

namespace App\Http\Controllers;

use App\Mail\TeacherApprovedMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function pendingCount(): JsonResponse
    {
        return response()->json(['count' => User::where('status', 'pending')->count()]);
    }

    public function index(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'role', 'status', 'points', 'streak_days', 'created_at')
            ->latest()
            ->get();

        $stats = [
            'total'    => User::count(),
            'students' => User::where('role', 'student')->count(),
            'teachers' => User::where('role', 'teacher')->count(),
            'pending'  => User::where('status', 'pending')->count(),
        ];

        return response()->json(['users' => $users, 'stats' => $stats]);
    }

    public function approve(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'approved']);

        Mail::to($user->email)->send(new TeacherApprovedMail($user));

        return response()->json(['message' => 'User approved successfully', 'user' => $user]);
    }

    public function reject(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'rejected']);

        return response()->json(['message' => 'User rejected', 'user' => $user]);
    }

    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return response()->json(['error' => 'Cannot delete admin account'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
