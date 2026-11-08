<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function chat()
    {
        $conversation = null;
        $messages = collect();

        if (Auth::check()) {
            $conversation = ChatConversation::where('user_id', Auth::id())
                ->where('status', 'open')
                ->latest()
                ->first();

            if ($conversation) {
                $messages = $conversation->messages()->with('sender')->oldest()->get();
            }
        }

        return view('chat.index', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    public function submitChat(Request $request)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        if (Auth::check()) {
            $conversation = ChatConversation::firstOrCreate(
                ['user_id' => Auth::id(), 'status' => 'open'],
                ['last_message_at' => now()],
            );

            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => Auth::id(),
                'message' => $data['message'],
            ]);

            $conversation->update(['last_message_at' => now()]);
        }

        return to_route('chat.index')->with('status', 'Pesan terkirim. Tim Daily Books akan segera membalas.');
    }
}
