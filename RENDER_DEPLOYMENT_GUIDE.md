# 🌐 Deploy Laravel to Render - FREE Hosting Guide

**Platform:** Render.com  
**Cost:** 100% FREE (No credit card required!)  
**Setup Time:** 15 minutes  
**Your App:** money-management (Laravel 11)

---

## 🎯 What You're Getting (100% FREE!)

✅ **Free Web Service** - 512MB RAM  
✅ **Automatic Deployments** - Every GitHub push  
✅ **Free SSL/HTTPS** - Automatic certificate  
✅ **Free Subdomain** - yourapp.onrender.com  
✅ **PostgreSQL Support** - Works with Supabase  
✅ **750 hours/month** - ~25 days runtime  

**⚠️ Note:** App sleeps after 15 minutes of inactivity (takes 30s to wake up)

---

## 📋 Prerequisites (Already Done! ✅)

- ✅ GitHub repo: `Afiqfar1s/money-management`
- ✅ Supabase database set up
- ✅ Laravel app working locally
- ✅ PostgreSQL driver installed

---

## 🚀 Step-by-Step Deployment

### **STEP 1: Prepare Your Laravel App** (5 minutes)

#### 1.1 Create Build Script

We need to tell Render how to build your Laravel app.

Create a file called `render-build.sh` in your project root:

```bash
#!/usr/bin/env bash
# exit on error
set -o errexit

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Generate optimized autoload files
composer dump-autoload --optimize

# Run database migrations
php artisan migrate --force --no-interaction

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if needed
php artisan storage:link
```

Make it executable:
```bash
chmod +x render-build.sh
```

#### 1.2 Create Start Script

Create `render-start.sh`:

```bash
#!/usr/bin/env bash

# Start PHP built-in server
php artisan serve --host=0.0.0.0 --port=$PORT
```

Make it executable:
```bash
chmod +x render-start.sh
```

#### 1.3 Update .gitignore

Make sure these files are NOT ignored in `.gitignore`:
- `render-build.sh` ✅
- `render-start.sh` ✅

#### 1.4 Commit and Push

```bash
git add render-build.sh render-start.sh
git commit -m "Add Render deployment scripts"
git push origin dev-afiq
```

---

### **STEP 2: Create Render Account** (2 minutes)

1. **Go to:** https://render.com
2. Click **"Get Started"** or **"Sign Up"**
3. Choose **"Sign up with GitHub"**
4. Authorize Render to access your GitHub
5. ✅ **No credit card required!**

---

### **STEP 3: Create New Web Service** (3 minutes)

1. In Render Dashboard, click **"New +"** → **"Web Service"**

2. **Connect Repository:**
   - You'll see your GitHub repos
   - Find and select: **`Afiqfar1s/money-management`**
   - Click **"Connect"**

3. **Configure Service:**

   Fill in these details:

   | Field | Value |
   |-------|-------|
   | **Name** | `money-management` |
   | **Region** | Singapore (closest to you) |
   | **Branch** | `dev-afiq` or `main` (your choice) |
   | **Root Directory** | Leave blank |
   | **Runtime** | `Docker` or `Native Environment` |
   | **Build Command** | `./render-build.sh` |
   | **Start Command** | `./render-start.sh` |
   | **Plan** | **Free** ✅ |

4. Click **"Advanced"** to add environment variables (next step)

---

### **STEP 4: Configure Environment Variables** (5 minutes)

Click **"Add Environment Variable"** and add these one by one:

#### Required Variables:

```env
APP_NAME=MoneyManagement
APP_ENV=production
APP_KEY=base64:1h6Cn5s51bt7hnPx/RAuKRE9m+UUFvdrLh8jd9wTVJo=
APP_DEBUG=false
APP_URL=https://money-management.onrender.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# Database (Supabase)
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-south-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.ljgoxrtxwibexxvbddbm
DB_PASSWORD=Mafinir301097!
DB_SSLMODE=require

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=database
CACHE_PREFIX=money_

# Queue
QUEUE_CONNECTION=database

# Mail (Optional - add if needed)
MAIL_MAILER=log
```

**Important Notes:**
- Replace `APP_URL` with your actual Render URL (shown after deployment)
- Use your actual Supabase credentials
- Keep `APP_DEBUG=false` for production
- Use `SESSION_DRIVER=database` (not file-based)

---

### **STEP 5: Deploy!** (2 minutes)

1. **Review all settings**
2. Click **"Create Web Service"**
3. **Wait for deployment** (takes 2-5 minutes)

You'll see logs showing:
```
==> Installing dependencies...
==> Running build command...
==> Running migrations...
==> Starting server...
==> Your service is live!
```

4. Once done, you'll get a URL like:
   ```
   https://money-management.onrender.com
   ```

---

## ✅ **STEP 6: Test Your Deployed App**

1. **Visit your URL:** `https://money-management.onrender.com`

2. **Login with:**
   - Admin: `admin@example.com` / `admin123`
   - Test User: `test@example.com` / `password`

3. **Test features:**
   - ✅ Create company
   - ✅ Create debtor
   - ✅ Add payment
   - ✅ Download report (the bug we fixed!)

---

## 🔧 **Post-Deployment Configuration**

### Update APP_URL in Render

Once you have your live URL:

1. Go to Render Dashboard → Your Service
2. Click **"Environment"** tab
3. Update `APP_URL` to your actual URL:
   ```
   APP_URL=https://money-management.onrender.com
   ```
4. Click **"Save Changes"**
5. Render will automatically redeploy

---

## 🎛️ **Managing Your App**

### View Logs
- Dashboard → Your Service → **"Logs"** tab
- See real-time server logs

### Redeploy Manually
- Dashboard → Your Service → **"Manual Deploy"** → **"Deploy latest commit"**

### Auto-Deploy on Git Push
- Every time you `git push`, Render automatically redeploys! 🚀

### Run Artisan Commands
1. Dashboard → Your Service → **"Shell"** tab
2. Opens a terminal in your app
3. Run commands:
   ```bash
   php artisan migrate
   php artisan cache:clear
   php artisan tinker
   ```

---

## ⚠️ **Important Notes About Free Tier**

### Sleep Behavior
- App sleeps after **15 minutes** of no activity
- First request after sleep takes **30-60 seconds** to wake up
- Subsequent requests are instant

### Keep App Awake (Optional)
Use a free uptime monitor:
1. **UptimeRobot** (https://uptimerobot.com) - FREE
2. Add your Render URL
3. Set check interval: every 5 minutes
4. App stays awake 24/7! ✅

### Storage Limitations
- No persistent file storage on free tier
- Uploads reset on each deploy
- **Solution:** Use S3, Cloudinary, or Supabase Storage for files

---

## 🐛 **Troubleshooting**

### Error: "Application key not set"
**Fix:** Make sure `APP_KEY` is set in environment variables

### Error: "Database connection failed"
**Fix:** 
1. Check Supabase credentials in environment variables
2. Make sure using Session Pooler host (aws-1-ap-south-1.pooler.supabase.com)
3. Check `DB_SSLMODE=require` is set

### Error: "500 Internal Server Error"
**Fix:**
1. Check logs in Render Dashboard
2. Set `APP_DEBUG=true` temporarily
3. Check storage permissions
4. Run: `php artisan config:clear`

### App is slow to load
**Reason:** App was sleeping
**Solution:** 
- Wait 30s for first load
- Set up UptimeRobot to keep it awake

---

## 🔄 **Update Your App**

### Method 1: Git Push (Automatic)
```bash
# Make changes to your code
git add .
git commit -m "Your changes"
git push origin dev-afiq

# Render automatically deploys! ✅
```

### Method 2: Manual Deploy
1. Render Dashboard → Your Service
2. Click **"Manual Deploy"**
3. Select **"Clear build cache & deploy"**

---

## 📊 **Check Your Data in Supabase**

1. Go to: https://supabase.com/dashboard
2. Click **"money-management"** project
3. Click **"Table Editor"**
4. See data created from your Render app in real-time! 🎉

---

## 🎯 **Your Deployment URLs**

| Service | URL | Purpose |
|---------|-----|---------|
| **Production** | `https://money-management.onrender.com` | Live app |
| **Database** | Supabase Dashboard | View/manage data |
| **Logs** | Render Dashboard → Logs | Debug issues |
| **GitHub** | `github.com/Afiqfar1s/money-management` | Source code |

---

## 💡 **Pro Tips**

1. **Monitor Usage:**
   - Check Render Dashboard for monthly hours used
   - Free tier: 750 hours/month (enough for small apps)

2. **Performance:**
   - First load after sleep: 30-60s
   - Regular loads: 1-2s
   - Use UptimeRobot to eliminate sleep delays

3. **Database:**
   - Supabase free tier: 500MB (plenty for small apps)
   - Check usage in Supabase Dashboard → Settings → Usage

4. **Custom Domain:**
   - Render supports custom domains on free tier!
   - Dashboard → Your Service → Settings → Custom Domain

---

## 🎉 **You're Done!**

Your Laravel Money Management app is now:
- ✅ Deployed to Render (FREE)
- ✅ Connected to Supabase PostgreSQL
- ✅ Auto-deploys on every git push
- ✅ Has SSL/HTTPS
- ✅ Accessible worldwide

**Next Steps:**
1. Test your app thoroughly
2. Share the URL with users
3. Set up UptimeRobot if you want 24/7 uptime
4. Monitor logs for any issues

**Need help?** Check Render logs or ask me! 🚀
