# Row Level Security (RLS) Implementation Guide

This document explains how to implement Supabase Row Level Security for the Money Management Laravel application.

## Overview

RLS adds database-level access control, ensuring users can only access data they're authorized to see—even if there's a bug in the application code.

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                      Laravel App                             │
│  ┌─────────────────┐    ┌──────────────────────────────┐   │
│  │  Auth System    │───►│  SetRlsContext Middleware     │   │
│  │  (user, company)│    │  Sets PostgreSQL session vars │   │
│  └─────────────────┘    └──────────────────────────────┘   │
│                                     │                        │
│                                     ▼                        │
│                         SET app.current_user_id = X          │
│                         SET app.current_company_id = Y       │
│                         SET app.is_admin = true/false        │
└─────────────────────────────────────────────────────────────┘
                                      │
                                      ▼
┌─────────────────────────────────────────────────────────────┐
│                      Supabase (PostgreSQL)                   │
│  ┌─────────────────────────────────────────────────────┐    │
│  │                   RLS Policies                       │    │
│  │  • current_app_user_id() → reads session var         │    │
│  │  • current_company_id() → reads session var          │    │
│  │  • is_app_admin() → reads session var                │    │
│  │                                                       │    │
│  │  Policy Example:                                      │    │
│  │  "Users can only SELECT debtors WHERE                 │    │
│  │   company_id = current_company_id()"                  │    │
│  └─────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
```

## Setup Instructions

### Step 1: Run the SQL Script in Supabase

1. Go to **Supabase Dashboard** → **SQL Editor**
2. Copy the contents of `database/supabase_rls_setup.sql`
3. Run the script

This will:
- Create helper functions (`current_app_user_id()`, `current_company_id()`, `is_app_admin()`)
- Enable RLS on all relevant tables
- Create policies for each table

### Step 2: Deploy Laravel Changes

The following files were added/modified:

- `app/Http/Middleware/SetRlsContext.php` - Sets PostgreSQL session variables
- `bootstrap/app.php` - Registers the middleware

Deploy these changes to your production environment.

### Step 3: Verify RLS is Working

Run this in Supabase SQL Editor to verify RLS is enabled:

```sql
SELECT 
  tablename,
  rowsecurity
FROM pg_tables 
WHERE schemaname = 'public' 
  AND tablename IN ('companies', 'company_user', 'debtors', 'payments');
```

All should show `rowsecurity = true`.

## How It Works

### On Each Web Request:

1. Laravel authenticates the user
2. `SetRlsContext` middleware runs
3. Middleware sets PostgreSQL session variables:
   - `app.current_user_id` = logged-in user's ID
   - `app.current_company_id` = selected company ID from session
   - `app.is_admin` = whether user is admin
4. Every subsequent database query is filtered by RLS policies

### Example: Querying Debtors

```php
// In Laravel controller
$debtors = Debtor::all();
```

Without RLS: Returns ALL debtors in the database.

With RLS: PostgreSQL automatically adds:
```sql
WHERE company_id = current_company_id()
```

So the user only sees debtors from their selected company.

## Tables Protected

| Table | Policy |
|-------|--------|
| `companies` | Users see only their assigned companies; admins see all |
| `company_user` | Users see their own assignments; admins see all |
| `debtors` | Users see debtors in their current company only |
| `payments` | Users see payments for debtors in their current company |
| `balance_adjustments` | Users see adjustments for debtors in their current company |

## Admin Access

Admins (users with `role = 'admin'`) bypass all RLS restrictions and can see/modify all data.

## Testing RLS

### Test 1: Verify User Isolation

1. Log in as User A (assigned to Company 1)
2. Create a debtor
3. Log out
4. Log in as User B (assigned to Company 2)
5. Verify User B cannot see User A's debtor

### Test 2: Direct SQL Test

In Supabase SQL Editor:

```sql
-- Simulate a regular user
SET app.current_user_id = '1';
SET app.current_company_id = '1';
SET app.is_admin = 'false';

-- This should only return debtors from company_id = 1
SELECT * FROM debtors;

-- Simulate admin
SET app.is_admin = 'true';

-- This should return ALL debtors
SELECT * FROM debtors;
```

## Troubleshooting

### "permission denied for table X"

RLS is working but no policy matches. Check:
- Is `current_company_id` set in session?
- Does the user have access to that company?

### Queries return empty when they shouldn't

The middleware might not be setting context. Add logging:

```php
// In SetRlsContext.php
\Log::info('RLS Context', [
    'user_id' => $user->id,
    'company_id' => $currentCompanyId,
    'is_admin' => $user->isAdmin(),
]);
```

### Need to bypass RLS temporarily

For migrations or admin scripts, use a direct connection without RLS middleware, or:

```sql
SET app.is_admin = 'true';
```

## Disabling RLS (Emergency)

If RLS causes issues, disable it in Supabase:

```sql
ALTER TABLE companies DISABLE ROW LEVEL SECURITY;
ALTER TABLE debtors DISABLE ROW LEVEL SECURITY;
-- etc.
```

## Security Considerations

1. **RLS is defense-in-depth** - Keep your Laravel authorization (policies, gates) as the primary security layer
2. **Service role bypasses RLS** - The connection string you use has elevated permissions; RLS is enforced via `FORCE ROW LEVEL SECURITY`
3. **Session variables can be set by anyone with DB access** - RLS here protects against bugs, not malicious database access
