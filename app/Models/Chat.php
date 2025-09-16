<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Services\AutoIntroMessageService;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'client_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany('created_at');
    }

    public static function findOrCreateChat(int $userId1, int $userId2): Chat
    {
        // Get both users to determine who is staff and who is client
        $user1 = User::find($userId1);
        $user2 = User::find($userId2);
        
        // Determine staff and client IDs
        if ($user1->role === 'Staff' || $user1->role === 'Doctor' || $user1->role === 'Admin') {
            $staffId = $userId1;
            $clientId = $userId2;
        } else {
            $staffId = $userId2;
            $clientId = $userId1;
        }
        
        // For single chat mode: Always get the single staff member
        $staff = User::where('role', 'Staff')->first();
        if (!$staff) {
            throw new \Exception('No staff member found');
        }
        
        // Always use the single staff member's ID
        $staffId = $staff->id;
        
        // The client is whichever user is NOT the staff
        $clientId = ($userId1 == $staffId) ? $userId2 : $userId1;
        
        // Simple find first, if not found then create
        $chat = static::where([
            'staff_id' => $staffId,
            'client_id' => $clientId,
        ])->first();
        
        $isNewChat = false;
        if (!$chat) {
            $chat = new static([
                'staff_id' => $staffId,
                'client_id' => $clientId,
                'last_message_at' => now(),
            ]);
            
            try {
                $chat->save();
                $isNewChat = true;
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // If constraint violation, another request created it, so fetch it
                $chat = static::where([
                    'staff_id' => $staffId,
                    'client_id' => $clientId,
                ])->first();
            }
        }
        
        // Send automated intro message if this is a new chat
        if ($isNewChat) {
            $introService = new AutoIntroMessageService();
            $introService->sendIntroMessage($chat);
        }
        
        return $chat;
    }
}
