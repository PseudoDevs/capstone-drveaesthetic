# Quick Deployment Checklist

## ✅ Pre-Deployment
- [ ] Backup all files listed below
- [ ] Access to hosted server (FTP/SSH)
- [ ] Access to Laravel command line on server

## 📝 Files to Update (5 files)

### 1. `app/Models/Bill.php`
- [ ] Update `updateBalance()` method
- [ ] Add capping logic to prevent negative balances

### 2. `app/Filament/Staff/Resources/PaymentResource/Pages/CreatePayment.php`
- [ ] Update `mutateFormDataBeforeCreate()` method
- [ ] Add `afterCreate()` method

### 3. `app/Filament/Staff/Resources/PaymentResource/Pages/EditPayment.php`
- [ ] Add `afterSave()` method

### 4. `app/Http/Controllers/DashboardController.php`
- [ ] Update payment query in `billingDashboard()` method

### 5. `resources/views/filament/staff/modals/balance-details.blade.php`
- [ ] Add dark mode classes to Recent Payments section
- [ ] Add dark mode classes to other modal sections

## 🔧 Commands to Run (After File Upload)

```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan bills:recalculate-balances
```

## ✅ Post-Deployment Testing

- [ ] Create a new payment → Check bill balance updates
- [ ] Edit a payment → Check bill balance updates
- [ ] View client billing dashboard → Check payments are visible
- [ ] Switch to dark mode → Check payment amounts are visible
- [ ] Check for negative balances → Should be fixed

## 🚨 If Issues Occur

1. Check file permissions
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify all files were uploaded correctly
4. Run commands again
5. Clear browser cache

---

**See DEPLOYMENT_GUIDE.md for detailed instructions with code snippets.**

