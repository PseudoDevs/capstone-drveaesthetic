<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Sanctum\HasApiTokens;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                'confirmed'
            ],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Client',
        ]);

        Auth::login($user);

        return redirect('/users/dashboard');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/users/dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function redirectToGoogle()
    {
        try {
            // Start session but don't regenerate to maintain consistency
            if (!session()->isStarted()) {
                session()->start();
            }

            // Generate and store state in cache instead of session
            $state = \Str::random(40);
            $sessionId = session()->getId();

            // Store state in cache with longer TTL (10 minutes)
            \Cache::put("oauth_state_{$state}", $sessionId, 600);

            // Also try to store in session as backup
            session()->put('oauth_state', $state);
            session()->save();

            \Log::info('Google OAuth: Starting redirect', [
                'session_id' => $sessionId,
                'generated_state' => $state,
                'cache_key' => "oauth_state_{$state}"
            ]);

            return Socialite::driver('google')
                ->scopes(['openid', 'profile', 'email'])
                ->with(['state' => $state])
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Google OAuth: Redirect error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect('/users/login')->withErrors([
                'error' => 'Unable to connect to Google. Please check the configuration or try again later.'
            ]);
        }
    }

    public function handleGoogleCallback()
    {
        try {
            // Verify state parameter using cache
            $requestState = request()->get('state');
            $sessionState = session()->get('oauth_state'); // backup check

            // Check cache for the state
            $cachedSessionId = \Cache::get("oauth_state_{$requestState}");

            \Log::info('Google OAuth: Callback received', [
                'current_session_id' => session()->getId(),
                'request_state' => $requestState,
                'session_state' => $sessionState,
                'cached_session_id' => $cachedSessionId,
                'cache_key' => "oauth_state_{$requestState}"
            ]);

            // Validate state using cache or session
            $stateValid = false;
            if ($requestState && $cachedSessionId) {
                $stateValid = true;
                \Log::info('Google OAuth: State validated via cache');
            } elseif ($requestState && $sessionState && $requestState === $sessionState) {
                $stateValid = true;
                \Log::info('Google OAuth: State validated via session');
            }

            if (!$stateValid) {
                \Log::error('Google OAuth: State validation failed', [
                    'request_state' => $requestState,
                    'session_state' => $sessionState,
                    'cached_session_id' => $cachedSessionId
                ]);

                // Clean up
                if ($requestState) {
                    \Cache::forget("oauth_state_{$requestState}");
                }
                session()->forget('oauth_state');

                return redirect('/users/login')->withErrors([
                    'error' => 'Invalid authentication state. Please try again.'
                ]);
            }

            // Clean up stored state
            if ($requestState) {
                \Cache::forget("oauth_state_{$requestState}");
            }
            session()->forget('oauth_state');

            // Get user from Google using stateless mode
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Log the Google user data for debugging
            \Log::info('Google OAuth User Data:', [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'avatar' => $googleUser->avatar,
            ]);

            // Validate required fields
            if (!$googleUser->email || !$googleUser->name) {
                \Log::error('Google OAuth: Missing required user data');
                return redirect('/users/login')->withErrors([
                    'error' => 'Unable to retrieve your Google account information. Please try again.'
                ]);
            }

            // Check if user already exists with this Google ID
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // Update user info if needed
                $user->update([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar ?? $user->avatar,
                ]);
                \Log::info('Google OAuth: Updated existing user with Google ID', ['user_id' => $user->id]);
            } else {
                // Check if user exists with this email
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Link Google account to existing user
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar ?? $user->avatar,
                    ]);
                    \Log::info('Google OAuth: Linked Google account to existing user', ['user_id' => $user->id]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'role' => 'Client',
                        'email_verified_at' => now(),
                        'password' => null, // OAuth users don't need password
                    ]);
                    \Log::info('Google OAuth: Created new user', ['user_id' => $user->id]);
                }
            }

            Auth::login($user, true);
            \Log::info('Google OAuth: Successfully logged in user', ['user_id' => $user->id]);

            // Redirect based on user role
            $redirectUrl = match ($user->role) {
                'Admin' => '/admin',
                'Staff', 'Doctor' => '/staff',
                'Client' => '/users/dashboard',
                default => '/',
            };

            return redirect($redirectUrl)->with('success', 'Successfully logged in with Google!');

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Google OAuth: Invalid state exception', [
                'error' => $e->getMessage(),
                'session_id' => session()->getId(),
                'session_token' => session()->token(),
                'request_state' => request()->get('state'),
                'session_state' => session()->get('state')
            ]);

            // Clear session and redirect
            session()->flush();
            return redirect('/users/login')->withErrors([
                'error' => 'Authentication session expired. Please clear your browser cache and try again.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Google OAuth: General exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect('/users/login')->withErrors([
                'error' => 'Unable to login using Google: ' . $e->getMessage()
            ]);
        }
    }

    // API Methods for mobile/SPA authentication
    public function registerApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                'confirmed'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'Client',
            ]);

            // Log the user in for web session
            Auth::login($user);
            $request->session()->regenerate();

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully',
                'user' => $user,
                'token' => $token,
                'redirect_url' => '/users/dashboard'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    public function loginApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        if (!Auth::attempt($credentials, $remember)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        // Regenerate session for security
        $request->session()->regenerate();

        // Create API token for mobile/API usage
        $token = $user->createToken('auth-token')->plainTextToken;

        // Determine redirect URL based on user role
        $redirectUrl = match ($user->role) {
            'Admin' => '/admin',
            'Staff', 'Doctor' => '/staff',
            'Client' => '/users/dashboard',
            default => '/users/dashboard',
        };

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
            'redirect_url' => $redirectUrl
        ]);
    }

    public function redirectToGoogleApi(Request $request)
    {
        try {
            $redirectUrl = $request->input('redirect_url', config('app.url') . '/auth/google/callback');

            return response()->json([
                'success' => true,
                'auth_url' => Socialite::driver('google')
                    ->redirectUrl($redirectUrl)
                    ->redirect()
                    ->getTargetUrl()
            ]);
        } catch (\Exception $e) {
            \Log::error('Google OAuth API: Redirect error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to connect to Google. Please check the configuration or try again later.'
            ], 500);
        }
    }

    public function handleGoogleCallbackApi(Request $request)
    {
        try {
            // Get user from Google
            $googleUser = Socialite::driver('google')->user();

            // Log the Google user data for debugging
            \Log::info('Google OAuth API User Data:', [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'avatar' => $googleUser->avatar,
            ]);

            // Validate required fields
            if (!$googleUser->email || !$googleUser->name) {
                \Log::error('Google OAuth API: Missing required user data');
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to retrieve your Google account information. Please try again.'
                ], 400);
            }

            // Check if user already exists with this Google ID
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // Update user info if needed
                $user->update([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar ?? $user->avatar,
                ]);
                \Log::info('Google OAuth API: Updated existing user with Google ID', ['user_id' => $user->id]);
            } else {
                // Check if user exists with this email
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Link Google account to existing user
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar ?? $user->avatar,
                    ]);
                    \Log::info('Google OAuth API: Linked Google account to existing user', ['user_id' => $user->id]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'role' => 'Client',
                        'email_verified_at' => now(),
                        'password' => null, // OAuth users don't need password
                    ]);
                    \Log::info('Google OAuth API: Created new user', ['user_id' => $user->id]);
                }
            }

            $token = $user->createToken('auth-token')->plainTextToken;
            \Log::info('Google OAuth API: Successfully authenticated user', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged in with Google!',
                'user' => $user,
                'token' => $token
            ]);

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Google OAuth API: Invalid state exception', [
                'error' => $e->getMessage(),
                'session_id' => session()->getId(),
                'request_state' => request()->get('state')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Authentication session expired. Please clear your browser cache and try again.'
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Google OAuth API: General exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to login using Google: ' . $e->getMessage()
            ], 500);
        }
    }
}
