<?php

// Production Environment Checker
echo "🔍 Production Environment Check\n\n";

// Check Laravel bootstrap
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    echo "✅ Laravel application bootstrapped successfully\n";
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 1. Environment checks
echo "\n📋 Environment Configuration:\n";
echo "APP_ENV: " . env('APP_ENV') . "\n";
echo "APP_DEBUG: " . (env('APP_DEBUG') ? 'true' : 'false') . "\n";
echo "APP_URL: " . env('APP_URL') . "\n";

// 2. Database check
echo "\n🗄️ Database Check:\n";
try {
    DB::connection()->getPdo();
    echo "✅ Database connection: SUCCESS\n";
    
    // Check tables exist
    $tables = ['users', 'chats', 'messages'];
    foreach ($tables as $table) {
        $count = DB::table($table)->count();
        echo "✅ Table '$table': $count records\n";
    }
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}

// 3. Asset checks
echo "\n📦 Asset Check:\n";
$manifestPath = public_path('build/manifest.json');
if (file_exists($manifestPath)) {
    echo "✅ Build manifest exists\n";
    $manifest = json_decode(file_get_contents($manifestPath), true);
    
    // Check specific assets
    $requiredAssets = ['resources/js/app.js', 'resources/js/chat.js', 'resources/css/app.css'];
    foreach ($requiredAssets as $asset) {
        if (isset($manifest[$asset])) {
            $assetPath = public_path('build/' . ltrim($manifest[$asset]['file'], '/'));
            if (file_exists($assetPath)) {
                echo "✅ Asset '$asset': EXISTS (" . round(filesize($assetPath)/1024, 2) . " KB)\n";
            } else {
                echo "❌ Asset '$asset': MISSING FILE\n";
            }
        } else {
            echo "❌ Asset '$asset': NOT IN MANIFEST\n";
        }
    }
} else {
    echo "❌ Build manifest missing! Run 'npm run build'\n";
}

// 4. Permissions check (Unix systems)
echo "\n🔐 Permissions Check:\n";
$directories = ['storage', 'bootstrap/cache'];
foreach ($directories as $dir) {
    if (is_writable($dir)) {
        echo "✅ Directory '$dir': WRITABLE\n";
    } else {
        echo "❌ Directory '$dir': NOT WRITABLE\n";
    }
}

// 5. Chat API check
echo "\n💬 Chat API Check:\n";
try {
    // Test chat endpoints
    $request = Illuminate\Http\Request::create('/api/client/chats', 'GET');
    $response = app()->handle($request);
    
    if ($response->getStatusCode() == 200) {
        echo "✅ Chat API endpoint: ACCESSIBLE\n";
        $data = json_decode($response->getContent(), true);
        if (isset($data['success']) && $data['success']) {
            echo "✅ Chat API response: VALID\n";
        } else {
            echo "⚠️ Chat API response: INVALID FORMAT\n";
        }
    } else {
        echo "❌ Chat API endpoint: ERROR " . $response->getStatusCode() . "\n";
    }
} catch (Exception $e) {
    echo "❌ Chat API test failed: " . $e->getMessage() . "\n";
}

// 6. Cache check
echo "\n🗃️ Cache Check:\n";
try {
    Cache::put('test_key', 'test_value', 60);
    $value = Cache::get('test_key');
    if ($value === 'test_value') {
        echo "✅ Cache system: WORKING\n";
        Cache::forget('test_key');
    } else {
        echo "❌ Cache system: NOT WORKING\n";
    }
} catch (Exception $e) {
    echo "❌ Cache test failed: " . $e->getMessage() . "\n";
}

echo "\n🎯 Summary:\n";
echo "If all checks show ✅, your production environment should be ready.\n";
echo "If you see ❌, address those issues before expecting the chat to work.\n\n";

echo "🔗 Test URLs to check manually:\n";
echo "- Homepage: " . env('APP_URL') . "\n";
echo "- Chat API: " . env('APP_URL') . "/api/client/chats\n";
echo "- Assets: " . env('APP_URL') . "/build/manifest.json\n\n";

echo "📊 Browser Console Check:\n";
echo "Open browser dev tools and look for:\n";
echo "- JavaScript errors (red messages)\n";
echo "- Failed network requests (in Network tab)\n";
echo "- Chat debug messages (🔧 ChatData initialized)\n";