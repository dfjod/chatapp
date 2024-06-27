<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Chat $chat)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = new Message();
        $message->user_from = auth()->id();
        $message->chat_to = $chat->id;
        $message->content = $request->content;
        $message->save();

        return redirect()->route('chats.show', $chat)->with('status', 'Message sent!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
