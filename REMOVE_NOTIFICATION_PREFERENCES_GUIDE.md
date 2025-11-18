# Guide: Remove Notification Preferences from Hosted Web

This guide will help you remove the notification preferences page and links from your hosted website.

---

## Step 1: Hide Notification Preferences from Admin Panel

### File: `app/Filament/Resources/NotificationPreferenceResource.php`

**Location:** Add this method after the `$navigationSort` property (around line 22)

**Add this method:**
```php
public static function shouldRegisterNavigation(): bool
{
    return false;
}
```

**The file should look like this:**
```php
protected static ?string $navigationGroup = 'User Management';

protected static ?int $navigationSort = 3;

public static function shouldRegisterNavigation(): bool
{
    return false;
}

public static function form(Form $form): Form
{
    // ... rest of the code
}
```

---

## Step 2: Remove Routes (If They Exist)

### File: `routes/web.php`

**Check if these routes exist** (search for "notification-preferences"):

If you find routes like this:
```php
Route::get('/notification-preferences', [NotificationPreferenceController::class, 'index'])
    ->middleware('auth')
    ->name('notification-preferences');

Route::put('/notification-preferences', [NotificationPreferenceController::class, 'update'])
    ->middleware('auth')
    ->name('notification-preferences.update');
```

**Delete them completely.**

---

## Step 3: Remove Navigation Links from Layout

### File: `resources/views/layouts/app.blade.php`

**Search for "notification-preferences" or "Notification Preferences" in the file.**

**Look for these patterns and remove them:**

1. **In the desktop navigation dropdown** (usually in the "MY ACCOUNT" section):
```php
<!-- REMOVE THIS -->
<a href="{{ route('notification-preferences') }}" class="dropdown-item">
    <i class="fas fa-bell"></i> Notification Preferences
</a>
```

2. **In the mobile sidebar navigation**:
```php
<!-- REMOVE THIS -->
<a href="{{ route('notification-preferences') }}" class="nav-link">
    <i class="fas fa-bell"></i> Notification Preferences
</a>
```

**Or if it's in a list:**
```php
<!-- REMOVE THIS -->
<li>
    <a href="{{ route('notification-preferences') }}">Notification Preferences</a>
</li>
```

---

## Step 4: Clear Caches

After making the changes, SSH into your hosted server and run:

```bash
# Navigate to your project directory
cd /path/to/your/project

# Clear all caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## Step 5: Verify Changes

1. **Check Admin Panel:**
   - Log in to the admin panel
   - Verify "Notification Preferences" is NOT visible in the navigation menu

2. **Check Client Dashboard:**
   - Log in as a client
   - Check the "MY ACCOUNT" dropdown menu
   - Verify "Notification Preferences" link is NOT there

3. **Check Mobile Menu:**
   - Check the mobile navigation menu
   - Verify "Notification Preferences" link is NOT there

4. **Test Direct Access (Should Return 404):**
   - Try accessing: `yourdomain.com/notification-preferences`
   - Should return a 404 error or redirect

---

## Files That May Need Changes

### ✅ Must Update:
1. `app/Filament/Resources/NotificationPreferenceResource.php` - Hide from navigation
2. `routes/web.php` - Remove routes (if they exist)
3. `resources/views/layouts/app.blade.php` - Remove navigation links

### ⚠️ Optional (Don't Delete):
- `app/Http/Controllers/NotificationPreferenceController.php` - Keep for API/mobile app
- `app/Models/NotificationPreference.php` - Keep for database
- `resources/views/notification-preferences.blade.php` - Keep (won't be accessible)

**Note:** We're only hiding/removing the web interface. The API endpoints and database model are kept for mobile app functionality.

---

## Quick Checklist

- [ ] Added `shouldRegisterNavigation()` method to `NotificationPreferenceResource.php`
- [ ] Removed notification preferences routes from `routes/web.php` (if they exist)
- [ ] Removed navigation links from `resources/views/layouts/app.blade.php`
- [ ] Cleared all caches
- [ ] Verified changes in admin panel
- [ ] Verified changes in client dashboard
- [ ] Tested that direct URL returns 404

---

## Troubleshooting

### If "Notification Preferences" still appears:
1. **Clear browser cache:** Hard refresh (Ctrl+F5 or Cmd+Shift+R)
2. **Check file permissions:** Ensure files are readable
3. **Verify file was saved:** Check that changes were actually saved
4. **Check for duplicate files:** Look in `capstone-drveaesthetic/` subdirectory if it exists

### If you get errors:
1. **Check Laravel logs:** `storage/logs/laravel.log`
2. **Verify syntax:** Make sure PHP syntax is correct
3. **Check route cache:** Run `php artisan route:clear`

---

## What Stays (For Mobile App)

These are kept for mobile app functionality:
- ✅ API routes in `routes/api.php` (for mobile app)
- ✅ `NotificationPreferenceController` (used by API)
- ✅ `NotificationPreference` model (database)
- ✅ Database table `notification_preferences`

Only the **web interface** is being removed.

---

**Need Help?** If you encounter issues:
1. Check that all files were updated correctly
2. Verify no syntax errors
3. Check server error logs
4. Ensure caches were cleared

