<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('chat.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('chat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $users = [
            'current' => User::find(auth()->user()->id),
            'requested' => User::where('username', $request->username)->first(),
        ];

        if (!$users['requested']) {
            return Redirect::back()->withErrors(['username' => 'User not found!']);
        }

        $chat = new Chat;
        $chat->name = $users['requested']->username . " & " . $users['current']->username . " chat";
        $chat->save();

        foreach ($users as $user) {
            $user->chats()->attach($chat->id);
        }

        return Redirect::route('chats.show', ['chat' => $chat->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        if (auth()->user()->cannot('view', $chat)) {
            abort(403);
        }

        $currentChat = $chat;
        $messages = $chat->messages;

        return view('chat.show', compact('messages', 'currentChat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
