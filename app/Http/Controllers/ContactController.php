<?php

namespace App\Http\Controllers;

use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'message' => 'required|string|min:10',
        ]);

        ContactMessage::create($request->only('name', 'email', 'message'));

        try {
            Mail::to($request->email)->send(new ContactReplyMail($request->name));
        } catch (\Throwable) {
            // mail failure must never block the contact form
        }

        return response()->json(['message' => 'Message sent successfully'], 201);
    }
}
