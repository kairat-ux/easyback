<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;

class CertificateController extends Controller
{
    public function index(): JsonResponse
    {
        $student = auth()->user();
        $lessons = Lesson::with('exercises')->get();
        $completed = [];

        foreach ($lessons as $lesson) {
            $exerciseIds = $lesson->exercises->pluck('id');

            if ($exerciseIds->isEmpty()) {
                continue;
            }

            $attempted = Attempt::where('student_id', $student->id)
                ->whereIn('exercise_id', $exerciseIds)
                ->distinct('exercise_id')
                ->count('exercise_id');

            if ($attempted >= $exerciseIds->count()) {
                $completed[] = [
                    'lesson_id'    => $lesson->id,
                    'lesson_title' => $lesson->title,
                    'earned_at'    => Attempt::where('student_id', $student->id)
                        ->whereIn('exercise_id', $exerciseIds)
                        ->latest()
                        ->value('created_at'),
                ];
            }
        }

        return response()->json($completed);
    }
}
