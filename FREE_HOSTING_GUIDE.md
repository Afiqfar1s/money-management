# 🆓 Free Hosting Setup Guide - Railway + Supabase

**Perfect for:** Small-scale apps, testing, personal projects  
**Cost:** $0.00/month (100% FREE)  
**Setup Time:** 30 minutes

---

## 📊 What You're Getting (FREE!)

| Feature | Included | Limit |
|---------|----------|-------|
| **Database** | PostgreSQL (Supabase) | 500MB storage |
| **Hosting** | Railway | $5 credit/month (~500 hours) |
| **Bandwidth** | Both | 2GB/month |
| **SSL/HTTPS** | Automatic | Unlimited |
| **Deployments** | Unlimited | Automatic on git push |
| **Uptime** | 24/7 | No sleep/downtime |
| **Custom Domain** | Yes | Free subdomain included |

**Perfect for your small-scale money management app!** ✅

---

## 🚀 Quick Setup (30 Minutes)

### Phase 1: Supabase Database Setup (10 min)

#### Step 1: Create Supabase Account
1. Go to: https://supabase.com
2. Click **"Start your project"**
3. Sign up with GitHub (easiest)
4. **No credit card required!** ✅

#### Step 2: Create New Project
1. Click **"New Project"**
2. Fill in:
   - **Name:** money-management
   - **Database Password:** [Create strong password - SAVE THIS!]
   - **Region:** Choose closest to you
3. Click **"Create new project"**
4. Wait 2-3 minutes for setup

#### Step 3: Get Database Credentials
1. In Supabase dashboard, go to **Settings** → **Database**
2. Scroll to **Connection String** section
3. Copy the connection details:
   ```
   Host: db.xxxxxx.supabase.co
   Port: 5432
   Database: postgres
   User: postgres
   Password: [your password]
   ```
4. **SAVE THESE!** You'll need them later

---

### Phase 2: Update Laravel for PostgreSQL (5 min)

#### Step 1: Install PostgreSQL Driver
```bash
cd /Users/iffahrosani/Desktop/Main_Code/money-management
composer require doctrine/dbal
```

#### Step 2: Update .env File
Edit your `.env` file and update database section:

```env
# Change from SQLite to PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_supabase_password
```

#### Step 3: Test Local Connection
```bash
# Test database connection
php artisan migrate:fresh --seed

# If successful, you'll see:
# Migration table created successfully
# Migrating: xxxx_create_users_table
# Migrated:  xxxx_create_users_table
```

**If migration works, you're ready for deployment!** ✅

---

### Phase 3: Deploy to Railway (10 min)

#### Step 1: Create Railway Account
1. Go to: https://railway.app
2. Click **"Start a New Project"**
3. Sign up with **GitHub** (easiest)
4. **No credit card required!** ✅

#### Step 2: Deploy from GitHub
1. In Railway dashboard, click **"New Project"**
2. Select **"Deploy from GitHub repo"**
3. Grant Railway access to your GitHub
4. Select: **Afiqfar1s/money-management**
5. Railway will auto-detect Laravel! 🎉

#### Step 3: Add Environment Variables
In Railway dashboard:

1. Click on your project → **Variables** tab
2. Add these variables (click **"New Variable"** for each):

```env
APP_NAME="Money Management"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_URL=https://your-app.railway.app

DB_CONNECTION=pgsql
DB_HOST=db.xxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_supabase_password
DB_SSLMODE=require

SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database

LOG_CHANNEL=stack
LOG_LEVEL=error
```

**Get APP_KEY:**
```bash
# Run locally to generate:
php artisan key:generate --show
# Copy the output (starts with "base64:")
```

#### Step 4: Configure Build Settings
Railway auto-detects Laravel, but add this in **Settings** → **Build Command**:

```bash
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**Start Command:**
```bash
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

#### Step 5: Deploy!
1. Click **"Deploy"** button
2. Wait 3-5 minutes for build
3. Railway will give you a URL: `https://your-app.railway.app`
4. Click the URL to visit your live app! 🎉

---

### Phase 4: Initial Setup (5 min)

#### Step 1: Run Database Migrations
In Railway dashboard:

1. Go to **Deployments** tab
2. Click **"View Logs"**
3. Check if migrations ran (they should run automatically)

**Or manually run:**
1. Click **"Settings"** → **"Environment"**
2. Scroll down to **"Run Command"**
3. Enter: `php artisan migrate --force`

#### Step 2: Create Admin User
In Railway **"Run Command"**:

```bash
php artisan tinker
```

Then in tinker:
```php
$user = new App\Models\User;
$user->name = 'Admin';
$user->email = 'admin@example.com';
$user->password = bcrypt('your-password');
$user->role = 'admin';
$user->permissions = ['view_reports', 'manage_users', 'view_all_debtors', 'create_debtors', 'edit_debtors', 'delete_debtors'];
$user->save();
```

#### Step 3: Test Your App
1. Visit your Railway URL
2. Login with admin credentials
3. Test all features:
   - ✅ Dashboard loads
   - ✅ Can create debtors
   - ✅ Can add payments
   - ✅ PDF downloads work
   - ✅ Reports generate

---

## 🔄 Automatic Deployments

**Good news!** Railway automatically deploys when you push to GitHub:

```bash
# Make changes locally
git add .
git commit -m "Your changes"
git push origin main

# Railway automatically:
# 1. Detects the push
# 2. Builds your app
# 3. Deploys new version
# 4. Live in 3-5 minutes!
```

---

## 🌐 Custom Domain (Optional)

### Use Railway Subdomain (Free)
Railway gives you: `your-app.railway.app`

### Use Your Own Domain
1. Buy domain from Namecheap/GoDaddy
2. In Railway: **Settings** → **Domains**
3. Add custom domain
4. Update DNS records (Railway will show you how)
5. SSL is automatic! ✅

---

## 📊 Monitoring Usage

### Check Railway Usage
1. Dashboard → **Usage** tab
2. See:
   - Hours used
   - Credits remaining
   - Bandwidth used

### Check Supabase Usage
1. Dashboard → **Settings** → **Usage**
2. See:
   - Database size
   - API requests
   - Bandwidth

**Both reset monthly!**

---

## 🐛 Troubleshooting

### Issue: Migration Failed
```bash
# In Railway "Run Command":
php artisan migrate:fresh --force
```

### Issue: 500 Error
1. Check Railway logs: **Deployments** → **Logs**
2. Common fixes:
   - Check APP_KEY is set
   - Verify database credentials
   - Run: `php artisan config:clear`

### Issue: PDF Not Generating
```bash
# In Railway "Run Command":
php artisan view:clear
php artisan config:clear
```

### Issue: Session Not Working
Update .env:
```env
SESSION_DRIVER=database
```

Then:
```bash
php artisan migrate --force
```

---

## 💡 Pro Tips

### 1. Use Database Sessions
In `.env`:
```env
SESSION_DRIVER=database
CACHE_DRIVER=database
```

### 2. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Monitor Logs
Railway Dashboard → **Deployments** → **Logs**

### 4. Backup Database
Supabase Dashboard → **Database** → **Backups** (automatic!)

### 5. Environment Separation
- Use `dev-afiq` branch for testing
- Use `main` branch for production
- Railway can deploy different branches!

---

## 📈 Scaling Up (When Needed)

### When Free Tier Isn't Enough:

**Railway Paid Plans:**
- $5/month: ~750 hours
- $10/month: ~1500 hours
- $20/month: ~3000 hours

**Supabase Paid Plans:**
- $25/month: 8GB database
- More bandwidth
- Better performance

**You probably won't need paid plans for months!**

---

## ✅ Checklist

### Before Deployment:
- [ ] Supabase project created
- [ ] Database credentials saved
- [ ] PostgreSQL driver installed
- [ ] .env updated
- [ ] Migrations tested locally
- [ ] Railway account created
- [ ] GitHub repo connected

### After Deployment:
- [ ] App loads successfully
- [ ] Database migrations ran
- [ ] Admin user created
- [ ] Can login
- [ ] Dashboard works
- [ ] CRUD operations work
- [ ] PDF generation works
- [ ] All features tested

---

## 🎉 Success!

Your Money Management app is now:
- ✅ **Live on the internet**
- ✅ **Accessible 24/7**
- ✅ **Secure with HTTPS**
- ✅ **Free to run**
- ✅ **Auto-deploying**
- ✅ **Production-ready**

**Share your URL with users!** 🚀

---

## 📞 Need Help?

If you encounter issues:
1. Check Railway logs
2. Check Supabase dashboard
3. Review Laravel logs: `storage/logs/laravel.log`
4. Ask me for help!

---

**Ready to deploy? Let me know and I'll help you through each step!** 🎯
