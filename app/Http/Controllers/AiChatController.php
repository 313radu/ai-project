<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $userMessage = $request->input('message');

        $response = Http::timeout(120)->post('http://127.0.0.1:11434/api/chat', [
            'model' => 'gemma3',
            'stream' => false,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an AI shopping assistant for an affiliate product platform. Answer clearly and shortly.',
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage,
                ],
            ],
        ]);

        if (! $response->successful()) {
            return response()->json([
                'reply' => 'AI server error. Check if Ollama is running.',
            ], 500);
        }

        return response()->json([
            'reply' => $response->json('message.content') ?? 'No response from model.',
        ]);
    }
}
