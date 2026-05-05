<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\UserController;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);
Route::post('/contact',       [ContactController::class, 'store']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/auth/me',           [AuthController::class, 'me']);
    Route::post('/auth/logout',      [AuthController::class, 'logout']);
    Route::patch('/auth/language',   [AuthController::class, 'updateLanguage']);

    // Lessons — view accessible by all authenticated users
    Route::get('/lessons',       [LessonController::class, 'index']);
    Route::get('/lessons/{id}',  [LessonController::class, 'show']);

    // Lessons — mutate (teacher + admin only)
    Route::middleware('role:teacher,admin')->group(function () {
        Route::post('/lessons',          [LessonController::class, 'store']);
        Route::put('/lessons/{id}',      [LessonController::class, 'update']);
        Route::delete('/lessons/{id}',   [LessonController::class, 'destroy']);
    });

    // Comments (any authenticated user)
    Route::get('/lessons/{lessonId}/comments',        [CommentController::class, 'index']);
    Route::post('/lessons/{lessonId}/comments',       [CommentController::class, 'store']);
    Route::delete('/lessons/{lessonId}/comments/{id}', [CommentController::class, 'destroy']);

    // Exercises — view accessible by all authenticated users
    Route::get('/exercises',      [ExerciseController::class, 'index']);
    Route::get('/exercises/{id}', [ExerciseController::class, 'show']);

    // Exercises — submit (student only)
    Route::middleware('role:student')->group(function () {
        Route::post('/exercises/{id}/submit', [ExerciseController::class, 'submit']);
        Route::get('/progress',               [ProgressController::class, 'index']);
        Route::get('/charts/student',         [ChartController::class, 'studentCharts']);
        Route::get('/certificates',           [CertificateController::class, 'index']);
    });

    // Exercises — mutate (teacher + admin only)
    Route::middleware('role:teacher,admin')->group(function () {
        Route::post('/exercises',          [ExerciseController::class, 'store']);
        Route::put('/exercises/{id}',      [ExerciseController::class, 'update']);
        Route::delete('/exercises/{id}',   [ExerciseController::class, 'destroy']);
    });

    // Files — all authenticated users
    Route::post('/files/upload',        [FileController::class, 'upload']);
    Route::get('/files',                [FileController::class, 'index']);
    Route::delete('/files/{id}',        [FileController::class, 'destroy']);
    Route::get('/files/{id}/download',  [FileController::class, 'download']);

    // Leaderboard (any authenticated user)
    Route::get('/leaderboard', [LeaderboardController::class, 'index']);

    // Admin only
    Route::middleware('role:admin')->group(function () {
        // pending-count MUST be declared before /users/{id} to avoid route conflict
        Route::get('/users/pending-count',      [UserController::class, 'pendingCount']);
        Route::get('/users',                    [UserController::class, 'index']);
        Route::patch('/users/{id}/approve',     [UserController::class, 'approve']);
        Route::patch('/users/{id}/reject',      [UserController::class, 'reject']);
        Route::delete('/users/{id}',            [UserController::class, 'destroy']);
        Route::get('/charts/admin',             [ChartController::class, 'adminCharts']);
    });
});
