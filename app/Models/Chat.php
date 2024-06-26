<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Message;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the messages of the chat
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_to');
    }

    /**
     * The users that belong to the chat
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function getUserRole($userId)
    {
        $user = $this->users()->where('user_id', $userId)->first();
        
        return $user ? $user->pivot->role : null;
    }
}
