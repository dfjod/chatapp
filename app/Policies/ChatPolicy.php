<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chat $chat): bool
    {
        return $chat->users->contains($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function adminActions(User $user, Chat $chat): bool
    {
        $userRole = $chat->getUserRole($user->id);
        return $userRole <= 1;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function ownerActions(User $user, Chat $chat): bool
    {
        $userRole = $chat->getUserRole($user->id);
        return $userRole === 0;
    }
}
