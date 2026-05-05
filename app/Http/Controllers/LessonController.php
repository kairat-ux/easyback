<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(): JsonResponse
    {
        $lessons = Lesson::with('teacher:id,name')->latest()->get();

        return response()->json($lessons);
    }

    public function show(int $id): JsonResponse
    {
        $lesson = Lesson::with(['teacher:id,name', 'uploadedFiles'])->findOrFail($id);

        return response()->json($lesson);
    }

    public function store(LessonRequest $request): JsonResponse
    {
        $lesson = Lesson::create(array_merge(
            $request->validated(),
            ['teacher_id' => auth()->id()]
        ));

        return response()->json($lesson->load('teacher:id,name'), 201);
    }

    public function update(LessonRequest $request, int $id): JsonResponse
    {
        $lesson = Lesson::findOrFail($id);

        $user = auth()->user();
        if ($lesson->teacher_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $lesson->update($request->validated());

        return response()->json($lesson->load('teacher:id,name'));
    }

    public function destroy(int $id): JsonResponse
    {
        $lesson = Lesson::findOrFail($id);

        $user = auth()->user();
        if ($lesson->teacher_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $lesson->delete();

        return response()->json(['message' => 'Lesson deleted successfully']);
    }
}
