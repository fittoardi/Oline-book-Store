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
        return view('chat.index');
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

        return to_route('chat.index')->with('status', 'Your message is in. The Kembang Books team will reply soon.');
    }
}
