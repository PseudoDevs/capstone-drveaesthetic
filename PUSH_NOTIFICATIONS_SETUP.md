# Push Notifications Setup Guide

## Overview
This implementation adds Firebase Cloud Messaging (FCM) push notifications to the chat system. When a staff member sends a message to a client (or vice versa), the recipient will receive a push notification on their mobile device.

## Backend Implementation

### 1. Dependencies
- **kreait/firebase-php**: Modern Firebase SDK for PHP
- **Firebase service account credentials**: Required for server-to-server communication

### 2. Database Changes
```sql
-- Migration: add_fcm_token_to_users_table
ALTER TABLE users ADD COLUMN fcm_token TEXT NULL AFTER remember_token;
```

### 3. Environment Configuration
Add the following to your `.env` file:

```bash
# Firebase Configuration
FIREBASE_PROJECT_ID=your-firebase-project-id
FIREBASE_CREDENTIALS_PATH=path/to/service-account-key.json
# OR alternatively use JSON string directly:
# FIREBASE_CREDENTIALS='{"type":"service_account",...}'
```

### 4. Firebase Service Account Setup
1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select your project
3. Go to Project Settings > Service Accounts
4. Click "Generate new private key"
5. Download the JSON file and place it in your Laravel project
6. Update `FIREBASE_CREDENTIALS_PATH` in your `.env` file

## API Endpoints

### FCM Token Management
```bash
# Register/Update FCM Token
POST /api/client/auth/fcm-token
Authorization: Bearer {token}
Content-Type: application/json

{
    "fcm_token": "your-fcm-token-from-client"
}

# Remove FCM Token (logout)
DELETE /api/client/auth/fcm-token
Authorization: Bearer {token}

# Test Push Notification
POST /api/client/auth/test-notification
Authorization: Bearer {token}
```

## React Native Integration

### 1. Install Dependencies
```bash
npm install @react-native-firebase/app @react-native-firebase/messaging
# For iOS
cd ios && pod install
```

### 2. Firebase Configuration
- Place `google-services.json` in `android/app/`
- Place `GoogleService-Info.plist` in `ios/YourApp/`

### 3. FCM Token Registration
```javascript
import messaging from '@react-native-firebase/messaging';

// Request permission and get token
const requestUserPermission = async () => {
  const authStatus = await messaging().requestPermission();
  const enabled =
    authStatus === messaging.AuthorizationStatus.AUTHORIZED ||
    authStatus === messaging.AuthorizationStatus.PROVISIONAL;

  if (enabled) {
    const token = await messaging().getToken();
    // Send token to your Laravel API
    await registerFCMToken(token);
  }
};

// Register token with Laravel backend
const registerFCMToken = async (token) => {
  try {
    await fetch('/api/client/auth/fcm-token', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${userToken}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ fcm_token: token }),
    });
  } catch (error) {
    console.error('Failed to register FCM token:', error);
  }
};

// Handle token refresh
messaging().onTokenRefresh(token => {
  registerFCMToken(token);
});
```

### 4. Handle Incoming Messages
```javascript
import messaging from '@react-native-firebase/messaging';
import { NavigationContainer } from '@react-navigation/native';

// Handle foreground messages
const handleForegroundMessage = () => {
  return messaging().onMessage(async remoteMessage => {
    console.log('Foreground message:', remoteMessage);

    if (remoteMessage.data?.type === 'chat_message') {
      // Show in-app notification or update chat UI
      showInAppNotification(remoteMessage);
    }
  });
};

// Handle background/quit state messages
messaging().onNotificationOpenedApp(remoteMessage => {
  console.log('Notification opened app:', remoteMessage);

  if (remoteMessage.data?.type === 'chat_message') {
    // Navigate to chat screen
    navigateToChat(remoteMessage.data.chat_id);
  }
});

// Handle app launch from notification
messaging()
  .getInitialNotification()
  .then(remoteMessage => {
    if (remoteMessage?.data?.type === 'chat_message') {
      // Navigate to chat screen
      navigateToChat(remoteMessage.data.chat_id);
    }
  });
```

### 5. Notification Handling in Chat
```javascript
// In your chat component
useEffect(() => {
  const unsubscribe = handleForegroundMessage();
  return unsubscribe;
}, []);

const showInAppNotification = (remoteMessage) => {
  // Show toast, badge, or update chat UI
  if (remoteMessage.data?.chat_id === currentChatId) {
    // If user is in the same chat, just add message to UI
    // Don't show notification
    return;
  }

  // Show notification for other chats
  Toast.show({
    type: 'info',
    text1: remoteMessage.notification?.title,
    text2: remoteMessage.notification?.body,
    onPress: () => navigateToChat(remoteMessage.data?.chat_id),
  });
};
```

## Notification Payload Structure

### Chat Message Notification
```json
{
  "notification": {
    "title": "New Message from Dr. Smith",
    "body": "Hello, how can I help you today?"
  },
  "data": {
    "type": "chat_message",
    "chat_id": "123",
    "sender_id": "456",
    "sender_name": "Dr. Smith",
    "message": "Hello, how can I help you today?",
    "click_action": "FLUTTER_NOTIFICATION_CLICK"
  }
}
```

## Security Considerations

1. **Token Validation**: FCM tokens are validated before storage
2. **User Authentication**: All FCM endpoints require authentication
3. **Message Content**: Sensitive information is not included in notification payload
4. **Token Cleanup**: Invalid tokens are automatically removed
5. **Rate Limiting**: Consider implementing rate limiting for notification endpoints

## Testing

### 1. Test FCM Token Registration
```bash
curl -X POST "http://localhost:8000/api/client/auth/fcm-token" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"fcm_token":"test-token-123"}'
```

### 2. Test Push Notification
```bash
curl -X POST "http://localhost:8000/api/client/auth/test-notification" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 3. Send Test Chat Message
```bash
curl -X POST "http://localhost:8000/api/client/mobile/chat/send-message" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"receiver_id":2,"message":"Test notification message"}'
```

## Troubleshooting

### Common Issues

1. **Firebase credentials not found**
   - Verify `FIREBASE_CREDENTIALS_PATH` or `FIREBASE_CREDENTIALS` in `.env`
   - Ensure service account JSON file exists and is readable

2. **Push notifications not received**
   - Check FCM token is registered in database
   - Verify Firebase project configuration
   - Check device notification permissions
   - Review Laravel logs for notification sending errors

3. **Invalid FCM token errors**
   - FCM tokens can expire or become invalid
   - Implement token refresh in React Native app
   - Clean up invalid tokens using the validation method

### Debug Logging
Enable debug logging in your Laravel app:
```php
// In config/logging.php - add FCM channel
'channels' => [
    'fcm' => [
        'driver' => 'daily',
        'path' => storage_path('logs/fcm.log'),
        'level' => 'debug',
    ],
],
```

Then update `PushNotificationService` to use the FCM log channel for better debugging.

## Performance Considerations

1. **Batch Notifications**: Use `sendBulkNotification()` for multiple recipients
2. **Async Processing**: Consider using Laravel queues for notification sending
3. **Token Cleanup**: Regularly clean up invalid FCM tokens
4. **Rate Limiting**: Implement rate limiting to prevent notification spam

## Future Enhancements

1. **Notification Preferences**: Allow users to customize notification settings
2. **Rich Notifications**: Add images, action buttons to notifications
3. **Delivery Reports**: Track notification delivery and engagement
4. **Multiple Device Support**: Support multiple FCM tokens per user
5. **Notification History**: Store notification history for analytics