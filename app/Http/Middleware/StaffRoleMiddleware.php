<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class StaffRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip middleware for login and auth routes
        if ($this->shouldSkipMiddleware($request)) {
            return $next($request);
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('filament.staff.auth.login');
        }

        $user = Auth::user();
        
        // Check if user has staff role
        if (!in_array($user->role, ['Staff', 'Doctor', 'Admin'])) {
            // Log unauthorized access attempt
            \Log::warning('Unauthorized staff panel access attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'timestamp' => now(),
            ]);

            // Redirect to appropriate panel or show error
            if ($user->role === 'Client') {
                return redirect()->route('filament.client.auth.login')
                    ->with('error', 'Access denied. Please use the client portal.');
            }

            return abort(403, 'Access denied. Insufficient privileges.');
        }

        // Additional security checks for staff
        $this->performSecurityChecks($request, $user);

        return $next($request);
    }

    /**
     * Perform additional security checks
     */
    private function performSecurityChecks(Request $request, $user): void
    {
        // Check for suspicious activity
        if ($this->isSuspiciousActivity($request, $user)) {
            \Log::warning('Suspicious activity detected in staff panel', [
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'timestamp' => now(),
            ]);
        }

        // Check session security
        if (!$this->isSecureSession($request)) {
            \Log::warning('Insecure session detected in staff panel', [
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ]);
        }
    }

    /**
     * Check for suspicious activity patterns
     */
    private function isSuspiciousActivity(Request $request, $user): bool
    {
        // Check for rapid requests (potential bot activity)
        $cacheKey = 'staff_requests_' . $user->id . '_' . $request->ip();
        $requestCount = \Cache::get($cacheKey, 0);
        
        if ($requestCount > 100) { // More than 100 requests in 1 minute
            return true;
        }
        
        \Cache::put($cacheKey, $requestCount + 1, 60); // 1 minute cache
        
        // Check for unusual user agent
        $userAgent = $request->userAgent();
        if (empty($userAgent) || strlen($userAgent) < 10) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if session is secure
     */
    private function isSecureSession(Request $request): bool
    {
        // Check if request is over HTTPS (in production)
        if (app()->environment('production') && !$request->secure()) {
            return false;
        }
        
        // Check for valid session
        if (!$request->hasSession() || !$request->session()->isStarted()) {
            return false;
        }
        
        return true;
    }

    /**
     * Check if middleware should be skipped for certain routes
     */
    private function shouldSkipMiddleware(Request $request): bool
    {
        $skipRoutes = [
            'filament.staff.auth.login',
            'filament.staff.auth.logout',
            'filament.staff.auth.register',
            'filament.staff.auth.password.request',
            'filament.staff.auth.password.reset',
        ];

        $currentRoute = $request->route()?->getName();
        
        return in_array($currentRoute, $skipRoutes) || 
               str_contains($request->path(), 'auth') ||
               str_contains($request->path(), 'login');
    }
}
