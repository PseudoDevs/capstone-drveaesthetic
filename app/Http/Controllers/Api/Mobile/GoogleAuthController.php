<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function getAuthUrl(): JsonResponse
    {
        try {
            $url = Socialite::driver('google')
                ->stateless()
                ->redirect()
                ->getTargetUrl();

            return response()->json([
                'success' => true,
                'auth_url' => $url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate Google auth URL',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function handleCallback(Request $request): JsonResponse
    {
        try {
            $socialiteUser = Socialite::driver('google')
                ->stateless()
                ->user();

            $user = User::where('email', $socialiteUser->getEmail())->first();

            if ($user) {
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $socialiteUser->getId(),
                        'avatar' => $socialiteUser->getAvatar(),
                    ]);
                }
            } else {
                $user = User::create([
                    'name' => $socialiteUser->getName(),
                    'email' => $socialiteUser->getEmail(),
                    'google_id' => $socialiteUser->getId(),
                    'avatar' => $socialiteUser->getAvatar(),
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'Client',
                    'email_verified_at' => now(),
                ]);
            }

            $token = $user->createToken('google-mobile-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar_url' => $user->avatar_url,
                    'phone' => $user->phone,
                    'date_of_birth' => $user->date_of_birth,
                    'address' => $user->address,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google authentication failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function handleMobileAuth(Request $request): JsonResponse
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

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

            $token = $user->createToken('google-mobile-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar_url' => $user->avatar_url,
                    'phone' => $user->phone,
                    'date_of_birth' => $user->date_of_birth,
                    'address' => $user->address,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google authentication failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getGoogleUserFromToken(string $accessToken): ?array
    {
        try {
            $response = file_get_contents('https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $accessToken);

            if ($response === false) {
                return null;
            }

            $userData = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($userData['email'])) {
                return null;
            }

            return $userData;
        } catch (\Exception $e) {
            return null;
        }
    }
}
