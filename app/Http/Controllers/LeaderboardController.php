<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class LeaderboardController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::where('role', 'student')
            ->where('status', 'approved')
            ->orderByDesc('points')
            ->take(10)
            ->get(['id', 'name', 'points', 'streak_days']);

        $ranked = $users->values()->map(function ($user, int $index) {
            return array_merge($user->toArray(), ['rank' => $index + 1]);
        });

        return response()->json($ranked);
    }
}
