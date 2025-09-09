# Client Authentication API Documentation

This document describes the custom authentication API endpoints for the Dr. V Aesthetic Clinic application.

## Base URL
```
/api/client/auth/
```

## Public Endpoints (No Authentication Required)

### 1. Login
**POST** `/api/client/auth/login`

Login with email and password to get an access token.

#### Request Body
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

#### Success Response (200)
```json
{
    "success": true,
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "avatar_url": "https://example.com/avatar.jpg",
        "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "access_token": "1|abc123token...",
    "token_type": "Bearer"
}
```

#### Error Response (401)
```json
{
    "success": false,
    "message": "Invalid email or password"
}
```

#### Validation Error Response (422)
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 6 characters."]
    }
}
```

### 2. Google Login
**POST** `/api/client/auth/google-login`

Authenticate/register user using Google OAuth token from mobile apps.

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

### 3. Register
**POST** `/api/client/auth/register`

Register a new user account.

#### Request Body
```json
{
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890",
    "date_of_birth": "1990-01-01",
    "address": "123 Main St, City, State"
}
```

#### Success Response (201)
```json
{
    "success": true,
    "message": "Registration successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "avatar_url": null,
        "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "access_token": "1|abc123token...",
    "token_type": "Bearer"
}
```

## Protected Endpoints (Authentication Required)

**Headers Required:**
```
Authorization: Bearer {access_token}
Content-Type: application/json
```

### 4. Logout
**POST** `/api/client/auth/logout`

Logout and revoke the current access token.

#### Success Response (200)
```json
{
    "success": true,
    "message": "Successfully logged out"
}
```

### 5. Get Profile
**GET** `/api/client/auth/profile`

Get the current user's profile information.

#### Success Response (200)
```json
{
    "success": true,
    "message": "Profile retrieved successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "phone": "+1234567890",
        "date_of_birth": "1990-01-01",
        "address": "123 Main St, City, State",
        "avatar_url": "https://example.com/avatar.jpg",
        "role": "client",
        "email_verified_at": null,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

### 6. Update Profile
**PUT** `/api/client/auth/profile`

Update the current user's profile information.

### 7. Unlink Google Account
**POST** `/api/client/auth/unlink-google`

Unlink Google account from user profile (requires authentication).

#### Success Response (200)
```json
{
    "success": true,
    "message": "Google account unlinked successfully"
}
```

#### Error Response (400)
```json
{
    "success": false,
    "message": "No Google account linked"
}
```

#### Request Body
```json
{
    "name": "John Smith",
    "email": "newuser@example.com",
    "phone": "+0987654321",
    "date_of_birth": "1990-01-01",
    "address": "456 New St, City, State",
    "current_password": "oldpassword123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

#### Success Response (200)
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "user": {
        "id": 1,
        "name": "John Smith",
        "email": "newuser@example.com",
        "phone": "+0987654321",
        "date_of_birth": "1990-01-01",
        "address": "456 New St, City, State",
        "avatar_url": "https://example.com/avatar.jpg",
        "updated_at": "2024-01-01T12:00:00.000000Z"
    }
}
```

## Usage Examples

### JavaScript/Axios Example

```javascript
// Login
const login = async (email, password) => {
    try {
        const response = await axios.post('/api/client/auth/login', {
            email: email,
            password: password
        });
        
        if (response.data.success) {
            // Store the token
            localStorage.setItem('access_token', response.data.access_token);
            console.log('Login successful:', response.data.user);
            return response.data;
        }
    } catch (error) {
        console.error('Login failed:', error.response.data);
        throw error;
    }
};

// Make authenticated requests
const getProfile = async () => {
    try {
        const token = localStorage.getItem('access_token');
        const response = await axios.get('/api/client/auth/profile', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        
        return response.data.user;
    } catch (error) {
        console.error('Failed to get profile:', error.response.data);
        throw error;
    }
};

// Logout
const logout = async () => {
    try {
        const token = localStorage.getItem('access_token');
        await axios.post('/api/client/auth/logout', {}, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        
        localStorage.removeItem('access_token');
        console.log('Logged out successfully');
    } catch (error) {
        console.error('Logout failed:', error.response.data);
    }
};
```

### cURL Examples

```bash
# Login
curl -X POST http://localhost:8000/api/client/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123"
  }'

# Get Profile (with token)
curl -X GET http://localhost:8000/api/client/auth/profile \
  -H "Authorization: Bearer 1|abc123token..." \
  -H "Content-Type: application/json"

# Logout
curl -X POST http://localhost:8000/api/client/auth/logout \
  -H "Authorization: Bearer 1|abc123token..." \
  -H "Content-Type: application/json"
```

## Error Codes

- **200**: Success
- **201**: Created (Registration successful)
- **401**: Unauthorized (Invalid credentials or no token)
- **422**: Validation Error (Invalid input data)
- **500**: Server Error

## Security Features

- ✅ **Laravel Sanctum**: Token-based authentication
- ✅ **Password Hashing**: Secure password storage
- ✅ **Input Validation**: Comprehensive request validation
- ✅ **Token Management**: Automatic token creation/revocation
- ✅ **Role-based Access**: Default client role assignment
- ✅ **Error Handling**: Consistent error responses

## Notes

1. All tokens are created using Laravel Sanctum
2. Tokens are automatically revoked on logout
3. The API falls back gracefully if Sanctum is not available
4. All passwords are hashed using Laravel's built-in hashing
5. Email uniqueness is enforced on registration and profile updates
6. Password changes require current password verification