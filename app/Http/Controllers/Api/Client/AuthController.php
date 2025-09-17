<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * Login API
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $credentials = $request->only(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password'
                ], 401);
            }

            $user = Auth::user();
            
            // Create Sanctum token if available, otherwise use simple response
            if (method_exists($user, 'createToken')) {
                $token = $user->createToken('auth_token')->plainTextToken;
                
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar_url' => $user->avatar_url,
                        'created_at' => $user->created_at,
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ], 200);
            } else {
                // Fallback for systems without Sanctum
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar_url' => $user->avatar_url,
                        'created_at' => $user->created_at,
                    ]
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register API
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date|before:today',
                'address' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'role' => 'client', // Default role for API registration
            ]);

            // Create Sanctum token if available
            if (method_exists($user, 'createToken')) {
                $token = $user->createToken('auth_token')->plainTextToken;
                
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar_url' => $user->avatar_url,
                        'created_at' => $user->created_at,
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ], 201);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar_url' => $user->avatar_url,
                        'created_at' => $user->created_at,
                    ]
                ], 201);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout API
     */
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Revoke Sanctum tokens if available
            if (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }

            // Logout from guard
            Auth::logout();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'date_of_birth' => $user->date_of_birth,
                    'address' => $user->address,
                    'avatar_url' => $user->avatar_url,
                    'role' => $user->role,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date|before:today',
                'address' => 'nullable|string|max:1000',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max
                'current_password' => 'sometimes|required_with:new_password|string',
                'new_password' => 'sometimes|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->only(['name', 'email', 'phone', 'date_of_birth', 'address']);

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists and is not a Google avatar URL
                if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $updateData['avatar'] = $avatarPath;
            }

            // Handle password update
            if ($request->filled('new_password')) {
                if (!$request->filled('current_password')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Current password is required to set new password'
                    ], 422);
                }

                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Current password is incorrect'
                    ], 422);
                }

                $updateData['password'] = Hash::make($request->new_password);
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'date_of_birth' => $user->date_of_birth,
                    'address' => $user->address,
                    'avatar_url' => $user->avatar_url,
                    'updated_at' => $user->updated_at,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Google Login API - for React Native and mobile apps
     */
    public function googleLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'access_token' => 'required|string',
                'id_token' => 'sometimes|string', // Optional ID token for additional verification
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verify Google access token
            $googleUser = $this->verifyGoogleToken($request->access_token, $request->id_token);
            
            if (!$googleUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Google token'
                ], 401);
            }

            // Find or create user
            $user = $this->findOrCreateGoogleUser($googleUser);

            // Create Sanctum token if available
            if (method_exists($user, 'createToken')) {
                $token = $user->createToken('google_auth_token')->plainTextToken;
                
                return response()->json([
                    'success' => true,
                    'message' => 'Google login successful',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar_url' => $user->avatar_url,
                        'google_id' => $user->google_id,
                        'role' => $user->role,
                        'created_at' => $user->created_at,
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'is_new_user' => $user->wasRecentlyCreated
                ], 200);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Google login successful',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar_url' => $user->avatar_url,
                        'google_id' => $user->google_id,
                        'role' => $user->role,
                        'created_at' => $user->created_at,
                    ],
                    'is_new_user' => $user->wasRecentlyCreated
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during Google login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify Google access token and get user info
     */
    private function verifyGoogleToken($accessToken, $idToken = null)
    {
        try {
            // Method 1: Verify using Google's userinfo endpoint with access token
            $response = Http::get('https://www.googleapis.com/oauth2/v2/userinfo', [
                'access_token' => $accessToken
            ]);

            if (!$response->successful()) {
                // Method 2: Try alternative approach with Authorization header
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken
                ])->get('https://www.googleapis.com/oauth2/v2/userinfo');
            }

            if (!$response->successful()) {
                return null;
            }

            $userData = $response->json();

            // Validate required fields
            if (!isset($userData['id']) || !isset($userData['email'])) {
                return null;
            }

            // Optional: Additional verification with ID token if provided
            if ($idToken) {
                $this->verifyGoogleIdToken($idToken, $userData['id']);
            }

            return [
                'google_id' => $userData['id'],
                'email' => $userData['email'],
                'name' => $userData['name'] ?? $userData['email'],
                'avatar' => $userData['picture'] ?? null,
                'verified_email' => $userData['verified_email'] ?? false,
            ];

        } catch (\Exception $e) {
            \Log::error('Google token verification failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Optional: Verify Google ID Token for additional security
     */
    private function verifyGoogleIdToken($idToken, $expectedUserId)
    {
        try {
            // You can implement JWT verification here if needed
            // For now, we'll rely on the access token verification
            // This is where you'd add google/apiclient or firebase/jwt if needed
            return true;
        } catch (\Exception $e) {
            \Log::warning('Google ID token verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Find or create user from Google data
     */
    private function findOrCreateGoogleUser($googleUser)
    {
        // First, try to find user by Google ID
        $user = User::where('google_id', $googleUser['google_id'])->first();

        if ($user) {
            // Update user info if found
            $user->update([
                'name' => $googleUser['name'],
                'avatar' => $googleUser['avatar'],
            ]);
            return $user;
        }

        // If not found by Google ID, try to find by email
        $user = User::where('email', $googleUser['email'])->first();

        if ($user) {
            // Link Google account to existing user
            $user->update([
                'google_id' => $googleUser['google_id'],
                'name' => $googleUser['name'],
                'avatar' => $googleUser['avatar'],
            ]);
            return $user;
        }

        // Create new user
        $user = User::create([
            'google_id' => $googleUser['google_id'],
            'name' => $googleUser['name'],
            'email' => $googleUser['email'],
            'avatar' => $googleUser['avatar'],
            'role' => 'client',
            'email_verified_at' => $googleUser['verified_email'] ? now() : null,
            'password' => Hash::make(uniqid()), // Random password for Google users
        ]);

        return $user;
    }

    /**
     * Unlink Google account
     */
    public function unlinkGoogle(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            if (!$user->google_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No Google account linked'
                ], 400);
            }

            // Check if user has a password set (for regular login)
            if (!$user->password || $user->password === Hash::make(uniqid())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot unlink Google account. Please set a password first for regular login.'
                ], 400);
            }

            $user->update([
                'google_id' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Google account unlinked successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while unlinking Google account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}