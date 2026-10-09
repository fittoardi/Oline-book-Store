<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function chat()
    {
        return view('chat.index');
    }

    public function submitChat(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        return to_route('chat.index')->with('status', 'Your message is in. The Kembang Books team will reply soon.');
    }
}
