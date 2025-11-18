# Quick Checklist: Remove Notification Preferences

## 📝 Files to Update (3 files)

### 1. `app/Filament/Resources/NotificationPreferenceResource.php`
- [ ] Add `shouldRegisterNavigation()` method that returns `false`
- [ ] Place it after `$navigationSort` property

### 2. `routes/web.php`
- [ ] Search for "notification-preferences"
- [ ] Remove any routes that reference notification preferences
- [ ] Look for routes like:
  - `Route::get('/notification-preferences', ...)`
  - `Route::put('/notification-preferences', ...)`

### 3. `resources/views/layouts/app.blade.php`
- [ ] Search for "notification-preferences" or "Notification Preferences"
- [ ] Remove links from desktop navigation (MY ACCOUNT dropdown)
- [ ] Remove links from mobile navigation menu

## 🔧 Commands to Run

```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## ✅ Verification

- [ ] Admin panel: No "Notification Preferences" in navigation
- [ ] Client dashboard: No "Notification Preferences" link in MY ACCOUNT
- [ ] Mobile menu: No "Notification Preferences" link
- [ ] Direct URL test: `/notification-preferences` returns 404

---

**See REMOVE_NOTIFICATION_PREFERENCES_GUIDE.md for detailed instructions.**

