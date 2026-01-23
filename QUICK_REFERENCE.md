# 🎯 Quick Reference - What to Test

## 🔐 Test with BOTH Account Types

### 1. ADMIN ACCOUNT (✅ Full Access)
```
Login at: http://127.0.0.1:8000
```

**Must Work:**
- ✅ Access ALL features
- ✅ See "Money Management" text in navbar (not company logo)
- ✅ Company Management menu
- ✅ Reports Center with PDF downloads
- ✅ User Management
- ✅ Session Management

**Test These:**
1. Dashboard → Click "Reports" quick action → Should go to /reports
2. Reports Center → Select company → Generate all 4 reports + PDFs
3. Create a new user with limited permissions
4. Verify company logos use object-contain (not cropped)
5. Verify RM 1,234.56 formatting everywhere

---

### 2. REGULAR USER (⚠️ Limited Access)
```
Login at: http://127.0.0.1:8000
```

**Must Work:**
- ✅ See COMPANY LOGO in navbar (not "Money Management" text)
- ✅ Dashboard with filtered data
- ✅ Company selection (assigned companies only)
- ✅ Debtor/Payment management (if has permission)

**Must NOT Work (Should get 403 error):**
- ❌ /companies (Company Management)
- ❌ /users (User Management)
- ❌ /sessions (Session Management)
- ❌ /reports (Reports Center)
- ❌ /reports/all-transactions

**Test These:**
1. Login and verify logo shows in navbar
2. Select different company from dropdown
3. Dashboard updates to show selected company data only
4. Try accessing /reports → should be blocked
5. Verify cannot see other companies' data

---

## 🎨 UI/UX Checks (Both Accounts)

### Company Logos
- [ ] Dashboard: Company Performance logos (object-contain, not cropped)
- [ ] Dashboard: Top Debtors logos (object-contain, not cropped)
- [ ] Navbar: User sees company logo, Admin sees text
- [ ] PDFs: Company logos appear correctly

### Currency Formatting
- [ ] Dashboard: RM 1,234.56 format
- [ ] Debtors page: RM format
- [ ] Payments page: RM format
- [ ] Reports: RM format
- [ ] PDFs: RM format

### Scrollable Sections
- [ ] Company Performance: scrolls with max-h-96
- [ ] Recent Activity: scrolls with max-h-96

### Charts
- [ ] Outstanding vs Payments Made: shows historical data (not flat line)
- [ ] All other charts display correctly

---

## 📊 Reports Testing (Admin Only)

### Reports Center (/reports)
1. Select company
2. Generate each report:
   - [ ] Overview → Download PDF
   - [ ] All Debtors → Download PDF
   - [ ] Outstanding → Download PDF
   - [ ] Payment History → Apply date filters → Download PDF
3. Verify:
   - [ ] Only "Download PDF" button (NO "Print" button)
   - [ ] PDFs open correctly
   - [ ] Company logo in PDF
   - [ ] Data is accurate

---

## 🔒 Permission Testing

### Create Test User
1. Go to Users → Create User
2. Set:
   - Username: testuser
   - Email: test@example.com
   - Role: User
   - Permissions: View Debtors ONLY
   - Companies: Assign 1 company
3. Logout and login as testuser
4. Verify:
   - [ ] Can view debtors
   - [ ] Cannot create/edit/delete debtors
   - [ ] Cannot record payments
   - [ ] Cannot access admin features

---

## ⚡ Quick Smoke Test (5 minutes)

**As Admin:**
1. [ ] Login
2. [ ] Dashboard loads
3. [ ] Click "Reports" → goes to Reports Center
4. [ ] Generate Overview report → Download PDF
5. [ ] Go to Companies → Edit a company
6. [ ] Logout

**As User:**
1. [ ] Login
2. [ ] Dashboard loads
3. [ ] See company logo in navbar
4. [ ] Select different company
5. [ ] Try /reports → blocked (403)
6. [ ] Logout

---

## 🐛 If Something Breaks

### Clear Caches
```bash
cd /Users/iffahrosani/Desktop/Main_Code/money-management
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Check Logs
```bash
tail -50 storage/logs/laravel.log
```

### Restart Servers
```bash
# Stop servers (Ctrl+C on each terminal)
# Then restart:
php artisan serve
npm run dev
```

---

## 📋 Full Testing Guides

- **COMPLETE_USER_TESTING.md** - Comprehensive checklist (150+ items)
- **test_all_features.md** - All features checklist (120+ items)
- **QUICK_TEST_GUIDE.md** - 8-minute fast test
- **FINAL_CLEANUP_REPORT.md** - Cleanup details

---

## ✅ After Testing

If everything works:
```bash
cd /Users/iffahrosani/Desktop/Main_Code/money-management
rm -rf .cleanup_backup/
```

---

**Quick Link**: http://127.0.0.1:8000

**Start with**: COMPLETE_USER_TESTING.md

Good luck! 🚀
