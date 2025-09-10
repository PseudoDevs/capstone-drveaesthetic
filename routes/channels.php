<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    \Log::info('Channel auth attempt', ['user_id' => $user->id, 'chat_id' => $chatId]);
    
    // Check if user has access to this chat
    $chat = \App\Models\Chat::find($chatId);
    
    if (!$chat) {
        \Log::warning('Chat not found for channel auth', ['chat_id' => $chatId]);
        return false;
    }
    
    // User can access if they are either the staff or client in this chat
    $canAccess = $chat->staff_id == $user->id || $chat->client_id == $user->id;
    \Log::info('Channel auth result', [
        'user_id' => $user->id, 
        'chat_id' => $chatId, 
        'can_access' => $canAccess,
        'staff_id' => $chat->staff_id,
        'client_id' => $chat->client_id
    ]);
    
    return $canAccess;
});