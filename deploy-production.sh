#!/bin/bash

echo "🚀 Starting Production Deployment..."

# 1. Build production assets
echo "📦 Building production assets..."
npm ci --only=production
npm run build

# 2. Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# 3. Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 4. Run migrations (if needed)
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 5. Set proper permissions (if on Linux/Unix)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    echo "🔐 Setting file permissions..."
    chmod -R 775 storage/
    chmod -R 775 bootstrap/cache/
    
    # Only run if www-data user exists
    if id "www-data" &>/dev/null; then
        chown -R www-data:www-data storage/
        chown -R www-data:www-data bootstrap/cache/
    fi
fi

# 6. Verify critical files
echo "✅ Verifying deployment..."

if [ -f "public/build/manifest.json" ]; then
    echo "✅ Assets built successfully"
else
    echo "❌ ERROR: Assets not built! Check npm run build"
    exit 1
fi

if [ -f ".env" ]; then
    echo "✅ Environment file exists"
else
    echo "❌ ERROR: .env file missing!"
    exit 1
fi

# 7. Test database connection
echo "🔌 Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connected successfully'; } catch(Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); }"

echo "🎉 Production deployment complete!"
echo ""
echo "📋 Next steps:"
echo "1. Verify website loads correctly"
echo "2. Test chat functionality"
echo "3. Check browser console for errors"
echo "4. Monitor Laravel logs: tail -f storage/logs/laravel.log"