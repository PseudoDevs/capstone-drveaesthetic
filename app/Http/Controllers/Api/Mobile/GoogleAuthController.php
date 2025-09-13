<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class GoogleAuthController extends Controller
{
    public function authenticateWithToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $googleUser = $this->getGoogleUserFromToken($request->access_token);

            if (!$googleUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Google access token'
                ], 401);
            }

            $user = User::where('email', $googleUser['email'])->first();

            if ($user) {
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleUser['id'],
                        'avatar' => $googleUser['picture'],
                    ]);
                }
            } else {
                $user = User::create([
                    'name' => $googleUser['name'],
                    'email' => $googleUser['email'],
                    'google_id' => $googleUser['id'],
                    'avatar' => $googleUser['picture'],
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'Client',
                    'email_verified_at' => now(),
                ]);
            }

            $user->tokens()->delete();

            $token = $user->createToken('mobile-app', ['mobile'])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Google authentication successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'avatar_url' => $user->avatar_url,
                        'phone' => $user->phone,
                        'date_of_birth' => $user->date_of_birth,
                        'address' => $user->address,
                        'google_id' => $user->google_id,
                        'email_verified_at' => $user->email_verified_at,
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google authentication failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Authentication error'
            ], 500);
        }
    }

    public function authenticateWithIdToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $googleUser = $this->verifyGoogleIdToken($request->id_token);

            if (!$googleUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Google ID token'
                ], 401);
            }

            $user = User::where('email', $googleUser['email'])->first();

            if ($user) {
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleUser['sub'],
                        'avatar' => $googleUser['picture'] ?? null,
                    ]);
                }
            } else {
                $user = User::create([
                    'name' => $googleUser['name'],
                    'email' => $googleUser['email'],
                    'google_id' => $googleUser['sub'],
                    'avatar' => $googleUser['picture'] ?? null,
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'Client',
                    'email_verified_at' => $googleUser['email_verified'] ? now() : null,
                ]);
            }

            $user->tokens()->delete();

            $token = $user->createToken('mobile-app', ['mobile'])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Google authentication successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'avatar_url' => $user->avatar_url,
                        'phone' => $user->phone,
                        'date_of_birth' => $user->date_of_birth,
                        'address' => $user->address,
                        'google_id' => $user->google_id,
                        'email_verified_at' => $user->email_verified_at,
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google authentication failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Authentication error'
            ], 500);
        }
    }

    private function getGoogleUserFromToken(string $accessToken): ?array
    {
        try {
            $response = Http::timeout(10)
                ->get('https://www.googleapis.com/oauth2/v2/userinfo', [
                    'access_token' => $accessToken
                ]);

            if (!$response->successful()) {
                return null;
            }

            $userData = $response->json();

            if (!isset($userData['email'])) {
                return null;
            }

            return $userData;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function verifyGoogleIdToken(string $idToken): ?array
    {
        try {
            $response = Http::timeout(10)
                ->get('https://oauth2.googleapis.com/tokeninfo', [
                    'id_token' => $idToken
                ]);

            if (!$response->successful()) {
                return null;
            }

            $tokenData = $response->json();

            if (!isset($tokenData['email']) || $tokenData['aud'] !== config('services.google.client_id')) {
                return null;
            }

            return $tokenData;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Logout error'
            ], 500);
        }
    }
}
