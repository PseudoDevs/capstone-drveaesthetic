<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle(): JsonResponse
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

    public function handleGoogleCallback(): JsonResponse
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

            $token = $user->createToken('google-web-token')->plainTextToken;

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
}
