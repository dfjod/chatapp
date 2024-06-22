<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;

class Message extends Model
{
    use HasFactory;

    /**
     * Get the chat of the message
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_to');
    }

    /**
     * Get the user of the message
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_from');
    }
}
