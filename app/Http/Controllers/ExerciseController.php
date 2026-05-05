<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExerciseRequest;
use App\Models\Attempt;
use App\Models\Exercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index(): JsonResponse
    {
        $exercises = Exercise::with(['teacher:id,name', 'lesson:id,title'])->latest()->get();

        return response()->json($exercises);
    }

    public function show(int $id): JsonResponse
    {
        $exercise = Exercise::with(['teacher:id,name', 'lesson:id,title'])->findOrFail($id);

        return response()->json($exercise);
    }

    public function store(ExerciseRequest $request): JsonResponse
    {
        $exercise = Exercise::create(array_merge(
            $request->validated(),
            ['teacher_id' => auth()->id()]
        ));

        return response()->json($exercise->load(['teacher:id,name', 'lesson:id,title']), 201);
    }

    public function update(ExerciseRequest $request, int $id): JsonResponse
    {
        $exercise = Exercise::findOrFail($id);

        $user = auth()->user();
        if ($exercise->teacher_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $exercise->update($request->validated());

        return response()->json($exercise->load(['teacher:id,name', 'lesson:id,title']));
    }

    public function destroy(int $id): JsonResponse
    {
        $exercise = Exercise::findOrFail($id);

        $user = auth()->user();
        if ($exercise->teacher_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $exercise->delete();

        return response()->json(['message' => 'Exercise deleted successfully']);
    }

    public function submit(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $exercise = Exercise::findOrFail($id);
        $questions = $exercise->questions;
        $submittedAnswers = $request->answers;
        $score = 0;
        $maxScore = count($questions);

        foreach ($questions as $index => $question) {
            $submitted = $submittedAnswers[$index] ?? null;

            if ($exercise->type === 'multiple_choice') {
                if ($submitted !== null && (string) $submitted === (string) ($question['correct_answer'] ?? '')) {
                    $score++;
                }
            } elseif ($exercise->type === 'fill_blank') {
                $correct = strtolower(trim($question['correct_answer'] ?? ''));
                $given   = strtolower(trim((string) ($submitted ?? '')));
                if ($given !== '' && $given === $correct) {
                    $score++;
                }
            } elseif ($exercise->type === 'matching') {
                $correctRight = $question['right'] ?? null;
                $submittedRight = is_array($submitted) ? ($submitted['right'] ?? null) : $submitted;
                if ($submittedRight !== null && (string) $submittedRight === (string) $correctRight) {
                    $score++;
                }
            }
        }

        $attempt = Attempt::create([
            'student_id'  => auth()->id(),
            'exercise_id' => $exercise->id,
            'score'       => $score,
            'max_score'   => $maxScore,
            'answers'     => $submittedAnswers,
        ]);

        // --- Gamification: award points based on difficulty ---
        $pointsPerCorrect = match ($exercise->difficulty ?? 'medium') {
            'easy'  => 5,
            'hard'  => 20,
            default => 10, // medium
        };
        $pointsEarned = $score * $pointsPerCorrect;

        // --- Streak logic ---
        $student  = auth()->user();
        $today    = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $lastDate = $student->last_activity_date
            ? $student->last_activity_date->toDateString()
            : null;

        if ($lastDate === $yesterday) {
            $newStreak = $student->streak_days + 1;
        } elseif ($lastDate === $today) {
            $newStreak = $student->streak_days;
        } else {
            $newStreak = 1;
        }

        $student->increment('points', $pointsEarned);
        $student->update([
            'streak_days'        => $newStreak,
            'last_activity_date' => $today,
        ]);

        $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100, 1) : 0;

        return response()->json([
            'score'         => $score,
            'max_score'     => $maxScore,
            'percentage'    => $percentage,
            'answers'       => $submittedAnswers,
            'attempt_id'    => $attempt->id,
            'points_earned' => $pointsEarned,
            'total_points'  => $student->fresh()->points,
            'streak_days'   => $newStreak,
        ]);
    }
}
