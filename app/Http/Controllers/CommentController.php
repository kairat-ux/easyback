<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(int $lessonId): JsonResponse
    {
        // Ensure the lesson exists
        Lesson::findOrFail($lessonId);

        $comments = Comment::where('lesson_id', $lessonId)
            ->with('user:id,name')
            ->latest()
            ->get();

        return response()->json($comments);
    }

    public function store(Request $request, int $lessonId): JsonResponse
    {
        // Ensure the lesson exists
        Lesson::findOrFail($lessonId);

        $request->validate([
            'body' => 'required|string|min:2|max:500',
        ]);

        $comment = Comment::create([
            'lesson_id' => $lessonId,
            'user_id'   => auth()->id(),
            'body'      => $request->body,
        ]);

        return response()->json($comment->load('user:id,name'), 201);
    }

    public function destroy(int $lessonId, int $id): JsonResponse
    {
        $comment = Comment::where('lesson_id', $lessonId)->findOrFail($id);

        $user = auth()->user();

        if ($comment->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
