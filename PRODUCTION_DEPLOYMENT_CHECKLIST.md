# Production Deployment Checklist

## 🚀 Required Steps for Production Deployment

### 1. Environment Configuration
- [ ] Set `APP_ENV=production` in .env
- [ ] Set `APP_DEBUG=false` in .env 
- [ ] Configure correct `APP_URL` (production domain)
- [ ] Set up production database connection
- [ ] Configure session/cache drivers properly

### 2. Asset Building & Deployment
```bash
# Build production assets
npm run build

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Database Migration & Seeding
```bash
# Run migrations
php artisan migrate --force

# Seed if needed (be careful in production!)
php artisan db:seed --force
```

### 4. File Permissions (Linux/Unix servers)
```bash
# Storage and cache permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

### 5. Web Server Configuration
- [ ] Point document root to `/public` directory
- [ ] Configure URL rewriting (Apache .htaccess / Nginx rules)
- [ ] Enable HTTPS if needed
- [ ] Set up proper error pages

### 6. Asset Deployment Issues
**Most Common Problem:** Built assets not deployed to production

Check these files exist on production:
- [ ] `public/build/manifest.json`
- [ ] `public/build/assets/chat-*.js`
- [ ] `public/build/assets/app-*.css`

### 7. JavaScript/API Issues
- [ ] Check browser console for errors
- [ ] Verify API endpoints work: `/api/client/chats`
- [ ] Check CSRF token configuration
- [ ] Verify mixed content issues (HTTP vs HTTPS)

### 8. Database Issues
- [ ] Verify production database has same data as local
- [ ] Check if chat messages exist in production DB
- [ ] Verify user authentication works

## 🔧 Quick Debug Commands

### Check if assets are built:
```bash
ls -la public/build/assets/
```

### Test API endpoints:
```bash
curl -H "Accept: application/json" https://yourdomain.com/api/client/chats
```

### Check Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

### Test database connection:
```bash
php artisan tinker
> DB::connection()->getPdo();
```

## 🐛 Common Production Issues

1. **Assets not built**: Run `npm run build` and deploy `/public/build/`
2. **Wrong APP_URL**: Update .env with production domain
3. **Cache issues**: Clear all caches with artisan commands
4. **Permission issues**: Fix storage/bootstrap permissions
5. **Database empty**: Import production data or run migrations
6. **HTTPS/Mixed content**: Ensure all requests use same protocol
7. **CSRF issues**: Check token generation in production
8. **JavaScript errors**: Check browser console for errors

## 🎯 Specific Chat Issues

1. **Messages not loading**: Check `/api/client/chats/messages/{userId}` endpoint
2. **Sending fails**: Verify CSRF token and validation
3. **Real-time not working**: Check Server-Sent Events (SSE) configuration
4. **Authentication issues**: Verify session/sanctum configuration