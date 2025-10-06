# Dr. Ve Aesthetic Mobile App

## Project Setup Instructions

### Prerequisites
- Node.js (v18 or higher)
- React Native CLI
- Android Studio (for Android development)
- Xcode (for iOS development - macOS only)
- Expo CLI (optional, for easier development)

### Installation Steps

1. **Install React Native CLI globally:**
```bash
npm install -g @react-native-community/cli
```

2. **Create new React Native project:**
```bash
npx react-native init DrVeAestheticApp --template react-native-template-typescript
cd DrVeAestheticApp
```

3. **Install required dependencies:**
```bash
# Navigation
npm install @react-navigation/native @react-navigation/stack @react-navigation/bottom-tabs
npm install react-native-screens react-native-safe-area-context

# HTTP requests
npm install axios

# State management
npm install @reduxjs/toolkit react-redux

# UI components
npm install react-native-elements react-native-vector-icons
npm install react-native-paper

# Notifications
npm install @react-native-async-storage/async-storage
npm install react-native-push-notification

# Date/Time handling
npm install moment

# Forms
npm install react-hook-form

# Charts (for analytics)
npm install react-native-chart-kit react-native-svg

# Camera/Image handling
npm install react-native-image-picker
npm install react-native-image-crop-picker

# Calendar
npm install react-native-calendars

# Maps (for clinic location)
npm install react-native-maps

# Development tools
npm install --save-dev @types/react @types/react-native
```

4. **iOS Setup (macOS only):**
```bash
cd ios && pod install && cd ..
```

5. **Android Setup:**
- Open Android Studio
- Configure Android SDK
- Set up Android emulator or connect physical device

### Project Structure
```
DrVeAestheticApp/
├── src/
│   ├── components/          # Reusable UI components
│   ├── screens/            # Screen components
│   ├── navigation/         # Navigation configuration
│   ├── services/           # API services
│   ├── store/              # Redux store
│   ├── utils/              # Utility functions
│   ├── types/              # TypeScript type definitions
│   └── constants/          # App constants
├── android/                # Android-specific code
├── ios/                    # iOS-specific code
└── package.json
```

### Running the App

**Android:**
```bash
npx react-native run-android
```

**iOS:**
```bash
npx react-native run-ios
```

### Key Features to Implement

1. **Authentication**
   - Login/Register screens
   - Biometric authentication
   - Social login (Google)

2. **Dashboard**
   - Appointment overview
   - Quick actions
   - Notifications

3. **Appointment Management**
   - Book appointments
   - View appointment history
   - Reschedule/Cancel appointments

4. **Services**
   - Browse services
   - Service categories
   - Service details

5. **Profile Management**
   - User profile
   - Notification preferences
   - Medical information

6. **Chat**
   - Real-time messaging
   - File sharing
   - Voice messages

7. **Notifications**
   - Push notifications
   - In-app notifications
   - Notification settings

8. **Analytics**
   - Personal statistics
   - Service history
   - Spending tracking
