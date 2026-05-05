<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        // Fetch all attempts with exercise loaded
        $attempts = Attempt::where('student_id', $userId)
            ->with('exercise:id,title,type')
            ->orderByDesc('created_at')
            ->get();

        // Group by exercise_id to compute per-exercise stats
        $grouped = $attempts->groupBy('exercise_id');

        $attemptRows = $grouped->map(function ($group) {
            $latest  = $group->first();
            $bestRaw = $group->max('score');
            $bestMax = $group->first()->max_score;

            return [
                'id'             => $latest->id,
                'exercise_title' => $latest->exercise?->title ?? 'Unknown',
                'score'          => $latest->score,
                'max_score'      => $latest->max_score,
                'best_score'     => $bestRaw,
                'attempt_count'  => $group->count(),
                'attempted_at'   => $latest->created_at,
            ];
        })->values();

        // Unique exercises completed
        $exercisesCompleted = $grouped->count();

        // Average score percentage across all attempts
        $avgScore = 0;
        if ($attempts->count() > 0) {
            $totalPct = $attempts->sum(fn($a) => $a->max_score > 0 ? ($a->score / $a->max_score) * 100 : 0);
            $avgScore = round($totalPct / $attempts->count(), 1);
        }

        return response()->json([
            'stats' => [
                'exercises_completed' => $exercisesCompleted,
                'avg_score'           => $avgScore,
            ],
            'attempts' => $attemptRows,
        ]);
    }
}
