<?php
/**
 * Mobile Backend Connection Test Script
 * 
 * This script tests if your backend is properly configured for mobile app connections.
 * Run: php test-mobile-connection.php
 */

echo "\n";
echo "==============================================\n";
echo "  Mobile Backend Connection Test\n";
echo "==============================================\n\n";

// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$errors = [];
$warnings = [];
$success = [];

// Test 1: Check APP_URL
echo "1. Checking APP_URL...\n";
$appUrl = config('app.url');
if (str_starts_with($appUrl, 'https://')) {
    $success[] = "✓ APP_URL is using HTTPS: {$appUrl}";
} else {
    $warnings[] = "⚠ APP_URL should use HTTPS for mobile apps: {$appUrl}";
}

// Test 2: Check APP_ENV
echo "2. Checking APP_ENV...\n";
$appEnv = config('app.env');
if ($appEnv === 'production' && str_contains($appUrl, 'drveaestheticclinic.online')) {
    $success[] = "✓ APP_ENV is set to production";
} elseif ($appEnv === 'local' && str_contains($appUrl, 'drveaestheticclinic.online')) {
    $errors[] = "✗ APP_ENV is 'local' but using production URL! Should be 'production'";
} else {
    $success[] = "✓ APP_ENV is: {$appEnv}";
}

// Test 3: Check APP_DEBUG
echo "3. Checking APP_DEBUG...\n";
$appDebug = config('app.debug');
if ($appEnv === 'production' && $appDebug === true) {
    $errors[] = "✗ APP_DEBUG is TRUE in production! This is a SECURITY RISK!";
} elseif ($appDebug === false) {
    $success[] = "✓ APP_DEBUG is disabled";
} else {
    $warnings[] = "⚠ APP_DEBUG is enabled: " . ($appDebug ? 'true' : 'false');
}

// Test 4: Check Database Connection
echo "4. Checking database connection...\n";
try {
    DB::connection()->getPdo();
    $success[] = "✓ Database connection successful";
} catch (\Exception $e) {
    $errors[] = "✗ Database connection failed: " . $e->getMessage();
}

// Test 5: Check Sanctum Configuration
echo "5. Checking Laravel Sanctum...\n";
$sanctumStateful = config('sanctum.stateful');
if (!empty($sanctumStateful)) {
    $success[] = "✓ Sanctum stateful domains configured: " . implode(', ', $sanctumStateful);
} else {
    $warnings[] = "⚠ Sanctum stateful domains not configured";
}

// Test 6: Check API Routes
echo "6. Checking API routes...\n";
$routes = collect(Route::getRoutes())->filter(function($route) {
    return str_starts_with($route->uri(), 'api/client');
})->count();

if ($routes > 0) {
    $success[] = "✓ Found {$routes} API routes under /api/client";
} else {
    $errors[] = "✗ No API routes found under /api/client";
}

// Test 7: Check Google OAuth Configuration
echo "7. Checking Google OAuth...\n";
$googleClientId = config('services.google.client_id');
if (!empty($googleClientId)) {
    $success[] = "✓ Google OAuth configured";
} else {
    $warnings[] = "⚠ Google OAuth not configured";
}

// Test 8: Check Pusher Configuration
echo "8. Checking Pusher (for real-time chat)...\n";
$pusherKey = config('broadcasting.connections.pusher.key');
if (!empty($pusherKey)) {
    $success[] = "✓ Pusher configured for real-time features";
} else {
    $warnings[] = "⚠ Pusher not configured (real-time chat won't work)";
}

// Test 9: Check Mail Configuration
echo "9. Checking mail configuration...\n";
$mailHost = config('mail.mailers.smtp.host');
if (!empty($mailHost)) {
    $success[] = "✓ Mail configured: {$mailHost}";
} else {
    $warnings[] = "⚠ Mail not configured";
}

// Test 10: Check Storage Directory Permissions
echo "10. Checking storage permissions...\n";
$storagePath = storage_path();
if (is_writable($storagePath)) {
    $success[] = "✓ Storage directory is writable";
} else {
    $errors[] = "✗ Storage directory is not writable: {$storagePath}";
}

// Test 11: Check if key API endpoints exist
echo "11. Checking key API endpoints...\n";
$requiredEndpoints = [
    'api/client/auth/login',
    'api/client/auth/register',
    'api/client/services',
    'api/client/appointments',
    'api/client/bills/users/{clientId}',
    'api/client/payments/users/{clientId}',
];

$foundEndpoints = 0;
foreach ($requiredEndpoints as $endpoint) {
    $route = Route::getRoutes()->match(
        \Illuminate\Http\Request::create($endpoint, 'GET')
    );
    if ($route) {
        $foundEndpoints++;
    }
}

if ($foundEndpoints >= count($requiredEndpoints) - 2) { // Allow some to be POST only
    $success[] = "✓ Key API endpoints are accessible";
} else {
    $warnings[] = "⚠ Some API endpoints might be missing";
}

// Test 12: Check Session Configuration
echo "12. Checking session configuration...\n";
$sessionDriver = config('session.driver');
$success[] = "✓ Session driver: {$sessionDriver}";

// Display Results
echo "\n";
echo "==============================================\n";
echo "  Test Results\n";
echo "==============================================\n\n";

if (!empty($success)) {
    echo "✅ PASSED TESTS:\n";
    foreach ($success as $item) {
        echo "   {$item}\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  WARNINGS:\n";
    foreach ($warnings as $item) {
        echo "   {$item}\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "❌ ERRORS (MUST FIX):\n";
    foreach ($errors as $item) {
        echo "   {$item}\n";
    }
    echo "\n";
}

// Summary
$totalTests = count($success) + count($warnings) + count($errors);
echo "==============================================\n";
echo "Summary: ";
echo count($success) . " passed, ";
echo count($warnings) . " warnings, ";
echo count($errors) . " errors\n";
echo "==============================================\n\n";

// Recommendations
if (!empty($errors) || !empty($warnings)) {
    echo "📋 RECOMMENDATIONS:\n\n";
    
    if ($appEnv === 'local' && str_contains($appUrl, 'drveaestheticclinic.online')) {
        echo "1. Update .env file:\n";
        echo "   APP_ENV=production\n";
        echo "   APP_DEBUG=false\n\n";
    }
    
    if (empty($sanctumStateful)) {
        echo "2. Add to .env file:\n";
        echo "   SANCTUM_STATEFUL_DOMAINS=drveaestheticclinic.online,www.drveaestheticclinic.online\n";
        echo "   SESSION_DOMAIN=.drveaestheticclinic.online\n\n";
    }
    
    echo "3. After making changes, run:\n";
    echo "   php artisan config:clear\n";
    echo "   php artisan config:cache\n\n";
    
    echo "4. See MOBILE_BACKEND_CONNECTION_GUIDE.md for detailed instructions\n\n";
}

// Mobile App Configuration
echo "==============================================\n";
echo "  Mobile App Configuration\n";
echo "==============================================\n\n";
echo "Use these values in your mobile app:\n\n";
echo "API_BASE_URL: {$appUrl}\n";
echo "API_PREFIX: /api/client\n";
echo "PUSHER_KEY: {$pusherKey}\n";
echo "PUSHER_CLUSTER: " . config('broadcasting.connections.pusher.options.cluster') . "\n";
echo "\nFull API URL: {$appUrl}/api/client\n";
echo "\n";

// Test URLs
echo "==============================================\n";
echo "  Test These URLs from Mobile App\n";
echo "==============================================\n\n";
echo "1. Services:\n";
echo "   GET {$appUrl}/api/client/services\n\n";
echo "2. Login:\n";
echo "   POST {$appUrl}/api/client/auth/login\n";
echo "   Body: {\"email\":\"test@example.com\",\"password\":\"password123\"}\n\n";
echo "3. Register:\n";
echo "   POST {$appUrl}/api/client/auth/register\n\n";

echo "\nFor detailed API documentation, see:\n";
echo "- MOBILE_API_QUICK_REFERENCE.md\n";
echo "- API_AUTH_DOCUMENTATION.md\n\n";

exit(count($errors) > 0 ? 1 : 0);










