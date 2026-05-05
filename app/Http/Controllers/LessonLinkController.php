<?php

namespace App\Http\Controllers;

use App\Models\LessonLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonLinkController extends Controller
{
    public function index(int $lessonId): JsonResponse
    {
        $links = LessonLink::where('lesson_id', $lessonId)
            ->with('user:id,name')
            ->latest()
            ->get();

        return response()->json($links);
    }

    public function store(Request $request, int $lessonId): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'url'   => 'required|url|max:500',
        ]);

        $link = LessonLink::create([
            'lesson_id' => $lessonId,
            'user_id'   => auth()->id(),
            'title'     => $request->title,
            'url'       => $request->url,
        ]);

        return response()->json($link->load('user:id,name'), 201);
    }

    public function destroy(int $lessonId, int $id): JsonResponse
    {
        $link = LessonLink::where('lesson_id', $lessonId)->findOrFail($id);

        $user = auth()->user();
        if ($link->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $link->delete();

        return response()->json(['message' => 'Link deleted']);
    }
}
