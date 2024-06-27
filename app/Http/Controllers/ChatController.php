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
            $user->chats()->attach($chat->id, ['role' => 0]); // role 0 means owner
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

        return view('chat.show', compact('messages', 'currentChat', 'chat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chat $chat)
    {
        $currentUser = auth()->user();

        if ($currentUser->cannot('view', $chat)) {
            abort(403);
        }

        return view('chat.edit', compact('chat', 'currentUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        $currentUser = auth()->user();

        if ($currentUser->cannot('adminActions', $chat)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $chat->fill($validated);
        $chat->save();

        return Redirect::route('chats.edit', $chat)->with('status', 'chat-updated');
    }

    public function addUser(Request $request, Chat $chat)
    {
        $currentUser = auth()->user();

        if ($currentUser->cannot('adminActions', $chat)) {
            abort(403);
        }

        $validated = $request->validate([
            'username' => 'required|exists:users,username',
        ]);
        $username = $validated['username'];

        if (!$chat->users()->where('username', $username)->exists()) {
            $user = User::where('username', $username)->first();
            $chat->users()->attach($user->id, ['role' => 2]); // role 2 means regular user
        }

        return Redirect::route('chats.edit', $chat)->with('status', 'user-added');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        if (auth()->user()->cannot('ownerActions', $chat)) {
            abort(403);
        }

        $chat->users()->detach();

        $chat->delete();

        return Redirect::route('chats.index');
    }

    public function promoteUser(Request $request, Chat $chat, User $user)
    {
        $currentUser = auth()->user();
        
        if ($currentUser->cannot('ownerActions', $chat)) {
            abort(403);
        }

        if ($currentUser->id === $user->id) {
            return Redirect::back()->withErrors(['User can\'t promote himself']);
        }

        if (!$chat->users()->where('user_id', $user->id)->exists()) {
            return Redirect::back()->withErrors(['User not in the chat']);
        }

        $chat->users()->updateExistingPivot($user->id, ['role' => 1]);

        return Redirect::route('chats.edit', $chat)->with('status', 'user-promoted');
    }

    public function demoteUser(Request $request, Chat $chat, User $user)
    {
        $currentUser = auth()->user();
        
        if ($currentUser->cannot('ownerActions', $chat)) {
            abort(403);
        }

        if ($currentUser->id === $user->id) {
            return Redirect::back()->withErrors(['User can\'t demote himself']);
        }

        if (!$chat->users()->where('user_id', $user->id)->exists()) {
            return Redirect::back()->withErrors(['User not in the chat']);
        }

        $chat->users()->updateExistingPivot($user->id, ['role' => 2]); // Adjust the role as needed

        return Redirect::route('chats.edit', $chat)->with('status', 'user-demoted');
    }

    public function kickUser(Request $request, Chat $chat, User $user)
    {
        $currentUser = auth()->user();
        
        if ($currentUser->cannot('adminActions', $chat)) {
            abort(403);
        }

        if ($currentUser->id === $user->id) {
            return Redirect::back()->withErrors(['User can\'t kick himself out']);
        }

        if (!$chat->users()->where('user_id', $user->id)->exists()) {
            return Redirect::back()->withErrors(['User not in the chat']);
        }

        $currentUserRole = $chat->users()->where('user_id', auth()->id())->first()->pivot->role;
        $userRole = $chat->users()->where('user_id', $user->id)->first()->pivot->role;

        if ($currentUserRole > $userRole) {
            return Redirect::back()->withErrors(['You do not have permission to kick this user']);
        }

        $chat->users()->detach($user->id);

        return Redirect::route('chats.edit', $chat)->with('status', 'user-kicked');
    }

    public function leave(Chat $chat)
    {
        $user = auth()->user();
        
        if ($chat->getUserRole($user->id) === 0) {
            return Redirect::back()->withErrors(['Owner can\'t leave']);
        }

        $chat->users()->detach($user->id);
        return Redirect::route('chats.index')->with('status', 'Chat successfully left');
    }
}
