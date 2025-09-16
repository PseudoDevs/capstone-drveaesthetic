<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function ($message) {
            // Update the chat's last_message_at timestamp whenever a new message is created
            $message->chat()->update(['last_message_at' => $message->created_at]);
        });
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
