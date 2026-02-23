# QUICK TESTING GUIDE
**Start testing your Money Management system now!**

## Login Credentials
```
URL: http://moneymanagement.com/login
Email: admin@test.com
Password: admin123
```

## Testing Workflow (Step-by-Step)

### 1. Login & Dashboard
- [ ] Login with credentials above
- [ ] Verify dashboard loads
- [ ] Check if "Default Company" is shown

### 2. Company Management (Admin)
- [ ] Go to Companies menu
- [ ] Edit "Default Company" - Add details:
  - Name: Your Company Name
  - Address: Your Address
  - Phone: Your Phone
  - Upload Logo (optional)
- [ ] Save and verify changes

### 3. Create Test Debtors
Go to Debtors → Create New

**Individual Debtor:**
- [ ] Name: John Doe
- [ ] Type: Individual
- [ ] Staff Number: EMP001
- [ ] IC Number: 900101-01-1234
- [ ] Phone: 012-3456789
- [ ] Starting Outstanding: 1000.00

**Company Debtor:**
- [ ] Name: ABC Sdn Bhd
- [ ] Type: Company
- [ ] SSM Number: 123456-A
- [ ] Office Phone: 03-12345678
- [ ] Starting Outstanding: 5000.00

### 4. Record Payments
- [ ] Go to Debtors list
- [ ] Click on John Doe
- [ ] Click "Add Payment"
  - Amount: 500.00
  - Description: Payment installment 1
  - Date: Today
- [ ] Verify outstanding reduced to 500.00

### 5. Balance Adjustments
- [ ] Go to John Doe's details
- [ ] Click "Adjust Balance"
  - Type: Increase
  - Amount: 100.00
  - Reason: Late penalty
- [ ] Verify outstanding now 600.00

### 6. Generate Reports
- [ ] Go to Reports menu
- [ ] Try "Outstanding Report" - Click "Generate PDF"
- [ ] Try "Debtor Payment History" - Select John Doe - Generate PDF
- [ ] Try "All Transactions" - Generate PDF

### 7. User Management (Admin)
- [ ] Go to Users menu
- [ ] Create new user:
  - Name: Test User
  - Email: test@test.com
  - Password: test123
  - Role: User
  - Permissions: Select a few
- [ ] Save and verify

### 8. Company Switching (if multiple companies)
- [ ] Top-right corner - Click company dropdown
- [ ] Switch between companies
- [ ] Verify data changes based on company

### 9. Profile Management
- [ ] Click your name (top-right)
- [ ] Go to Profile
- [ ] Try changing name/email
- [ ] Try changing password

### 10. Session Management
- [ ] Go to Sessions menu
- [ ] View active sessions
- [ ] Check last activity

## What to Look For:

### ✓ Visual Checks:
- Professional UI with proper colors
- Tailwind CSS styling applied
- Forms validated properly
- Success/error messages appear
- Tables display correctly
- Buttons and links work

### ✓ Functionality Checks:
- Data saves correctly
- Outstanding balances calculate automatically
- PDFs generate and download
- Search/filter works
- Pagination works (if many records)
- Company isolation works (multi-tenancy)

### ✓ Security Checks:
- Can't access admin pages as regular user
- CSRF protection working (forms have tokens)
- Logout works properly
- Session expires after inactivity

## Common Issues & Solutions:

### Issue: "ViteManifestNotFoundException"
**Solution:** Run `npm run build` then copy manifest:
```powershell
npm run build
copy public\build\.vite\manifest.json public\build\manifest.json
```

### Issue: "403 Forbidden"
**Solution:** Clear Laravel cache:
```powershell
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### Issue: PDFs not generating
**Check:**
1. dompdf installed (should be via composer)
2. GD extension enabled in php.ini
3. Temp folder writable

### Issue: Company logo not showing
**Check:**
1. Storage folder permissions
2. Storage link created: `php artisan storage:link`
3. File upload working (check max file size in php.ini)

## Advanced Testing (Optional):

### Performance Test:
- [ ] Create 50+ debtors
- [ ] Record 100+ payments
- [ ] Generate large reports
- [ ] Check page load times
- [ ] Monitor database query speed

### Multi-User Test:
- [ ] Login with different users
- [ ] Test permission restrictions
- [ ] Verify data isolation

### Data Integrity:
- [ ] Try entering invalid data (negative amounts)
- [ ] Try deleting a debtor with payments
- [ ] Check foreign key constraints
- [ ] Verify audit trails (user_id recorded)

## Browser Testing:
- [ ] Test on Google Chrome
- [ ] Test on Mozilla Firefox
- [ ] Test on Microsoft Edge
- [ ] Test on mobile device (responsive)

## Ready for Production?

Once you've tested everything and it works, do this before going live:

1. **Security:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Cache for Performance:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Backup:**
   - Set up automated MySQL backups
   - Backup files folder
   - Document backup restore procedure

4. **Monitoring:**
   - Set up log monitoring
   - Monitor disk space
   - Monitor Apache/MySQL status

5. **SSL:**
   - Install SSL certificate
   - Force HTTPS in .env: `APP_URL=https://...`

---

**Happy Testing! 🎉**

If everything works as expected, your system is ready for real data!
