# 🚀 Quick Test Guide

## ⚡ Fast Testing Commands

### Start/Check Servers
```bash
# Check if servers are running
./smoke_test.sh

# Start Laravel server (if not running)
php artisan serve

# Start Vite dev server (if not running)
npm run dev
```

### Quick Verification
```bash
# List all routes
php artisan route:list

# Clear all caches
php artisan optimize:clear

# Re-cache for performance
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

## 🎯 Critical Features to Test

### 1. Dashboard (2 minutes)
- [ ] Login as admin
- [ ] Dashboard loads
- [ ] Click "Reports" quick action → should go to Reports Center
- [ ] Company Performance section is scrollable
- [ ] Recent Activity section is scrollable
- [ ] Charts display

### 2. Reports Center (3 minutes)
- [ ] Click "Reports" in navbar
- [ ] Select a company
- [ ] Choose "Overview" → Click "Download PDF"
- [ ] Choose "All Debtors" → Click "Download PDF"
- [ ] Choose "Outstanding" → Click "Download PDF"
- [ ] Choose "Payment History" → Click "Download PDF"
- [ ] Verify PDFs download correctly

### 3. Company Logos (1 minute)
- [ ] Dashboard: Company Performance logos look good (not cropped)
- [ ] Dashboard: Top Debtors logos look good (not cropped)
- [ ] Navbar: Admin sees "Money Management" text (not company logo)
- [ ] Navbar: Regular user sees company logo

### 4. Basic CRUD (2 minutes)
- [ ] Add a new debtor
- [ ] Record a payment
- [ ] View debtor details
- [ ] Edit debtor

---

## 🐛 Common Issues & Fixes

### Issue: Page not loading
```bash
php artisan optimize:clear
php artisan config:cache
```

### Issue: Routes not found
```bash
php artisan route:clear
php artisan route:cache
```

### Issue: Views not updating
```bash
php artisan view:clear
php artisan view:cache
```

### Issue: PDF not generating
Check if DomPDF is installed:
```bash
composer require barryvdh/laravel-dompdf
```

---

## ✅ Expected Results

### Dashboard
- ✅ RM currency shows as: RM 1,234.56 (2 decimals)
- ✅ Company logos are fully visible (not cropped)
- ✅ Sections are scrollable with `max-h-96`
- ✅ Charts display historical data correctly

### Reports Center
- ✅ 4 report types available
- ✅ Company selection independent from dashboard
- ✅ PDF downloads work for all report types
- ✅ PDF shows company logo and details
- ✅ No "Print" button (only "Download PDF")

### Navigation
- ✅ Admin: sees "Money Management" + icon
- ✅ User: sees company logo
- ✅ Dark mode toggle works

---

## 📊 Performance Benchmarks

Expected page load times:
- Dashboard: < 1 second
- Reports Center: < 1 second
- PDF Generation: < 3 seconds
- Debtor list: < 1 second

---

## 🎉 All Good? Mark as Complete!

Date Tested: __________
Tested By: __________
Status: ☐ All Working ☐ Issues Found

Notes:
