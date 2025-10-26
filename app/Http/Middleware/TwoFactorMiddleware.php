<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Only apply to admin users
        if (!$user || $user->role !== 'Admin') {
            return $next($request);
        }
        
        // Check if 2FA is enabled for admin
        if (!$this->isTwoFactorEnabled($user)) {
            return $next($request);
        }
        
        // Check if 2FA is verified for this session
        if (!$this->isTwoFactorVerified($request, $user)) {
            return $this->redirectToTwoFactor($request);
        }
        
        return $next($request);
    }

    /**
     * Check if 2FA is enabled for the user
     */
    private function isTwoFactorEnabled($user): bool
    {
        // Check if user has 2FA enabled
        return $user->two_factor_enabled ?? false;
    }

    /**
     * Check if 2FA is verified for this session
     */
    private function isTwoFactorVerified(Request $request, $user): bool
    {
        $sessionKey = 'two_factor_verified_' . $user->id;
        
        // Check if 2FA was verified in this session
        if ($request->session()->has($sessionKey)) {
            $verifiedAt = $request->session()->get($sessionKey);
            
            // Check if verification is still valid (15 minutes)
            if ($verifiedAt && now()->diffInMinutes($verifiedAt) < 15) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Redirect to 2FA verification
     */
    private function redirectToTwoFactor(Request $request): Response
    {
        // Store the intended URL
        $request->session()->put('two_factor_intended_url', $request->fullUrl());
        
        // Redirect to 2FA verification page
        return redirect()->route('filament.admin.auth.two-factor')
            ->with('message', 'Please verify your identity with two-factor authentication.');
    }
}
