# 👥 Complete User Account Testing Checklist

## Test Date: ____________
## Tested By: ____________

---

## 🔐 ADMIN ACCOUNT TESTING

### Login & Access
- [ ] Login as admin account
- [ ] Dashboard loads successfully
- [ ] Navigation shows "Money Management" text (not company logo)
- [ ] Admin dropdown menu visible

### Company Management
- [ ] Access "Companies" from navbar
- [ ] View companies list
- [ ] Create new company
  - [ ] Upload company logo
  - [ ] Fill in company details
  - [ ] Save successfully
- [ ] Edit existing company
  - [ ] Change company logo
  - [ ] Update details
  - [ ] Logo displays with object-contain (not cropped)
- [ ] Delete company (test with empty company)

### Dashboard Features (Admin View)
- [ ] Select company from dropdown
- [ ] Company Performance section loads
  - [ ] Company logos display correctly (object-contain)
  - [ ] Section is scrollable (max-h-96)
  - [ ] RM currency format: RM 1,234.56
- [ ] Recent Activity section
  - [ ] Shows activities
  - [ ] Section is scrollable (max-h-96)
- [ ] Top Debtors section
  - [ ] Company logos display correctly (object-contain)
  - [ ] RM currency format correct
- [ ] Charts display
  - [ ] Outstanding vs Payments Made (shows historical data)
  - [ ] Monthly Payment Trends
  - [ ] Debtors by Outstanding Range
  - [ ] Payment Methods Distribution

### Quick Actions (Admin)
- [ ] Click "Add Debtor" button
  - [ ] Modal or page opens
  - [ ] Can create debtor
- [ ] Click "Record Payment" button
  - [ ] Modal or page opens
  - [ ] Can record payment
- [ ] Click "Reports" button
  - [ ] Redirects to Reports Center (/reports)
  - [ ] NOT to company selection

### Reports Center (Admin Only)
- [ ] Access from navbar "Reports" dropdown
- [ ] Access from dashboard "Reports" quick action
- [ ] Select company (independent from dashboard)
- [ ] Switch between report types:
  - [ ] Overview Report
    - [ ] View on screen
    - [ ] Download PDF button visible (green)
    - [ ] NO Print button visible
    - [ ] Click Download PDF
    - [ ] PDF downloads correctly
    - [ ] PDF shows company logo
    - [ ] PDF shows summary statistics
    - [ ] PDF shows recent payments
    - [ ] PDF has signature section
    - [ ] RM formatting correct in PDF
  - [ ] All Debtors Report
    - [ ] View debtors list
    - [ ] Download PDF
    - [ ] PDF shows all debtors with totals
  - [ ] Outstanding Debts Report
    - [ ] View debtors with outstanding > 0
    - [ ] Download PDF
    - [ ] PDF highlights outstanding amounts (red theme)
  - [ ] Payment History Report
    - [ ] Date filters visible
    - [ ] Apply date filters
    - [ ] View filtered payments
    - [ ] Download PDF with filters
    - [ ] PDF shows date range

### All Transactions Report (Admin Only)
- [ ] Access from navbar "Reports" → "All Transactions"
- [ ] View transactions table
- [ ] Apply filters (debtor name, date range)
- [ ] Download PDF button works
- [ ] PDF generates with filters applied

### Debtor Management (Admin)
- [ ] View debtors list
- [ ] Search/filter debtors
- [ ] Click on debtor name → view details
- [ ] Add new debtor
- [ ] Edit debtor
- [ ] Delete debtor
- [ ] Refresh debtor balance
- [ ] Record payment from debtor page
- [ ] Add balance adjustment

### Payment Management (Admin)
- [ ] View all payments
- [ ] Filter payments
- [ ] Edit payment
- [ ] Delete payment
- [ ] Download payment voucher PDF

### User Management (Admin Only)
- [ ] Access "Users" from navbar
- [ ] View users list
- [ ] Create new user
  - [ ] Set username
  - [ ] Set email
  - [ ] Set password
  - [ ] Assign role (Admin/User)
  - [ ] Set permissions
  - [ ] Assign companies
- [ ] Edit user
  - [ ] Update basic info
  - [ ] Change role/permissions
  - [ ] Update company assignments
- [ ] Delete user

### Session Management (Admin Only)
- [ ] Access "Sessions" from navbar
- [ ] View active sessions list
- [ ] Terminate a session
- [ ] Verify session data displays

---

## 👤 REGULAR USER ACCOUNT TESTING

### Login & Access
- [ ] Login as regular user account
- [ ] Dashboard loads successfully
- [ ] Navigation shows COMPANY LOGO (not "Money Management" text)
- [ ] Company logo displays correctly (object-contain, not cropped)
- [ ] NO admin menu items visible

### Access Restrictions (Should NOT be accessible)
- [ ] Cannot access /companies (Company Management)
- [ ] Cannot access /users (User Management)
- [ ] Cannot access /sessions (Session Management)
- [ ] Cannot access /reports (Reports Center)
- [ ] Cannot access /reports/all-transactions

### Company Selection (User)
- [ ] Company dropdown shows assigned companies only
- [ ] Select different company
- [ ] Company context switches correctly
- [ ] Logo updates in navbar

### Dashboard Features (User View)
- [ ] Company Performance section loads
  - [ ] Shows only assigned companies
  - [ ] Company logos display correctly
  - [ ] Section is scrollable
  - [ ] RM currency format: RM 1,234.56
- [ ] Recent Activity section
  - [ ] Shows user's activities only
  - [ ] Section is scrollable
- [ ] Top Debtors section
  - [ ] Shows for selected company
  - [ ] Company logos display correctly
  - [ ] RM currency format correct
- [ ] Charts display
  - [ ] Data filtered by selected company
  - [ ] Outstanding vs Payments Made works
  - [ ] All charts show correct data

### Quick Actions (User)
- [ ] Click "Add Debtor" button works
- [ ] Click "Record Payment" button works
- [ ] Click "Reports" button (should show error or redirect)

### Debtor Management (User)
- [ ] View debtors for selected company only
- [ ] Cannot see other companies' debtors
- [ ] Add new debtor (if has permission)
- [ ] Edit debtor (if has permission)
- [ ] Delete debtor (if has permission)
- [ ] Record payment (if has permission)

### Payment Management (User)
- [ ] View payments for selected company only
- [ ] Cannot see other companies' payments
- [ ] Record payment (if has permission)
- [ ] Edit payment (if has permission)
- [ ] Delete payment (if has permission)
- [ ] Download voucher (if has permission)

### Profile Management (User)
- [ ] Access profile from user menu
- [ ] Update profile information
- [ ] Change password
- [ ] Delete account (if allowed)

---

## 🎨 UI/UX CONSISTENCY TESTING

### Logo Display (All Pages)
- [ ] Dashboard: Company logos use object-contain
- [ ] Debtors list: Logos not cropped
- [ ] Payments list: Logos correct
- [ ] Reports: Logos in PDFs correct
- [ ] Navbar: Logo displays correctly for users
- [ ] All logos have proper aspect ratio

### Currency Formatting (All Pages)
- [ ] Dashboard: RM 1,234.56 format
- [ ] Debtors page: RM format correct
- [ ] Payments page: RM format correct
- [ ] Reports: RM format correct
- [ ] PDFs: RM format correct
- [ ] Vouchers: RM format correct

### Dark Mode (All Pages)
- [ ] Toggle dark mode
- [ ] Dashboard displays correctly
- [ ] All pages render properly in dark mode
- [ ] Charts visible in dark mode
- [ ] Forms readable in dark mode
- [ ] Modals styled correctly

### Scrollable Sections
- [ ] Company Performance scrolls with max-h-96
- [ ] Recent Activity scrolls with max-h-96
- [ ] Long tables have horizontal scroll
- [ ] No layout breaking on small screens

---

## 🔒 PERMISSION TESTING

### Test User with Limited Permissions
Create a test user with:
- ✅ View debtors only
- ❌ Cannot create/edit/delete debtors
- ❌ Cannot record payments
- ❌ Cannot export data

Verify:
- [ ] Can view debtors list
- [ ] Cannot see "Add Debtor" button
- [ ] Cannot edit debtor details
- [ ] Cannot delete debtors
- [ ] Cannot record payments
- [ ] Cannot download reports/PDFs

---

## 📊 DATA ACCURACY TESTING

### Calculations
- [ ] Outstanding balance = Correct sum
- [ ] Total paid = Correct sum of payments
- [ ] Chart data matches dashboard stats
- [ ] Reports show accurate totals
- [ ] PDFs match screen data

### Historical Data
- [ ] Outstanding vs Paid chart shows historical trends
- [ ] Not a flat line
- [ ] Data points make sense chronologically
- [ ] Monthly trends chart accurate

---

## 🐛 BUG TESTING

### Error Handling
- [ ] Try accessing restricted pages (should get 403)
- [ ] Try invalid form submissions (should show errors)
- [ ] Try deleting debtor with payments (should prevent or warn)
- [ ] Try uploading invalid logo file (should reject)

### Edge Cases
- [ ] Debtor with zero outstanding
- [ ] Company with no debtors
- [ ] Empty payment history
- [ ] Very long debtor names
- [ ] Special characters in names
- [ ] Large amounts (RM 1,000,000+)

---

## ✅ FINAL VERIFICATION

### Performance
- [ ] Pages load in < 2 seconds
- [ ] PDFs generate in < 5 seconds
- [ ] No console errors
- [ ] No PHP errors in logs

### Browser Compatibility
- [ ] Chrome/Edge (tested)
- [ ] Firefox (tested)
- [ ] Safari (tested)
- [ ] Mobile responsive (tested)

---

## 📝 ISSUES FOUND

| # | Issue Description | Severity | Status | Notes |
|---|-------------------|----------|--------|-------|
| 1 |                   |          |        |       |
| 2 |                   |          |        |       |
| 3 |                   |          |        |       |

---

## ✅ TESTING SIGN-OFF

**Admin Account Testing**: ☐ Pass ☐ Fail  
**User Account Testing**: ☐ Pass ☐ Fail  
**UI/UX Consistency**: ☐ Pass ☐ Fail  
**Permission System**: ☐ Pass ☐ Fail  
**Data Accuracy**: ☐ Pass ☐ Fail  
**Overall Status**: ☐ APPROVED ☐ NEEDS FIXES

**Tester Signature**: __________________  
**Date**: __________________  
**Notes**:
