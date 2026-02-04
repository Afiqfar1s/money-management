# Comprehensive Test Plan - Money Management System

**Generated:** February 4, 2026  
**Test Environment:** Local Development (http://localhost:8000)  
**Database:** Supabase (PostgreSQL)

---

## 📊 Testing Progress Summary

**Last Updated:** February 4, 2026  
**Current Phase:** Phase 3 - Debtor Management (IN PROGRESS)

### ✅ Completed:
- **Phase 1:** Authentication & Authorization - 7/7 tests PASSED
- **Phase 2:** Company Management - 6/6 tests PASSED
- **Phase 3:** Debtor Management - 8 tests PASSED (IN PROGRESS)

### 🔧 Recent Fixes Applied:
- Case-insensitive search (LIKE → ILIKE for PostgreSQL)
- Real-time search with 500ms debounce using Alpine.js
- Changed "Owing Only" to "Outstanding Only" filter label
- Made staff/company fields optional (required: type, name, description, outstanding only)

### 📝 Test Data:
- 20 debtors seeded (10 Microcorp, 10 MNHR)
- Mix of individuals and companies
- Various outstanding amounts (RM 0 - RM 55,000)

### ⏭️ Next Session:
- Complete Test 3.2.2: Create individual debtor
- Continue remaining Phase 3 tests
- Move to Phase 4: Payment & Balance Operations

---

## Test Accounts

| Role | Email | Password | Companies | Permissions |
|------|-------|----------|-----------|-------------|
| Admin | admin@example.com | admin123 | All (no selection required) | Full access |
| User | test@example.com | password | Microcorp, MNHR | All standard permissions |

---

## Phase 1: Authentication & Authorization Tests

### 1.1 Login/Logout
- [ ] **Test 1.1.1:** Login with admin account
  - Navigate to `/login`
  - Enter: admin@example.com / admin123
  - **Expected:** Redirect to admin dashboard
  - **Verify:** Can see admin navigation menu

- [ ] **Test 1.1.2:** Login with test user account
  - Navigate to `/login`
  - Enter: test@example.com / password
  - **Expected:** Redirect to debtors page, auto-select first company (Microcorp)
  - **Verify:** Company name shown in header

- [ ] **Test 1.1.3:** Login with invalid credentials
  - Try wrong email/password
  - **Expected:** Error message displayed
  - **Verify:** Stay on login page

- [ ] **Test 1.1.4:** Logout from admin
  - Click profile dropdown (top-right)
  - Click "Log Out" button
  - **Expected:** Redirect to login page
  - **Verify:** Cannot access `/debtors` without login

- [ ] **Test 1.1.5:** Logout from test user
  - Same as above
  - **Expected:** Session cleared, company selection reset

- [ ] **Test 1.1.6:** Quick logout URL
  - Visit `/logout-now` while logged in
  - **Expected:** Immediate logout and redirect to login

### 1.2 Permission System
- [ ] **Test 1.2.1:** Test user with all permissions
  - Login as test@example.com
  - **Verify:** Can view, create, edit, delete debtors
  - **Verify:** Can manage payments and adjustments
  - **Verify:** Can view reports

- [ ] **Test 1.2.2:** Admin bypasses permissions
  - Login as admin
  - **Verify:** Can access all features regardless of permissions

- [ ] **Test 1.2.3:** Access control enforcement
  - As test user, try to access `/users` (admin only)
  - **Expected:** 403 Forbidden or redirect

---

## Phase 2: Company Management Tests

### 2.1 Company Selection (Test User)
- [ ] **Test 2.1.1:** Auto-select first company on login
  - Login as test@example.com
  - **Expected:** "Microcorp" selected automatically
  - **Verify:** Company name in header/dropdown

- [ ] **Test 2.1.2:** Switch between companies
  - Click company dropdown in header
  - Select "MNHR"
  - **Expected:** Page refreshes, debtors filtered to MNHR
  - **Verify:** Company name updates in header

- [ ] **Test 2.1.3:** Switch back to Microcorp
  - Click company dropdown
  - Select "Microcorp"
  - **Expected:** Debtors filtered to Microcorp

- [ ] **Test 2.1.4:** Company persistence across pages
  - Select MNHR
  - Navigate to different pages
  - **Verify:** MNHR remains selected throughout session

### 2.2 Company CRUD (Admin Only)
- [ ] **Test 2.2.1:** View companies list
  - Login as admin
  - Navigate to `/companies`
  - **Expected:** See list of all companies (Microcorp, MNHR, etc.)
  - **Verify:** Shows company name, code, user count, debtor count

- [ ] **Test 2.2.2:** Create new company
  - Click "Create Company" button
  - Fill form:
    - Name: "Test Corp"
    - Code: "TESTCORP"
  - Click "Create"
  - **Expected:** Success message, redirected to companies list
  - **Verify:** New company appears in list

- [ ] **Test 2.2.3:** Edit company
  - Click "Edit" on Test Corp
  - Change name to "Test Corporation"
  - Click "Update"
  - **Expected:** Success message, changes saved
  - **Verify:** Updated name shows in list

- [ ] **Test 2.2.4:** Delete company without dependencies
  - Click "Delete" on Test Corp
  - Confirm deletion
  - **Expected:** Company deleted
  - **Verify:** Removed from list

- [ ] **Test 2.2.5:** Try to delete company with debtors
  - Try to delete Microcorp (has debtors)
  - **Expected:** Error message (cannot delete with debtors)

---

## Phase 3: Debtor Management Tests

### 3.1 Debtor Listing
- [ ] **Test 3.1.1:** View debtors list
  - Login as test user
  - Navigate to `/debtors`
  - **Expected:** See list of debtors for selected company
  - **Verify:** Shows name, type, outstanding, last payment date

- [ ] **Test 3.1.2:** Summary statistics
  - Check top of page
  - **Verify:** Total Outstanding, Total Paid, Total Debtors displayed

- [ ] **Test 3.1.3:** Pagination
  - If more than 20 debtors, check pagination
  - Click next/previous page
  - **Expected:** Navigate through pages smoothly

- [x] **Test 3.1.4:** Search by name ✅ PASS
  - Enter debtor name in search box (case-insensitive, real-time)
  - Search works with partial matches (e.g., "ahmad" finds "Ahmad bin Hassan")
  - **Expected:** Filtered results matching search term

- [ ] **Test 3.1.5:** Search by voucher number
  - Enter payment/adjustment voucher number
  - **Expected:** Find debtor with that voucher

- [x] **Test 3.1.6:** Filter by status (Outstanding) ✅ PASS
  - Select "Outstanding Only" from status filter
  - **Expected:** Only show debtors with outstanding > 0

- [x] **Test 3.1.7:** Filter by status (Settled) ✅ PASS
  - Select "Settled"
  - **Expected:** Only show debtors with outstanding = 0

- [x] **Test 3.1.8:** Clear filters ✅ PASS
  - Click "Clear Filters" button
  - **Expected:** Show all debtors again

### 3.2 Create Debtor
- [x] **Test 3.2.1:** Navigate to create page ✅ PASS
  - Click "Add Debtor" button
  - **Expected:** Form displayed with fields

- [ ] **Test 3.2.2:** Create individual debtor
  - Select Type: "Individual"
  - Fill required fields:
    - Name: "John Doe"
    - Starting Outstanding: "1000"
    - Description: "Test debtor"
  - Click "Create Debtor"
  - **Expected:** Success message, redirected to debtors list
  - **Verify:** New debtor appears with RM 1,000.00 outstanding

- [ ] **Test 3.2.3:** Create company debtor
  - Create new debtor
  - Select Type: "Company"
  - Fill:
    - Name: "ABC Sdn Bhd"
    - Starting Outstanding: "5000"
  - **Expected:** Created successfully

- [ ] **Test 3.2.4:** Create with optional voucher number
  - Create debtor with Voucher No: "INIT-001"
  - **Expected:** Voucher recorded in adjustments

- [ ] **Test 3.2.5:** Validation - required fields
  - Try to submit without name
  - **Expected:** Validation error displayed

- [ ] **Test 3.2.6:** Validation - negative amount
  - Try starting outstanding: "-100"
  - **Expected:** Validation error

- [ ] **Test 3.2.7:** Validation - duplicate voucher
  - Try to use same voucher number twice
  - **Expected:** Error: voucher already exists

### 3.3 View Debtor Details
- [ ] **Test 3.3.1:** Click on debtor name
  - From list, click a debtor's name
  - **Expected:** Navigate to debtor detail page

- [ ] **Test 3.3.2:** View debtor information
  - **Verify:** Shows name, type, description
  - **Verify:** Shows outstanding balance prominently

- [ ] **Test 3.3.3:** View payments history
  - **Verify:** List of all payments with date, amount, voucher
  - **Verify:** Shows chronologically (newest first or oldest first)

- [ ] **Test 3.3.4:** View balance adjustments
  - **Verify:** List of adjustments with type (debt/credit), amount, voucher

- [ ] **Test 3.3.5:** Quick actions present
  - **Verify:** "Record Payment" button visible
  - **Verify:** "Add Balance Adjustment" button visible
  - **Verify:** "Edit" button visible
  - **Verify:** "Delete" button visible

### 3.4 Edit Debtor
- [ ] **Test 3.4.1:** Navigate to edit page
  - From debtor detail or list, click "Edit"
  - **Expected:** Edit form with current data pre-filled

- [ ] **Test 3.4.2:** Update debtor name
  - Change name to "John Doe Updated"
  - Click "Update"
  - **Expected:** Success message, name updated

- [ ] **Test 3.4.3:** Update description
  - Change description text
  - **Expected:** Saved successfully

- [ ] **Test 3.4.4:** Update debtor type
  - Change from Individual to Company (or vice versa)
  - **Expected:** Type updated

- [ ] **Test 3.4.5:** Cannot edit outstanding directly
  - **Verify:** Outstanding field is read-only or not editable
  - **Note:** Outstanding updated only via payments/adjustments

- [ ] **Test 3.4.6:** Cancel edit
  - Click "Cancel" button
  - **Expected:** Return without saving changes

### 3.5 Delete Debtor
- [ ] **Test 3.5.1:** Delete debtor without transactions
  - Create a new test debtor
  - Click "Delete" button
  - Confirm deletion
  - **Expected:** Debtor deleted successfully

- [ ] **Test 3.5.2:** Delete debtor with transactions
  - Try to delete debtor with payments
  - **Expected:** Should either prevent deletion or cascade delete
  - **Verify:** Check behavior matches business rules

- [ ] **Test 3.5.3:** Cancel deletion
  - Click "Delete", then "Cancel" on confirmation
  - **Expected:** Debtor not deleted

### 3.6 Refresh Balance
- [ ] **Test 3.6.1:** Refresh single debtor balance
  - On debtor detail page, click "Refresh Balance"
  - **Expected:** Balance recalculated from payments/adjustments

- [ ] **Test 3.6.2:** Refresh all balances
  - On debtors list page, click "Refresh All Balances"
  - **Expected:** All debtor balances recalculated
  - **Verify:** Success message with count

---

## Phase 4: Payment & Balance Operations Tests

### 4.1 Record Payment
- [ ] **Test 4.1.1:** Open payment form
  - On debtor detail page, click "Record Payment"
  - **Expected:** Modal or form appears

- [ ] **Test 4.1.2:** Record simple payment
  - Fill:
    - Amount: "500"
    - Payment Date: Today
    - Voucher No: "PAY-001"
  - Click "Record Payment"
  - **Expected:** Payment recorded, outstanding reduced by RM 500
  - **Verify:** Payment appears in history

- [ ] **Test 4.1.3:** Record payment with notes
  - Add payment with notes/description
  - **Expected:** Notes saved and displayed

- [ ] **Test 4.1.4:** Validation - amount required
  - Try to submit without amount
  - **Expected:** Validation error

- [ ] **Test 4.1.5:** Validation - positive amount
  - Try negative amount: "-100"
  - **Expected:** Validation error

- [ ] **Test 4.1.6:** Validation - amount exceeds outstanding
  - Current outstanding: RM 500
  - Try to pay: RM 600
  - **Expected:** Warning or error (overpayment)

- [ ] **Test 4.1.7:** Multiple payments
  - Record 3 separate payments
  - **Expected:** All payments recorded
  - **Verify:** Outstanding updated correctly after each

- [ ] **Test 4.1.8:** Payment fully settles debt
  - Pay exact remaining outstanding
  - **Expected:** Outstanding becomes RM 0.00
  - **Verify:** Status changes to "Settled"

### 4.2 Balance Adjustments
- [ ] **Test 4.2.1:** Open adjustment form
  - Click "Add Balance Adjustment"
  - **Expected:** Form with type selection (Debit/Credit)

- [ ] **Test 4.2.2:** Add debit adjustment (increase debt)
  - Select Type: "Debit" or "Add Debt"
  - Amount: "200"
  - Reason: "Additional charge"
  - Voucher: "ADJ-001"
  - **Expected:** Outstanding increases by RM 200

- [ ] **Test 4.2.3:** Add credit adjustment (reduce debt)
  - Select Type: "Credit" or "Discount"
  - Amount: "100"
  - Reason: "Discount given"
  - **Expected:** Outstanding decreases by RM 100

- [ ] **Test 4.2.4:** Adjustment with voucher
  - Add adjustment with voucher number
  - **Expected:** Voucher recorded and searchable

- [ ] **Test 4.2.5:** Validation - type required
  - Try to submit without selecting type
  - **Expected:** Validation error

- [ ] **Test 4.2.6:** Validation - amount required
  - Submit without amount
  - **Expected:** Validation error

### 4.3 Payment Voucher
- [ ] **Test 4.3.1:** View payment voucher
  - From payment history, click "View Voucher" or voucher number
  - **Expected:** Navigate to voucher page

- [ ] **Test 4.3.2:** Voucher content
  - **Verify:** Shows debtor name
  - **Verify:** Shows payment amount, date
  - **Verify:** Shows voucher number
  - **Verify:** Shows company information

- [ ] **Test 4.3.3:** Print voucher
  - Click "Print" button
  - **Expected:** Browser print dialog opens
  - **Verify:** Print preview looks professional

- [ ] **Test 4.3.4:** Download voucher as PDF (if available)
  - Click download button
  - **Expected:** PDF file downloaded

---

## Phase 5: Reports & PDFs Tests

### 5.1 Debtor Payment History Report
- [ ] **Test 5.1.1:** Access from debtor detail
  - On debtor page, click "View Payment History" or "Report"
  - **Expected:** Navigate to payment history page

- [ ] **Test 5.1.2:** Payment history content
  - **Verify:** Shows all payments chronologically
  - **Verify:** Shows all balance adjustments
  - **Verify:** Shows running balance after each transaction

- [ ] **Test 5.1.3:** Filter by date range
  - Select date from/to
  - **Expected:** Only transactions in range shown

- [ ] **Test 5.1.4:** Export/print payment history
  - Click print/export button
  - **Expected:** PDF or printable version generated

### 5.2 Admin Reports (Admin Only)
- [ ] **Test 5.2.1:** Access reports hub
  - Login as admin
  - Navigate to `/reports`
  - **Expected:** Reports dashboard with options

- [ ] **Test 5.2.2:** Overview Report
  - **Verify:** Shows system-wide statistics
  - **Verify:** Total outstanding, total payments, etc.

- [ ] **Test 5.2.3:** Download Overview PDF
  - Click "Download Overview Report"
  - **Expected:** PDF file downloaded
  - **Verify:** PDF contains correct data

- [ ] **Test 5.2.4:** Debtors Report
  - View/download debtors report
  - **Expected:** List of all debtors across all companies
  - **Verify:** PDF format

- [ ] **Test 5.2.5:** Outstanding Report
  - Download outstanding report
  - **Expected:** Shows all debtors with outstanding > 0
  - **Verify:** Sorted by amount

- [ ] **Test 5.2.6:** Payments Report
  - Download payments report
  - **Expected:** All payments within selected period
  - **Verify:** Can filter by date range

- [ ] **Test 5.2.7:** All Transactions Report
  - Navigate to `/reports/all-transactions`
  - **Expected:** Comprehensive view of all payments and adjustments

- [ ] **Test 5.2.8:** Filter transactions by company
  - Select specific company from dropdown
  - **Expected:** Transactions filtered

- [ ] **Test 5.2.9:** Filter transactions by date
  - Set date range
  - **Expected:** Transactions within range shown

- [ ] **Test 5.2.10:** Download all transactions
  - Click download button
  - **Expected:** Excel or PDF downloaded

---

## Phase 6: Admin Features Tests

### 6.1 Admin Dashboard
- [ ] **Test 6.1.1:** Access admin dashboard
  - Login as admin
  - Navigate to `/dashboard` or `/`
  - **Expected:** Admin dashboard displayed

- [ ] **Test 6.1.2:** Dashboard statistics
  - **Verify:** Total users count
  - **Verify:** Total admins count
  - **Verify:** Total companies count
  - **Verify:** Total debtors count
  - **Verify:** Total outstanding amount
  - **Verify:** Active sessions count

- [ ] **Test 6.1.3:** Company performance overview
  - **Verify:** Shows each company with stats
  - **Verify:** Debtor count per company
  - **Verify:** Outstanding per company

- [ ] **Test 6.1.4:** Recent activity
  - **Verify:** Shows recent payments
  - **Verify:** Shows recent adjustments
  - **Verify:** Shows recent user activity

- [ ] **Test 6.1.5:** Charts/graphs (if present)
  - **Verify:** Charts render correctly
  - **Verify:** Data accurate

### 6.2 User Management
- [ ] **Test 6.2.1:** View users list
  - Navigate to `/users`
  - **Expected:** List of all users
  - **Verify:** Shows name, email, role, company access

- [ ] **Test 6.2.2:** Create new user
  - Click "Create User"
  - Fill:
    - Name: "New User"
    - Email: "newuser@example.com"
    - Password: "password123"
    - Role: "user"
  - Select companies to assign
  - Select permissions
  - Click "Create"
  - **Expected:** User created successfully

- [ ] **Test 6.2.3:** Edit user
  - Click "Edit" on a user
  - **Expected:** Edit form with tabs/sections:
    - Basic Info (name, email)
    - Role & Permissions
    - Company Access
    - Password

- [ ] **Test 6.2.4:** Update basic info
  - Change name or email
  - Click "Update Basic Info"
  - **Expected:** Information updated

- [ ] **Test 6.2.5:** Update role and permissions
  - Change role or toggle permissions
  - Click "Update Role & Permissions"
  - **Expected:** Changes saved

- [ ] **Test 6.2.6:** Update company access
  - Add or remove company assignments
  - Click "Update Companies"
  - **Expected:** User's company access updated

- [ ] **Test 6.2.7:** Change user password
  - Enter new password and confirmation
  - Click "Update Password"
  - **Expected:** Password changed
  - **Verify:** User can login with new password

- [ ] **Test 6.2.8:** Delete user
  - Click "Delete" on a user
  - Confirm deletion
  - **Expected:** User deleted (or soft deleted)

- [ ] **Test 6.2.9:** Cannot delete self
  - Try to delete currently logged-in admin
  - **Expected:** Error or button disabled

- [ ] **Test 6.2.10:** Validation - unique email
  - Try to create user with existing email
  - **Expected:** Validation error

### 6.3 Session Management
- [ ] **Test 6.3.1:** View active sessions
  - Navigate to `/sessions`
  - **Expected:** List of active sessions

- [ ] **Test 6.3.2:** Session information
  - **Verify:** Shows user name
  - **Verify:** Shows IP address
  - **Verify:** Shows last activity time
  - **Verify:** Shows user agent (browser)

- [ ] **Test 6.3.3:** Kill other user's session
  - Click "Terminate" on another user's session
  - **Expected:** Session ended, user logged out

- [ ] **Test 6.3.4:** Cannot kill own session
  - Try to terminate current admin session
  - **Expected:** Disabled or shows warning

---

## Phase 7: Profile & Settings Tests

### 7.1 Profile Management
- [ ] **Test 7.1.1:** Access profile page
  - Click profile dropdown → "Profile" or "Settings"
  - **Expected:** Navigate to `/profile`

- [ ] **Test 7.1.2:** View current information
  - **Verify:** Shows current name
  - **Verify:** Shows current email

- [ ] **Test 7.1.3:** Update profile name
  - Change name
  - Click "Save" or "Update"
  - **Expected:** Name updated
  - **Verify:** New name shows in header

- [ ] **Test 7.1.4:** Update profile email
  - Change email
  - Click "Update"
  - **Expected:** Email updated (may require verification)

- [ ] **Test 7.1.5:** Validation - email format
  - Try invalid email format
  - **Expected:** Validation error

### 7.2 Password Change
- [ ] **Test 7.2.1:** Change password form
  - **Verify:** Form has fields:
    - Current password
    - New password
    - Confirm new password

- [ ] **Test 7.2.2:** Change password successfully
  - Enter correct current password
  - Enter new password (twice)
  - Click "Update Password"
  - **Expected:** Password changed
  - **Verify:** Logout and login with new password works

- [ ] **Test 7.2.3:** Validation - current password incorrect
  - Enter wrong current password
  - **Expected:** Error message

- [ ] **Test 7.2.4:** Validation - passwords don't match
  - New password ≠ confirm password
  - **Expected:** Validation error

- [ ] **Test 7.2.5:** Validation - password too short
  - Try password < 8 characters
  - **Expected:** Validation error

### 7.3 Account Deletion
- [ ] **Test 7.3.1:** Delete account option
  - **Verify:** "Delete Account" section exists

- [ ] **Test 7.3.2:** Delete account with confirmation
  - Click "Delete Account"
  - Confirm with password
  - **Expected:** Account deleted, logged out

- [ ] **Test 7.3.3:** Cancel account deletion
  - Click "Delete Account", then cancel
  - **Expected:** Account not deleted

---

## Phase 8: UI & Navigation Tests

### 8.1 Navigation
- [ ] **Test 8.1.1:** Header navigation - logged in
  - **Verify:** Logo present and clickable (goes to dashboard)
  - **Verify:** Company selector visible (for non-admins)
  - **Verify:** Main navigation links work:
    - Dashboard/Home
    - Debtors
    - Reports (admin)
    - Users (admin)
    - Companies (admin)
    - Sessions (admin)

- [ ] **Test 8.1.2:** Mobile navigation
  - Resize browser to mobile width (< 768px)
  - **Verify:** Hamburger menu appears
  - Click hamburger
  - **Expected:** Mobile menu opens
  - **Verify:** All links accessible

- [ ] **Test 8.1.3:** Profile dropdown
  - Click profile icon/name
  - **Verify:** Dropdown shows:
    - Profile/Settings
    - Dark Mode toggle
    - Log Out

- [ ] **Test 8.1.4:** Breadcrumbs (if present)
  - Navigate to nested pages
  - **Verify:** Breadcrumbs show current location
  - **Verify:** Breadcrumb links work

### 8.2 Dark Mode
- [ ] **Test 8.2.1:** Toggle dark mode
  - Click "Dark Mode" from profile dropdown
  - **Expected:** Interface switches to dark theme

- [ ] **Test 8.2.2:** Dark mode persistence
  - Enable dark mode
  - Navigate to different pages
  - **Verify:** Dark mode remains enabled

- [ ] **Test 8.2.3:** Dark mode after logout/login
  - Enable dark mode, logout, login again
  - **Verify:** Dark mode preference remembered

- [ ] **Test 8.2.4:** Toggle back to light mode
  - Click "Light Mode"
  - **Expected:** Switches back to light theme

### 8.3 Buttons and Forms
- [ ] **Test 8.3.1:** Primary action buttons
  - **Verify:** All "Create", "Save", "Submit" buttons work
  - **Verify:** Proper styling (color, hover effects)

- [ ] **Test 8.3.2:** Secondary action buttons
  - **Verify:** "Cancel", "Back" buttons work
  - **Verify:** Return to previous page without saving

- [ ] **Test 8.3.3:** Danger buttons
  - **Verify:** "Delete", "Remove" buttons styled differently
  - **Verify:** Confirmation dialogs appear

- [ ] **Test 8.3.4:** Disabled buttons
  - **Verify:** Disabled buttons are visually distinct
  - **Verify:** Cannot click disabled buttons

- [ ] **Test 8.3.5:** Form inputs
  - **Verify:** All text inputs accept text
  - **Verify:** Number inputs only accept numbers
  - **Verify:** Date inputs show date picker
  - **Verify:** Select dropdowns open and work

- [ ] **Test 8.3.6:** Form validation messages
  - Submit invalid form
  - **Verify:** Error messages appear near relevant fields
  - **Verify:** Messages are clear and helpful

### 8.4 Tables and Lists
- [ ] **Test 8.4.1:** Sortable columns
  - Click column headers
  - **Verify:** Data sorts ascending/descending

- [ ] **Test 8.4.2:** Row actions
  - **Verify:** Edit, Delete, View icons/buttons work
  - **Verify:** Hover effects present

- [ ] **Test 8.4.3:** Empty states
  - View page with no data
  - **Verify:** Friendly "No data" message shown
  - **Verify:** Suggests action to take (e.g., "Create your first debtor")

- [ ] **Test 8.4.4:** Loading states
  - **Verify:** Loading indicators appear during data fetch
  - **Verify:** Spinners or skeleton screens shown

### 8.5 Responsive Design
- [ ] **Test 8.5.1:** Desktop view (1920x1080)
  - **Verify:** Layout uses full width appropriately
  - **Verify:** No horizontal scrolling

- [ ] **Test 8.5.2:** Laptop view (1366x768)
  - **Verify:** Layout adapts
  - **Verify:** All content accessible

- [ ] **Test 8.5.3:** Tablet view (768x1024)
  - **Verify:** Navigation collapses to mobile menu
  - **Verify:** Tables scroll horizontally or stack

- [ ] **Test 8.5.4:** Mobile view (375x667)
  - **Verify:** All features accessible
  - **Verify:** Forms are usable
  - **Verify:** Buttons are touch-friendly

### 8.6 Notifications and Alerts
- [ ] **Test 8.6.1:** Success messages
  - Perform successful action (e.g., create debtor)
  - **Verify:** Green success message appears
  - **Verify:** Message auto-dismisses or has close button

- [ ] **Test 8.6.2:** Error messages
  - Perform action that causes error
  - **Verify:** Red error message appears
  - **Verify:** Message is descriptive

- [ ] **Test 8.6.3:** Warning messages
  - **Verify:** Warning messages styled distinctly (yellow/orange)

- [ ] **Test 8.6.4:** Info messages
  - **Verify:** Info messages styled appropriately (blue)

### 8.7 Links and Redirects
- [ ] **Test 8.7.1:** All internal links work
  - Click through all navigation links
  - **Verify:** No broken links (404 errors)

- [ ] **Test 8.7.2:** External links (if any)
  - **Verify:** Open in new tab (target="_blank")

- [ ] **Test 8.7.3:** Redirect after login
  - Login → redirected to appropriate dashboard
  - **Verify:** Admin → admin dashboard
  - **Verify:** User → debtors page

- [ ] **Test 8.7.4:** Redirect after logout
  - Logout → redirected to login page

- [ ] **Test 8.7.5:** Protected route access
  - Try to access `/debtors` without login
  - **Expected:** Redirect to login page

---

## Phase 9: Data Integrity & Edge Cases

### 9.1 Data Validation
- [ ] **Test 9.1.1:** SQL injection prevention
  - Try to enter SQL code in text fields
  - **Expected:** Treated as plain text, not executed

- [ ] **Test 9.1.2:** XSS prevention
  - Try to enter `<script>alert('XSS')</script>` in text fields
  - **Expected:** Sanitized or escaped

- [ ] **Test 9.1.3:** Large numbers
  - Enter very large outstanding amount (e.g., 999999999.99)
  - **Expected:** Handled correctly or validation limit

- [ ] **Test 9.1.4:** Special characters
  - Enter name with special chars: "O'Brien & Co."
  - **Expected:** Saved and displayed correctly

- [ ] **Test 9.1.5:** Unicode characters
  - Enter name in different language (Chinese, Arabic, etc.)
  - **Expected:** Saved and displayed correctly

### 9.2 Concurrent User Scenarios
- [ ] **Test 9.2.1:** Two users editing same debtor
  - User A and User B open same debtor edit page
  - User A saves changes
  - User B saves changes
  - **Verify:** Last write wins or conflict detected

- [ ] **Test 9.2.2:** Two users making payments simultaneously
  - Both record payment on same debtor
  - **Expected:** Both payments recorded
  - **Verify:** Outstanding calculated correctly

### 9.3 Performance
- [ ] **Test 9.3.1:** Large dataset
  - Create 100+ debtors
  - **Verify:** List page loads in reasonable time (< 2s)
  - **Verify:** Pagination works

- [ ] **Test 9.3.2:** Many payments on single debtor
  - Add 50+ payments to one debtor
  - **Verify:** Detail page loads
  - **Verify:** History displays (possibly paginated)

---

## Phase 10: Browser Compatibility

### 10.1 Chrome
- [ ] Test all core features in Chrome
- [ ] Verify all styling correct

### 10.2 Firefox
- [ ] Test all core features in Firefox
- [ ] Verify all styling correct

### 10.3 Edge
- [ ] Test all core features in Edge
- [ ] Verify all styling correct

### 10.4 Safari (if available)
- [ ] Test all core features in Safari
- [ ] Verify all styling correct

---

## Test Summary Template

After each phase, fill out:

**Phase X: [Name]**  
**Tested by:** [Your Name]  
**Date:** [Date]  
**Status:** ✅ Passed / ⚠️ Passed with Issues / ❌ Failed

**Issues Found:**
1. [Issue description] - Priority: High/Medium/Low
2. [Issue description] - Priority: High/Medium/Low

**Notes:**
- [Any additional observations]

---

## Critical Issues Log

Track any critical bugs found:

| ID | Description | Priority | Status | Phase | Notes |
|----|-------------|----------|--------|-------|-------|
| 1  |             | High     | Open   | 3.2   |       |

---

## Sign-off

**Tester:** ___________________  
**Date:** ___________________  
**Overall Status:** ✅ Ready for Production / ⚠️ Needs Minor Fixes / ❌ Needs Major Fixes

