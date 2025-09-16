<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

class AutoIntroMessageService
{
    /**
     * Send an automated intro message when a new conversation is started
     */
    public function sendIntroMessage(Chat $chat): ?Message
    {
        // Check if this is a new conversation (no existing messages)
        if ($chat->messages()->count() > 0) {
            return null; // Already has messages, don't send intro
        }

        $staff = $chat->staff;
        $client = $chat->client;

        if (!$staff || !$client) {
            return null;
        }

        // Create personalized intro message
        $introMessage = $this->generateIntroMessage($staff, $client);

        // Create the message from staff
        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $staff->id,
            'message' => $introMessage,
            'is_read' => false,
        ]);

        return $message;
    }

    /**
     * Generate a personalized intro message
     */
    private function generateIntroMessage(User $staff, User $client): string
    {
        $greetings = [
            "Hello {$client->name}! 👋 Welcome to Dr. Ve Aesthetic Clinic. I'm {$staff->name} and I'm here to help you with any questions about our services.",
            "Hi {$client->name}! 😊 Thank you for choosing Dr. Ve Aesthetic Clinic. I'm {$staff->name}, your personal assistant. How can I help you today?",
            "Welcome {$client->name}! ✨ I'm {$staff->name} from Dr. Ve Aesthetic Clinic. I'm excited to help you on your aesthetic journey. What can I assist you with?",
            "Hello {$client->name}! 🌟 My name is {$staff->name} and I'll be your guide at Dr. V Aesthetic Clinic. Feel free to ask me anything about our treatments and services!"
        ];

        // Get current hour to provide time-appropriate greeting
        $hour = now()->hour;
        $timeGreeting = '';
        
        if ($hour >= 5 && $hour < 12) {
            $timeGreeting = "Good morning";
        } elseif ($hour >= 12 && $hour < 17) {
            $timeGreeting = "Good afternoon";
        } else {
            $timeGreeting = "Good evening";
        }

        // Select a random greeting and add time-based greeting
        $selectedGreeting = $greetings[array_rand($greetings)];
        
        return "{$timeGreeting}! {$selectedGreeting}";
    }

    /**
     * Check if a chat needs an intro message and send it
     */
    public function handleNewConversation(int $userId1, int $userId2): Chat
    {
        $chat = Chat::findOrCreateChat($userId1, $userId2);
        
        // Send intro message if this is a new conversation
        $this->sendIntroMessage($chat);
        
        return $chat;
    }
}