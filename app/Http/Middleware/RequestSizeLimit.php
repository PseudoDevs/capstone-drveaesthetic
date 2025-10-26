<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestSizeLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $maxSize = 1048576): Response
    {
        // Check Content-Length header
        $contentLength = $request->header('Content-Length');
        if ($contentLength && (int)$contentLength > $maxSize) {
            return response()->json([
                'success' => false,
                'message' => 'Request too large. Maximum size allowed: ' . $this->formatBytes($maxSize)
            ], 413);
        }

        // Check actual request size
        $requestSize = strlen($request->getContent());
        if ($requestSize > $maxSize) {
            return response()->json([
                'success' => false,
                'message' => 'Request too large. Maximum size allowed: ' . $this->formatBytes($maxSize)
            ], 413);
        }

        return $next($request);
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
