<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function adminCharts(): JsonResponse
    {
        // BAR: top 7 exercises by attempt count
        $barData = Attempt::select('exercise_id', DB::raw('COUNT(*) as count'))
            ->with('exercise:id,title')
            ->groupBy('exercise_id')
            ->orderByDesc('count')
            ->limit(7)
            ->get()
            ->map(fn($a) => [
                'label' => $a->exercise?->title ?? 'Unknown',
                'count' => $a->count,
            ])
            ->values();

        // PIE: users grouped by role
        $pieData = User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get();

        // POLAR AREA: average score % by exercise type
        $polarData = Attempt::select(
                'exercises.type',
                DB::raw('ROUND(AVG(CAST(attempts.score AS FLOAT) / NULLIF(attempts.max_score, 0) * 100), 1) as avg_score')
            )
            ->join('exercises', 'attempts.exercise_id', '=', 'exercises.id')
            ->groupBy('exercises.type')
            ->get();

        // LINE: registrations last 30 days grouped by date
        $lineData = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'bar'   => $barData,
            'pie'   => $pieData,
            'polar' => $polarData,
            'line'  => $lineData,
        ]);
    }

    public function studentCharts(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        // BAR: student's last 10 attempts with score %
        $barData = Attempt::where('student_id', $userId)
            ->with('exercise:id,title')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn($a) => [
                'label' => $a->exercise?->title ?? 'Unknown',
                'score' => $a->max_score > 0 ? round(($a->score / $a->max_score) * 100, 1) : 0,
            ])
            ->values();

        // PIE: completed vs not started
        $totalExercises = Exercise::count();
        $doneCount      = Attempt::where('student_id', $userId)->distinct('exercise_id')->count('exercise_id');
        $pieData = [
            ['label' => 'Completed',   'count' => $doneCount],
            ['label' => 'Not started', 'count' => max(0, $totalExercises - $doneCount)],
        ];

        // POLAR AREA: student's avg score by exercise type
        $polarData = Attempt::select(
                'exercises.type',
                DB::raw('ROUND(AVG(CAST(attempts.score AS FLOAT) / NULLIF(attempts.max_score, 0) * 100), 1) as avg_score')
            )
            ->join('exercises', 'attempts.exercise_id', '=', 'exercises.id')
            ->where('attempts.student_id', $userId)
            ->groupBy('exercises.type')
            ->get();

        // LINE: student's avg score by date last 14 days
        $lineData = Attempt::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('ROUND(AVG(CAST(score AS FLOAT) / NULLIF(max_score, 0) * 100), 1) as avg_score')
            )
            ->where('student_id', $userId)
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'bar'   => $barData,
            'pie'   => $pieData,
            'polar' => $polarData,
            'line'  => $lineData,
        ]);
    }
}
