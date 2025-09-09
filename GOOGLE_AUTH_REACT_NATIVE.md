# Google Authentication API for React Native

This document provides complete integration guide for implementing Google OAuth authentication in React Native apps with the Dr. V Aesthetic Clinic backend API.

## Overview

The Google Authentication API allows React Native apps to authenticate users using their Google accounts. The flow works as follows:

1. **Client-side**: React Native app handles Google OAuth and gets access token
2. **Server-side**: Laravel backend verifies the Google token and creates/authenticates user
3. **Response**: Backend returns user data and Laravel Sanctum token for API access

## API Endpoints

### Google Login
**POST** `/api/client/auth/google-login`

Send Google access token to authenticate/register user.

#### Request Body
```json
{
    "access_token": "ya29.a0ARrd...", // Required: Google access token
    "id_token": "eyJhbGc..."           // Optional: Google ID token for extra security
}
```

#### Success Response (200)
```json
{
    "success": true,
    "message": "Google login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@gmail.com",
        "avatar_url": "https://lh3.googleusercontent.com/a/...",
        "google_id": "1234567890",
        "role": "client",
        "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "access_token": "1|abc123token...",
    "token_type": "Bearer",
    "is_new_user": false
}
```

#### Error Response (401)
```json
{
    "success": false,
    "message": "Invalid Google token"
}
```

### Unlink Google Account
**POST** `/api/client/auth/unlink-google`

Unlink Google account from user profile (requires authentication).

#### Headers
```
Authorization: Bearer {access_token}
Content-Type: application/json
```

#### Success Response (200)
```json
{
    "success": true,
    "message": "Google account unlinked successfully"
}
```

## React Native Implementation

### 1. Install Dependencies

```bash
# Using npm
npm install @react-native-google-signin/google-signin react-native-keychain axios

# Using yarn
yarn add @react-native-google-signin/google-signin react-native-keychain axios
```

### 2. iOS Setup (ios/Podfile)

```ruby
# Add to ios/Podfile
pod 'GoogleSignIn'
```

Then run:
```bash
cd ios && pod install
```

### 3. Android Setup

Add to `android/app/build.gradle`:
```gradle
implementation 'com.google.android.gms:play-services-auth:20.7.0'
```

### 4. Google Console Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create/select your project
3. Enable Google+ API
4. Create OAuth 2.0 credentials:
   - **Web client ID** (for backend verification)
   - **Android client ID** (for Android app)
   - **iOS client ID** (for iOS app)

### 5. React Native Configuration

#### Configure Google Sign-In

```javascript
// GoogleSignInConfig.js
import { GoogleSignin } from '@react-native-google-signin/google-signin';

export const configureGoogleSignIn = () => {
    GoogleSignin.configure({
        webClientId: 'YOUR_WEB_CLIENT_ID.apps.googleusercontent.com', // From Google Console
        offlineAccess: true, // To get refresh token
        hostedDomain: '', // Optional: restrict to specific domain
        forceCodeForRefreshToken: true, // Android only
        accountName: '', // Android only
        iosClientId: 'YOUR_IOS_CLIENT_ID.apps.googleusercontent.com', // iOS only
        googleServicePlistPath: '', // iOS only, path to GoogleService-Info.plist
    });
};
```

#### Initialize in App.js

```javascript
// App.js
import React, { useEffect } from 'react';
import { configureGoogleSignIn } from './GoogleSignInConfig';

const App = () => {
    useEffect(() => {
        configureGoogleSignIn();
    }, []);

    return (
        // Your app components
    );
};

export default App;
```

### 6. Authentication Service

```javascript
// AuthService.js
import { GoogleSignin } from '@react-native-google-signin/google-signin';
import Keychain from 'react-native-keychain';
import axios from 'axios';

const API_BASE_URL = 'https://your-api-domain.com/api/client/auth';

class AuthService {
    constructor() {
        this.setupAxiosInterceptors();
    }

    // Setup automatic token attachment
    setupAxiosInterceptors() {
        axios.interceptors.request.use(async (config) => {
            const token = await this.getStoredToken();
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
            return config;
        });

        axios.interceptors.response.use(
            (response) => response,
            async (error) => {
                if (error.response?.status === 401) {
                    await this.logout();
                    // Redirect to login screen
                }
                return Promise.reject(error);
            }
        );
    }

    // Google Sign-In
    async googleSignIn() {
        try {
            // Check if device supports Google Play Services
            await GoogleSignin.hasPlayServices();
            
            // Sign in and get user info
            const userInfo = await GoogleSignin.signIn();
            
            // Get access token
            const tokens = await GoogleSignin.getTokens();
            
            console.log('Google user info:', userInfo);
            console.log('Access token:', tokens.accessToken);

            // Send token to backend
            const response = await axios.post(`${API_BASE_URL}/google-login`, {
                access_token: tokens.accessToken,
                id_token: tokens.idToken, // Optional
            });

            if (response.data.success) {
                // Store token securely
                await this.storeToken(response.data.access_token);
                
                return {
                    success: true,
                    user: response.data.user,
                    token: response.data.access_token,
                    isNewUser: response.data.is_new_user
                };
            } else {
                throw new Error(response.data.message);
            }

        } catch (error) {
            console.error('Google Sign-In Error:', error);
            
            if (error.code === 'SIGN_IN_CANCELLED') {
                throw new Error('Sign in was cancelled');
            } else if (error.code === 'IN_PROGRESS') {
                throw new Error('Sign in is in progress');
            } else if (error.code === 'PLAY_SERVICES_NOT_AVAILABLE') {
                throw new Error('Google Play Services not available');
            } else {
                throw new Error(error.message || 'Google sign in failed');
            }
        }
    }

    // Regular email/password login
    async login(email, password) {
        try {
            const response = await axios.post(`${API_BASE_URL}/login`, {
                email,
                password
            });

            if (response.data.success) {
                await this.storeToken(response.data.access_token);
                return {
                    success: true,
                    user: response.data.user,
                    token: response.data.access_token
                };
            } else {
                throw new Error(response.data.message);
            }
        } catch (error) {
            throw new Error(error.response?.data?.message || 'Login failed');
        }
    }

    // Logout
    async logout() {
        try {
            // Call API logout
            await axios.post(`${API_BASE_URL}/logout`);
        } catch (error) {
            console.warn('API logout failed:', error);
        } finally {
            // Always clean up local data
            await this.clearStoredToken();
            
            // Sign out from Google
            try {
                await GoogleSignin.signOut();
            } catch (error) {
                console.warn('Google sign out failed:', error);
            }
        }
    }

    // Get user profile
    async getProfile() {
        try {
            const response = await axios.get(`${API_BASE_URL}/profile`);
            return response.data.user;
        } catch (error) {
            throw new Error(error.response?.data?.message || 'Failed to get profile');
        }
    }

    // Update profile
    async updateProfile(profileData) {
        try {
            const response = await axios.put(`${API_BASE_URL}/profile`, profileData);
            return response.data.user;
        } catch (error) {
            throw new Error(error.response?.data?.message || 'Failed to update profile');
        }
    }

    // Unlink Google account
    async unlinkGoogle() {
        try {
            const response = await axios.post(`${API_BASE_URL}/unlink-google`);
            return response.data;
        } catch (error) {
            throw new Error(error.response?.data?.message || 'Failed to unlink Google account');
        }
    }

    // Token management
    async storeToken(token) {
        await Keychain.setInternetCredentials('auth_token', 'user', token);
    }

    async getStoredToken() {
        try {
            const credentials = await Keychain.getInternetCredentials('auth_token');
            return credentials ? credentials.password : null;
        } catch (error) {
            return null;
        }
    }

    async clearStoredToken() {
        await Keychain.resetInternetCredentials('auth_token');
    }

    // Check if user is authenticated
    async isAuthenticated() {
        const token = await this.getStoredToken();
        return !!token;
    }
}

export default new AuthService();
```

### 7. React Native Components

#### Login Screen

```javascript
// LoginScreen.js
import React, { useState } from 'react';
import {
    View,
    Text,
    TextInput,
    TouchableOpacity,
    Alert,
    ActivityIndicator,
    StyleSheet
} from 'react-native';
import AuthService from './AuthService';

const LoginScreen = ({ navigation }) => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [googleLoading, setGoogleLoading] = useState(false);

    const handleLogin = async () => {
        if (!email || !password) {
            Alert.alert('Error', 'Please enter email and password');
            return;
        }

        setLoading(true);
        try {
            const result = await AuthService.login(email, password);
            Alert.alert('Success', 'Login successful!');
            navigation.navigate('Dashboard');
        } catch (error) {
            Alert.alert('Login Failed', error.message);
        } finally {
            setLoading(false);
        }
    };

    const handleGoogleLogin = async () => {
        setGoogleLoading(true);
        try {
            const result = await AuthService.googleSignIn();
            
            if (result.isNewUser) {
                Alert.alert(
                    'Welcome!', 
                    'Account created successfully with Google.',
                    [{ text: 'OK', onPress: () => navigation.navigate('Dashboard') }]
                );
            } else {
                Alert.alert('Success', 'Login successful!');
                navigation.navigate('Dashboard');
            }
        } catch (error) {
            Alert.alert('Google Login Failed', error.message);
        } finally {
            setGoogleLoading(false);
        }
    };

    return (
        <View style={styles.container}>
            <Text style={styles.title}>Login</Text>
            
            <TextInput
                style={styles.input}
                placeholder="Email"
                value={email}
                onChangeText={setEmail}
                keyboardType="email-address"
                autoCapitalize="none"
            />
            
            <TextInput
                style={styles.input}
                placeholder="Password"
                value={password}
                onChangeText={setPassword}
                secureTextEntry
            />
            
            <TouchableOpacity 
                style={styles.loginButton} 
                onPress={handleLogin}
                disabled={loading}
            >
                {loading ? (
                    <ActivityIndicator color="white" />
                ) : (
                    <Text style={styles.buttonText}>Login</Text>
                )}
            </TouchableOpacity>
            
            <TouchableOpacity 
                style={styles.googleButton} 
                onPress={handleGoogleLogin}
                disabled={googleLoading}
            >
                {googleLoading ? (
                    <ActivityIndicator color="white" />
                ) : (
                    <Text style={styles.buttonText}>Continue with Google</Text>
                )}
            </TouchableOpacity>
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        paddingHorizontal: 20,
        backgroundColor: 'white'
    },
    title: {
        fontSize: 24,
        fontWeight: 'bold',
        textAlign: 'center',
        marginBottom: 30
    },
    input: {
        height: 50,
        borderWidth: 1,
        borderColor: '#ddd',
        borderRadius: 8,
        paddingHorizontal: 15,
        marginBottom: 15,
        fontSize: 16
    },
    loginButton: {
        backgroundColor: '#007bff',
        height: 50,
        borderRadius: 8,
        justifyContent: 'center',
        alignItems: 'center',
        marginBottom: 15
    },
    googleButton: {
        backgroundColor: '#db4437',
        height: 50,
        borderRadius: 8,
        justifyContent: 'center',
        alignItems: 'center'
    },
    buttonText: {
        color: 'white',
        fontSize: 16,
        fontWeight: 'bold'
    }
});

export default LoginScreen;
```

#### Profile Screen

```javascript
// ProfileScreen.js
import React, { useState, useEffect } from 'react';
import {
    View,
    Text,
    TouchableOpacity,
    Alert,
    StyleSheet,
    Image
} from 'react-native';
import AuthService from './AuthService';

const ProfileScreen = ({ navigation }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        loadProfile();
    }, []);

    const loadProfile = async () => {
        try {
            const profile = await AuthService.getProfile();
            setUser(profile);
        } catch (error) {
            Alert.alert('Error', 'Failed to load profile');
        } finally {
            setLoading(false);
        }
    };

    const handleLogout = async () => {
        Alert.alert(
            'Logout',
            'Are you sure you want to logout?',
            [
                { text: 'Cancel', style: 'cancel' },
                {
                    text: 'Logout',
                    onPress: async () => {
                        await AuthService.logout();
                        navigation.navigate('Login');
                    }
                }
            ]
        );
    };

    const handleUnlinkGoogle = async () => {
        Alert.alert(
            'Unlink Google Account',
            'Are you sure you want to unlink your Google account?',
            [
                { text: 'Cancel', style: 'cancel' },
                {
                    text: 'Unlink',
                    onPress: async () => {
                        try {
                            await AuthService.unlinkGoogle();
                            Alert.alert('Success', 'Google account unlinked successfully');
                            loadProfile(); // Refresh profile
                        } catch (error) {
                            Alert.alert('Error', error.message);
                        }
                    }
                }
            ]
        );
    };

    if (loading) {
        return (
            <View style={styles.container}>
                <Text>Loading...</Text>
            </View>
        );
    }

    return (
        <View style={styles.container}>
            <View style={styles.profileCard}>
                {user.avatar_url ? (
                    <Image source={{ uri: user.avatar_url }} style={styles.avatar} />
                ) : (
                    <View style={styles.avatarPlaceholder}>
                        <Text style={styles.avatarText}>
                            {user.name.substring(0, 2).toUpperCase()}
                        </Text>
                    </View>
                )}
                
                <Text style={styles.name}>{user.name}</Text>
                <Text style={styles.email}>{user.email}</Text>
                <Text style={styles.role}>Role: {user.role}</Text>
                
                {user.google_id && (
                    <Text style={styles.googleLinked}>✓ Google account linked</Text>
                )}
            </View>

            {user.google_id && (
                <TouchableOpacity style={styles.unlinkButton} onPress={handleUnlinkGoogle}>
                    <Text style={styles.buttonText}>Unlink Google Account</Text>
                </TouchableOpacity>
            )}
            
            <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
                <Text style={styles.buttonText}>Logout</Text>
            </TouchableOpacity>
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 20,
        backgroundColor: 'white'
    },
    profileCard: {
        alignItems: 'center',
        padding: 20,
        backgroundColor: '#f8f9fa',
        borderRadius: 10,
        marginBottom: 20
    },
    avatar: {
        width: 80,
        height: 80,
        borderRadius: 40,
        marginBottom: 15
    },
    avatarPlaceholder: {
        width: 80,
        height: 80,
        borderRadius: 40,
        backgroundColor: '#007bff',
        justifyContent: 'center',
        alignItems: 'center',
        marginBottom: 15
    },
    avatarText: {
        color: 'white',
        fontSize: 24,
        fontWeight: 'bold'
    },
    name: {
        fontSize: 20,
        fontWeight: 'bold',
        marginBottom: 5
    },
    email: {
        fontSize: 16,
        color: '#666',
        marginBottom: 5
    },
    role: {
        fontSize: 14,
        color: '#888',
        marginBottom: 10
    },
    googleLinked: {
        fontSize: 14,
        color: '#28a745'
    },
    unlinkButton: {
        backgroundColor: '#dc3545',
        height: 50,
        borderRadius: 8,
        justifyContent: 'center',
        alignItems: 'center',
        marginBottom: 15
    },
    logoutButton: {
        backgroundColor: '#6c757d',
        height: 50,
        borderRadius: 8,
        justifyContent: 'center',
        alignItems: 'center'
    },
    buttonText: {
        color: 'white',
        fontSize: 16,
        fontWeight: 'bold'
    }
});

export default ProfileScreen;
```

## Testing

### Test with Postman

1. First, get a Google access token from your React Native app (check console logs)
2. Use the token to test the API:

```bash
curl -X POST http://localhost:8000/api/client/auth/google-login \
  -H "Content-Type: application/json" \
  -d '{
    "access_token": "ya29.a0ARrd..."
  }'
```

### Debug Common Issues

1. **Invalid Token Error**: Check if Google token is valid and not expired
2. **CORS Issues**: Add your domain to CORS configuration
3. **User Creation Fails**: Check User model fillable fields
4. **Avatar Not Showing**: Verify avatar URL accessibility

## Security Best Practices

1. ✅ **Token Validation**: Always verify Google tokens server-side
2. ✅ **Secure Storage**: Use React Native Keychain for token storage
3. ✅ **HTTPS Only**: Always use HTTPS in production
4. ✅ **Token Expiry**: Handle token refresh and expiry
5. ✅ **Input Validation**: Validate all input data
6. ✅ **Error Handling**: Don't expose sensitive error details

## Production Considerations

1. **Environment Variables**: Store API keys in environment variables
2. **Error Logging**: Implement proper error logging
3. **Rate Limiting**: Add rate limiting to prevent abuse
4. **Monitoring**: Monitor API usage and errors
5. **Backup Strategy**: Implement user data backup

This implementation provides a complete, secure Google authentication flow for React Native apps with your Laravel backend!