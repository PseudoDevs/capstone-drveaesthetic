#!/bin/bash
# EMAIL DIAGNOSTIC SCRIPT FOR HOSTED SERVER
# Run this on your hosted server via SSH

echo "🔍 EMAIL DIAGNOSTIC FOR HOSTED SERVER"
echo "====================================="
echo ""

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    echo "❌ Not in a Laravel project directory"
    echo "Please run this script from your Laravel project root"
    exit 1
fi

echo "✅ Laravel project detected"
echo ""

# 1. Check environment variables
echo "1️⃣ ENVIRONMENT VARIABLES:"
echo "-------------------------"
if [ -f ".env" ]; then
    echo "✅ .env file exists"
    echo ""
    echo "Mail Configuration:"
    grep "MAIL_" .env | while read line; do
        if [[ $line == *"PASSWORD"* ]]; then
            echo "MAIL_PASSWORD=***SET***"
        else
            echo "$line"
        fi
    done
else
    echo "❌ .env file not found"
fi
echo ""

# 2. Check Laravel configuration
echo "2️⃣ LARAVEL CONFIGURATION:"
echo "--------------------------"
php artisan config:show mail 2>/dev/null || echo "❌ Cannot run artisan commands"
echo ""

# 3. Check queue status
echo "3️⃣ QUEUE STATUS:"
echo "----------------"
php artisan queue:failed 2>/dev/null || echo "❌ Cannot check queue status"
echo ""

# 4. Test database connection
echo "4️⃣ DATABASE CONNECTION:"
echo "------------------------"
php artisan tinker --execute="echo 'Database: ' . config('database.default') . PHP_EOL; try { DB::connection()->getPdo(); echo '✅ Connected' . PHP_EOL; } catch(Exception \$e) { echo '❌ Failed: ' . \$e->getMessage() . PHP_EOL; }" 2>/dev/null || echo "❌ Cannot test database"
echo ""

# 5. Check PHP mail function
echo "5️⃣ PHP MAIL FUNCTION:"
echo "---------------------"
php -r "if (function_exists('mail')) { echo '✅ mail() function available' . PHP_EOL; } else { echo '❌ mail() function not available' . PHP_EOL; }"
echo ""

# 6. Check server ports
echo "6️⃣ NETWORK CONNECTIVITY:"
echo "------------------------"
echo "Testing SMTP connectivity..."
timeout 5 bash -c "</dev/tcp/smtp.gmail.com/587" 2>/dev/null && echo "✅ Port 587 (Gmail SMTP) accessible" || echo "❌ Port 587 blocked"
timeout 5 bash -c "</dev/tcp/smtp.gmail.com/465" 2>/dev/null && echo "✅ Port 465 (Gmail SMTP SSL) accessible" || echo "❌ Port 465 blocked"
echo ""

# 7. Check file permissions
echo "7️⃣ FILE PERMISSIONS:"
echo "--------------------"
if [ -w "storage/logs" ]; then
    echo "✅ storage/logs writable"
else
    echo "❌ storage/logs not writable"
fi

if [ -w "bootstrap/cache" ]; then
    echo "✅ bootstrap/cache writable"
else
    echo "❌ bootstrap/cache not writable"
fi
echo ""

# 8. Check recent logs
echo "8️⃣ RECENT LOGS:"
echo "---------------"
if [ -f "storage/logs/laravel.log" ]; then
    echo "Recent log entries (last 5 lines):"
    tail -5 storage/logs/laravel.log
else
    echo "❌ No log file found"
fi
echo ""

echo "🏁 DIAGNOSTIC COMPLETE"
echo ""
echo "💡 COMMON SOLUTIONS:"
echo "===================="
echo "1. Set MAIL_MAILER=smtp in .env file"
echo "2. Configure correct SMTP credentials"
echo "3. Run: php artisan queue:work --daemon"
echo "4. Check hosting provider email restrictions"
echo "5. Use hosting provider's SMTP settings"
echo "6. Contact hosting support for SMTP access"
echo ""
echo "For detailed web-based diagnostic, upload email-diagnostic.php to your server"
echo "and access it via: https://yourdomain.com/email-diagnostic.php?key=diagnostic2024"

