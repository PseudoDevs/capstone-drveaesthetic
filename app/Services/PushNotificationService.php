<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    private $messaging;

    public function __construct()
    {
        $this->messaging = $this->initializeFirebase();
    }

    private function initializeFirebase()
    {
        try {
            $factory = (new Factory);

            // You'll need to add your Firebase service account key file
            // For now, we'll use environment variables approach
            if (config('services.firebase.credentials_path')) {
                $credentialsPath = base_path(config('services.firebase.credentials_path'));
                if (file_exists($credentialsPath)) {
                    $factory = $factory->withServiceAccount($credentialsPath);
                } else {
                    Log::error('Firebase credentials file not found: ' . $credentialsPath);
                    return null;
                }
            } elseif (config('services.firebase.credentials')) {
                $factory = $factory->withServiceAccount(json_decode(config('services.firebase.credentials'), true));
            } else {
                Log::error('Firebase credentials not configured');
                return null;
            }

            return $factory->createMessaging();
        } catch (\Exception $e) {
            Log::error('Failed to initialize Firebase: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send a chat message notification to a user
     */
    public function sendChatNotification(User $recipient, User $sender, string $message, int $chatId): bool
    {
        if (!$recipient->fcm_token || !$this->messaging) {
            return false;
        }

        try {
            $notification = Notification::create(
                'New Message from ' . $sender->name,
                $this->truncateMessage($message)
            );

            $data = [
                'type' => 'chat_message',
                'chat_id' => (string) $chatId,
                'sender_id' => (string) $sender->id,
                'sender_name' => $sender->name,
                'message' => $message,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ];

            $message = CloudMessage::withTarget('token', $recipient->fcm_token)
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->send($message);

            Log::info('Push notification sent successfully', [
                'recipient_id' => $recipient->id,
                'sender_id' => $sender->id,
                'chat_id' => $chatId
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send push notification: ' . $e->getMessage(), [
                'recipient_id' => $recipient->id,
                'sender_id' => $sender->id,
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send appointment notification
     */
    public function sendAppointmentNotification(User $recipient, string $title, string $message, array $data = []): bool
    {
        if (!$recipient->fcm_token || !$this->messaging) {
            return false;
        }

        try {
            $notification = Notification::create($title, $message);

            $defaultData = [
                'type' => 'appointment',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ];

            $messageData = array_merge($defaultData, $data);

            $message = CloudMessage::withTarget('token', $recipient->fcm_token)
                ->withNotification($notification)
                ->withData($messageData);

            $this->messaging->send($message);

            Log::info('Appointment notification sent successfully', [
                'recipient_id' => $recipient->id,
                'title' => $title
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send appointment notification: ' . $e->getMessage(), [
                'recipient_id' => $recipient->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send notification to multiple users
     */
    public function sendBulkNotification(array $fcmTokens, string $title, string $message, array $data = []): bool
    {
        if (empty($fcmTokens) || !$this->messaging) {
            return false;
        }

        try {
            $notification = Notification::create($title, $message);

            $defaultData = [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ];

            $messageData = array_merge($defaultData, $data);

            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($messageData);

            $report = $this->messaging->sendMulticast($message, $fcmTokens);

            Log::info('Bulk notification sent', [
                'total' => count($fcmTokens),
                'successful' => $report->successes()->count(),
                'failed' => $report->failures()->count()
            ]);

            return $report->hasFailures() === false;
        } catch (\Exception $e) {
            Log::error('Failed to send bulk notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify if FCM token is valid
     */
    public function validateToken(string $token): bool
    {
        if (!$this->messaging) {
            return false;
        }

        try {
            $message = CloudMessage::withTarget('token', $token)
                ->withData(['type' => 'token_validation']);

            $this->messaging->validate($message);
            return true;
        } catch (\Exception $e) {
            Log::warning('Invalid FCM token: ' . $e->getMessage(), ['token' => $token]);
            return false;
        }
    }

    /**
     * Remove invalid FCM tokens from user
     */
    public function cleanupInvalidToken(User $user): void
    {
        if ($user->fcm_token && !$this->validateToken($user->fcm_token)) {
            $user->update(['fcm_token' => null]);
            Log::info('Cleaned up invalid FCM token for user', ['user_id' => $user->id]);
        }
    }

    /**
     * Truncate message for notification display
     */
    private function truncateMessage(string $message, int $length = 100): string
    {
        return strlen($message) > $length ? substr($message, 0, $length) . '...' : $message;
    }

    /**
     * Send data-only message (silent notification)
     */
    public function sendDataMessage(User $recipient, array $data): bool
    {
        if (!$recipient->fcm_token || !$this->messaging) {
            return false;
        }

        try {
            $message = CloudMessage::withTarget('token', $recipient->fcm_token)
                ->withData($data);

            $this->messaging->send($message);

            Log::info('Data message sent successfully', [
                'recipient_id' => $recipient->id,
                'data_type' => $data['type'] ?? 'unknown'
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send data message: ' . $e->getMessage(), [
                'recipient_id' => $recipient->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}