<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Models\UploadedFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function upload(FileUploadRequest $request): JsonResponse
    {
        $file     = $request->file('file');
        $category = $request->input('category', 'general');

        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path       = 'uploads/' . $category . '/' . $storedName;

        Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));

        $record = UploadedFile::create([
            'user_id'       => $request->user()->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_name'   => $storedName,
            'path'          => $path,
            'disk'          => 'public',
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'category'      => $category,
            'fileable_type' => $request->input('fileable_type'),
            'fileable_id'   => $request->input('fileable_id'),
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'file'    => [
                'id'            => $record->id,
                'original_name' => $record->original_name,
                'url'           => $record->url,
                'mime_type'     => $record->mime_type,
                'size'          => $record->size,
                'category'      => $record->category,
                'created_at'    => $record->created_at,
            ],
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->uploadedFiles()->latest();

        if ($request->filled('fileable_type')) {
            $query->where('fileable_type', $request->input('fileable_type'));
        }

        if ($request->filled('fileable_id')) {
            $query->where('fileable_id', $request->input('fileable_id'));
        }

        return response()->json($query->get());
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $file = UploadedFile::findOrFail($id);

        if ($file->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        Storage::disk($file->disk)->delete($file->path);
        $file->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }

    public function download(Request $request, int $id): mixed
    {
        $file = UploadedFile::findOrFail($id);

        if ($file->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $fullPath = Storage::disk($file->disk)->path($file->path);

        return response()->download($fullPath, $file->original_name);
    }
}
