<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminRoleMiddleware
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
            return redirect()->route('filament.admin.auth.login');
        }

        $user = Auth::user();
        
        // Check if user has admin role
        if ($user->role !== 'Admin') {
            // Log unauthorized access attempt
            \Log::critical('Unauthorized admin panel access attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'timestamp' => now(),
            ]);

            // Redirect to appropriate panel
            if ($user->role === 'Client') {
                return redirect()->route('filament.client.auth.login')
                    ->with('error', 'Access denied. Please use the client portal.');
            } elseif (in_array($user->role, ['Staff', 'Doctor'])) {
                return redirect()->route('filament.staff.auth.login')
                    ->with('error', 'Access denied. Please use the staff portal.');
            }

            return abort(403, 'Access denied. Admin privileges required.');
        }

        // Enhanced security checks for admin
        $this->performAdminSecurityChecks($request, $user);

        return $next($request);
    }

    /**
     * Perform enhanced security checks for admin
     */
    private function performAdminSecurityChecks(Request $request, $user): void
    {
        // Check for suspicious activity
        if ($this->isSuspiciousActivity($request, $user)) {
            \Log::critical('Suspicious activity detected in admin panel', [
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'timestamp' => now(),
            ]);
        }

        // Check for admin-specific security requirements
        if (!$this->meetsAdminSecurityRequirements($request, $user)) {
            \Log::warning('Admin security requirements not met', [
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ]);
        }

        // Log admin access for audit trail
        $this->logAdminAccess($request, $user);
    }

    /**
     * Check for suspicious activity patterns
     */
    private function isSuspiciousActivity(Request $request, $user): bool
    {
        // Check for rapid requests (potential bot activity)
        $cacheKey = 'admin_requests_' . $user->id . '_' . $request->ip();
        $requestCount = \Cache::get($cacheKey, 0);
        
        if ($requestCount > 50) { // More than 50 requests in 1 minute
            return true;
        }
        
        \Cache::put($cacheKey, $requestCount + 1, 60); // 1 minute cache
        
        // Check for unusual user agent
        $userAgent = $request->userAgent();
        if (empty($userAgent) || strlen($userAgent) < 10) {
            return true;
        }
        
        // Check for suspicious IP patterns
        if ($this->isSuspiciousIP($request->ip())) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if IP is suspicious
     */
    private function isSuspiciousIP(string $ip): bool
    {
        // Check against known malicious IP ranges
        $suspiciousRanges = [
            '10.0.0.0/8',     // Private network (should not access admin from external)
            '192.168.0.0/16', // Private network
            '172.16.0.0/12',  // Private network
        ];
        
        foreach ($suspiciousRanges as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if IP is in range
     */
    private function ipInRange(string $ip, string $range): bool
    {
        if (strpos($range, '/') === false) {
            return $ip === $range;
        }
        
        list($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;
        
        return ($ip & $mask) === $subnet;
    }

    /**
     * Check admin security requirements
     */
    private function meetsAdminSecurityRequirements(Request $request, $user): bool
    {
        // Check if request is over HTTPS (in production)
        if (app()->environment('production') && !$request->secure()) {
            return false;
        }
        
        // Check for valid session
        if (!$request->hasSession() || !$request->session()->isStarted()) {
            return false;
        }
        
        // Check for recent password change (admin should change password regularly)
        if ($user->updated_at < now()->subDays(90)) {
            \Log::warning('Admin password may need updating', [
                'user_id' => $user->id,
                'last_updated' => $user->updated_at,
            ]);
        }
        
        return true;
    }

    /**
     * Log admin access for audit trail
     */
    private function logAdminAccess(Request $request, $user): void
    {
        \Log::info('Admin panel access', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => now(),
        ]);
    }

    /**
     * Check if middleware should be skipped for certain routes
     */
    private function shouldSkipMiddleware(Request $request): bool
    {
        $skipRoutes = [
            'filament.admin.auth.login',
            'filament.admin.auth.logout',
            'filament.admin.auth.register',
            'filament.admin.auth.password.request',
            'filament.admin.auth.password.reset',
        ];

        $currentRoute = $request->route()?->getName();
        
        return in_array($currentRoute, $skipRoutes) || 
               str_contains($request->path(), 'auth') ||
               str_contains($request->path(), 'login');
    }
}
