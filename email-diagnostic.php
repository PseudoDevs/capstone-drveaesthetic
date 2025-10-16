<?php
/**
 * EMAIL DIAGNOSTIC TOOL FOR HOSTED WEBSITE
 * 
 * Upload this file to your hosted website root directory
 * Access it via: https://yourdomain.com/email-diagnostic.php
 * 
 * This will help identify email issues on your live server
 */

// Basic security check
if (!isset($_GET['key']) || $_GET['key'] !== 'diagnostic2024') {
    die('Access denied. Add ?key=diagnostic2024 to URL');
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Email Diagnostic - Dr. Ve Aesthetic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
        .section { margin: 20px 0; padding: 15px; border-left: 4px solid #007bff; background: #f8f9fa; }
        pre { background: #f1f1f1; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Email Diagnostic Tool</h1>
        <p><strong>Dr. Ve Aesthetic Clinic</strong> - Hosted Website Email Check</p>
        <hr>";

// Check if Laravel is available
if (!file_exists('vendor/autoload.php')) {
    echo "<div class='error'>❌ Laravel not found. Make sure this file is in your Laravel project root.</div>";
    exit;
}

try {
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "<div class='success'>✅ Laravel loaded successfully</div>";
    
    // 1. Environment Check
    echo "<div class='section'>
        <h2>1️⃣ Environment Variables Check</h2>";
    
    $envVars = [
        'MAIL_MAILER' => env('MAIL_MAILER'),
        'MAIL_HOST' => env('MAIL_HOST'),
        'MAIL_PORT' => env('MAIL_PORT'),
        'MAIL_USERNAME' => env('MAIL_USERNAME'),
        'MAIL_PASSWORD' => env('MAIL_PASSWORD') ? '***SET***' : 'NOT SET',
        'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
        'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
        'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),
        'APP_URL' => env('APP_URL'),
        'QUEUE_CONNECTION' => env('QUEUE_CONNECTION'),
    ];
    
    foreach ($envVars as $key => $value) {
        $status = $value ? 'success' : 'error';
        echo "<div class='$status'>$key: " . ($value ?: 'NOT SET') . "</div>";
    }
    echo "</div>";
    
    // 2. Configuration Check
    echo "<div class='section'>
        <h2>2️⃣ Mail Configuration Check</h2>";
    
    echo "<div class='info'>Default Mailer: " . config('mail.default') . "</div>";
    echo "<div class='info'>SMTP Host: " . config('mail.mailers.smtp.host') . "</div>";
    echo "<div class='info'>SMTP Port: " . config('mail.mailers.smtp.port') . "</div>";
    echo "<div class='info'>From Address: " . config('mail.from.address') . "</div>";
    echo "<div class='info'>From Name: " . config('mail.from.name') . "</div>";
    echo "</div>";
    
    // 3. Queue Check
    echo "<div class='section'>
        <h2>3️⃣ Queue System Check</h2>";
    
    echo "<div class='info'>Queue Driver: " . config('queue.default') . "</div>";
    
    try {
        $pendingJobs = \Illuminate\Support\Facades\DB::table('jobs')->count();
        $failedJobs = \Illuminate\Support\Facades\DB::table('failed_jobs')->count();
        
        echo "<div class='info'>Pending Jobs: $pendingJobs</div>";
        echo "<div class='info'>Failed Jobs: $failedJobs</div>";
        
        if ($failedJobs > 0) {
            echo "<div class='warning'>⚠️ You have $failedJobs failed jobs. Check the failed_jobs table for details.</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Queue check failed: " . $e->getMessage() . "</div>";
    }
    echo "</div>";
    
    // 4. Database Connection Check
    echo "<div class='section'>
        <h2>4️⃣ Database Connection Check</h2>";
    
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        echo "<div class='success'>✅ Database connected successfully</div>";
        
        // Check if jobs table exists
        $tables = \Illuminate\Support\Facades\DB::select("SHOW TABLES LIKE 'jobs'");
        if (count($tables) > 0) {
            echo "<div class='success'>✅ Jobs table exists</div>";
        } else {
            echo "<div class='error'>❌ Jobs table missing. Run: php artisan queue:table</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Database connection failed: " . $e->getMessage() . "</div>";
    }
    echo "</div>";
    
    // 5. Test Email Sending
    echo "<div class='section'>
        <h2>5️⃣ Email Sending Test</h2>";
    
    if (isset($_POST['test_email'])) {
        $testEmail = $_POST['test_email'];
        
        try {
            \Illuminate\Support\Facades\Mail::raw('Test email from hosted server - ' . date('Y-m-d H:i:s'), function ($message) use ($testEmail) {
                $message->to($testEmail)
                        ->subject('Test Email from Dr. Ve Aesthetic Hosted Server');
            });
            
            echo "<div class='success'>✅ Test email sent successfully to $testEmail</div>";
            echo "<div class='info'>Check your inbox (including spam folder)</div>";
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ Test email failed: " . $e->getMessage() . "</div>";
        }
    }
    
    echo "<form method='POST'>
        <label>Test Email Address:</label><br>
        <input type='email' name='test_email' placeholder='your-email@example.com' required style='width: 300px; padding: 5px;'>
        <button type='submit' class='btn'>Send Test Email</button>
    </form>";
    echo "</div>";
    
    // 6. Server Information
    echo "<div class='section'>
        <h2>6️⃣ Server Information</h2>";
    
    echo "<div class='info'>PHP Version: " . PHP_VERSION . "</div>";
    echo "<div class='info'>Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</div>";
    echo "<div class='info'>Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</div>";
    echo "<div class='info'>Current Time: " . date('Y-m-d H:i:s T') . "</div>";
    
    // Check if mail() function is available
    if (function_exists('mail')) {
        echo "<div class='success'>✅ PHP mail() function available</div>";
    } else {
        echo "<div class='error'>❌ PHP mail() function not available</div>";
    }
    
    echo "</div>";
    
    // 7. Recommendations
    echo "<div class='section'>
        <h2>7️⃣ Recommendations</h2>";
    
    $recommendations = [];
    
    if (!env('MAIL_PASSWORD')) {
        $recommendations[] = "Set MAIL_PASSWORD in your .env file";
    }
    
    if (config('mail.default') === 'log') {
        $recommendations[] = "Change MAIL_MAILER to 'smtp' in .env file";
    }
    
    if ($pendingJobs > 10) {
        $recommendations[] = "Run queue worker: php artisan queue:work";
    }
    
    if (empty($recommendations)) {
        echo "<div class='success'>✅ Configuration looks good!</div>";
    } else {
        echo "<div class='warning'>⚠️ Issues found:</div>";
        foreach ($recommendations as $rec) {
            echo "<div class='warning'>• $rec</div>";
        }
    }
    
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error loading Laravel: " . $e->getMessage() . "</div>";
}

echo "<hr>
    <div class='section'>
        <h2>🔧 Quick Fixes</h2>
        <h3>If emails aren't working:</h3>
        <ol>
            <li><strong>Check .env file:</strong> Make sure MAIL_* variables are set correctly</li>
            <li><strong>Run queue worker:</strong> <code>php artisan queue:work --daemon</code></li>
            <li><strong>Check hosting provider:</strong> Some hosts block SMTP ports</li>
            <li><strong>Use hosting email:</strong> Try your hosting provider's SMTP settings</li>
            <li><strong>Check spam folder:</strong> Emails might be filtered</li>
        </ol>
        
        <h3>Common Hosting Solutions:</h3>
        <ul>
            <li><strong>Shared Hosting:</strong> Use hosting provider's SMTP</li>
            <li><strong>VPS/Cloud:</strong> Install Postfix or use external service</li>
            <li><strong>Platform Services:</strong> Add email addon (Mailgun, SendGrid)</li>
        </ul>
    </div>
    
    <p><small>Generated on: " . date('Y-m-d H:i:s') . "</small></p>
    </div>
</body>
</html>";
?>

