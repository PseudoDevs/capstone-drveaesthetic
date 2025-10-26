<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // Convert to milliseconds
        
        // Log the request
        $this->logRequest($request, $response, $duration);
        
        return $response;
    }

    /**
     * Log the request for audit purposes
     */
    private function logRequest(Request $request, Response $response, float $duration): void
    {
        $user = Auth::user();
        
        // Only log for authenticated users
        if (!$user) {
            return;
        }
        
        // Determine if this is a sensitive operation
        $isSensitiveOperation = $this->isSensitiveOperation($request);
        
        // Log sensitive operations and admin actions
        if ($isSensitiveOperation || $user->role === 'Admin') {
            $logData = [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'status_code' => $response->getStatusCode(),
                'duration_ms' => $duration,
                'timestamp' => now(),
                'sensitive_operation' => $isSensitiveOperation,
            ];
            
            // Add request data for sensitive operations
            if ($isSensitiveOperation) {
                $logData['request_data'] = $this->sanitizeRequestData($request);
            }
            
            // Log based on severity
            if ($isSensitiveOperation) {
                \Log::warning('Sensitive operation performed', $logData);
            } else {
                \Log::info('Admin operation performed', $logData);
            }
        }
    }

    /**
     * Check if this is a sensitive operation
     */
    private function isSensitiveOperation(Request $request): bool
    {
        $sensitivePaths = [
            '/admin/users',
            '/admin/appointments',
            '/admin/bills',
            '/admin/payments',
            '/admin/medical-certificates',
            '/admin/reports',
            '/staff/users',
            '/staff/appointments',
            '/staff/bills',
        ];
        
        $sensitiveMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];
        
        $path = $request->path();
        $method = $request->method();
        
        // Check for sensitive paths
        foreach ($sensitivePaths as $sensitivePath) {
            if (str_contains($path, $sensitivePath)) {
                return true;
            }
        }
        
        // Check for sensitive methods on any path
        if (in_array($method, $sensitiveMethods)) {
            return true;
        }
        
        // Check for specific sensitive operations
        $sensitiveOperations = [
            'create',
            'update',
            'delete',
            'destroy',
            'store',
            'edit',
            'generate',
            'export',
            'download',
        ];
        
        foreach ($sensitiveOperations as $operation) {
            if (str_contains($path, $operation)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Sanitize request data to remove sensitive information
     */
    private function sanitizeRequestData(Request $request): array
    {
        $data = $request->all();
        
        // Remove sensitive fields
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            'api_key',
            'secret',
            'credit_card',
            'ssn',
            'social_security',
        ];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[REDACTED]';
            }
        }
        
        // Remove nested sensitive data
        $this->sanitizeNestedData($data);
        
        return $data;
    }

    /**
     * Recursively sanitize nested data
     */
    private function sanitizeNestedData(array &$data): void
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $this->sanitizeNestedData($value);
            } elseif (is_string($value) && $this->isSensitiveValue($key, $value)) {
                $value = '[REDACTED]';
            }
        }
    }

    /**
     * Check if a value is sensitive
     */
    private function isSensitiveValue(string $key, string $value): bool
    {
        $sensitivePatterns = [
            '/password/i',
            '/token/i',
            '/secret/i',
            '/key/i',
            '/credit/i',
            '/card/i',
            '/ssn/i',
            '/social/i',
        ];
        
        foreach ($sensitivePatterns as $pattern) {
            if (preg_match($pattern, $key) || preg_match($pattern, $value)) {
                return true;
            }
        }
        
        return false;
    }
}
