# 🎯 Complete Supabase Setup - All Steps

**Status:** PostgreSQL driver ✅ installed

---

## ✅ Step 1-2: COMPLETED
- Supabase account created
- Project "money-management" created
- PostgreSQL driver installed (doctrine/dbal v4.4.1)

---

## 📋 Step 3: Get Database Credentials

**Go to:** https://supabase.com/dashboard

### Method 1: Individual Values
1. Click your "money-management" project
2. Click **Settings** (⚙️) → **Database**
3. Find "Connection Info" section
4. Copy these values:
   - **Host:** `db.xxxxxxxxxxxx.supabase.co`
   - **Port:** `5432`
   - **Database:** `postgres`
   - **User:** `postgres`
   - **Password:** [your password]

### Method 2: Connection String (Easier!)
1. In Database settings → "Connection string" section
2. Click **"URI"** tab
3. Copy the full string:
   ```
   postgresql://postgres:[PASSWORD]@db.xxxx.supabase.co:5432/postgres
   ```

---

## 🔧 Step 4: Update Laravel Configuration

I'll help you update your `.env` file once you have the credentials!

**What we'll change:**
```env
# FROM (SQLite):
DB_CONNECTION=sqlite

# TO (PostgreSQL/Supabase):
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxxxxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_password_here
DB_SSLMODE=require
```

---

## ✅ Step 5: Test Database Connection

After updating `.env`, we'll test the connection:

```bash
php artisan tinker
```

Then in tinker:
```php
DB::connection()->getPdo();
// Should show: PDO connection object
exit
```

---

## 🚀 Step 6: Run Migrations

Create all tables in Supabase:

```bash
php artisan migrate:fresh --seed
```

This will:
- ✅ Create all database tables
- ✅ Seed initial data (categories, test users)
- ✅ Your SQLite data won't be copied (fresh start)

---

## 🧪 Step 7: Test Locally

```bash
php artisan serve
```

Then visit: http://127.0.0.1:8000

**Test these:**
- ✅ Login works
- ✅ Dashboard loads
- ✅ Can create companies
- ✅ Can create debtors
- ✅ Can add payments

---

## 🎉 Step 8: (Optional) Export SQLite Data

**If you want to keep your existing SQLite data:**

### Option A: Manual Migration
1. Open your SQLite database
2. Export important data
3. Import into Supabase using SQL

### Option B: Fresh Start (Recommended)
Just use `migrate:fresh --seed` and start fresh!

For a small app, fresh start is easier and cleaner.

---

## ⏱️ Time Estimate

- Step 3: Get credentials → **2 minutes**
- Step 4: Update .env → **1 minute**
- Step 5: Test connection → **1 minute**
- Step 6: Run migrations → **2 minutes**
- Step 7: Test app → **3 minutes**

**Total: ~10 minutes** 🚀

---

## 🆘 Troubleshooting

### Error: "SQLSTATE[08006] could not connect"
- ✅ Check Host is correct (db.xxxx.supabase.co)
- ✅ Check Password is correct
- ✅ Make sure DB_SSLMODE=require is set

### Error: "Access denied for user"
- ✅ Reset password in Supabase dashboard
- ✅ Use the new password in .env
- ✅ Clear config cache: `php artisan config:clear`

### Error: "Base table or view not found"
- ✅ Run migrations: `php artisan migrate`

---

## 📞 Next Steps

**Once you have your credentials, tell me:**
- "got it" or "ready"
- OR share your Host (e.g., db.abc123.supabase.co)

**I'll then:**
1. ✅ Update your `.env` file
2. ✅ Test the connection
3. ✅ Run migrations
4. ✅ Verify everything works

**Let's continue when you're ready!** 🎯
