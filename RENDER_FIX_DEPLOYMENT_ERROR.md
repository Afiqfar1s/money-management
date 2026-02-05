# 🔧 Fix Render Deployment Error

## ❌ Error You're Seeing

```
==> Application exited early
==> Common ways to troubleshoot your deploy: https://render.com/docs/troubleshooting-deploys
```

## 🎯 Root Causes

1. **Wrong Branch**: Deploying from `main` branch instead of `dev-afiq`
2. **Wrong Build Command**: Render is trying to run the script content instead of the script file
3. **Missing Deployment Scripts**: The `main` branch doesn't have `render-build.sh` and `render-start.sh`

---

## ✅ FIX: Update Render Settings

### **Step 1: Go to Service Settings**

1. Open Render Dashboard: https://dashboard.render.com
2. Click on your **"money-management"** service
3. Click **"Settings"** tab (left sidebar)

---

### **Step 2: Change Branch** ⭐ IMPORTANT

Scroll down and find:

```
┌─────────────────────────────────────────┐
│ Branch                                   │
│ [main              ▼]  ← CHANGE THIS   │
└─────────────────────────────────────────┘
```

**Change to:**

```
┌─────────────────────────────────────────┐
│ Branch                                   │
│ [dev-afiq          ▼]  ← USE THIS      │
└─────────────────────────────────────────┘
```

Click **"Save Changes"**

---

### **Step 3: Fix Build Command**

Find the **"Build Command"** field.

**Currently shows:**
```
#!/usr/bin/env bash # Render.com build script for Laravel...
(very long text with entire script content)
```

**DELETE ALL THAT!**

**Replace with EXACTLY:**
```
bash render-build.sh
```

Just those 3 words! Nothing more.

Click **"Save Changes"**

---

### **Step 4: Fix Start Command**

Find the **"Start Command"** field.

**Should say:**
```
bash render-start.sh
```

If it's different, change it to exactly that.

Click **"Save Changes"**

---

### **Step 5: Set Environment (Optional but Recommended)**

Find **"Environment"** dropdown:

Change from: **Node**  
Change to: **Docker** or **Native Environment**

(This ensures proper PHP detection)

Click **"Save Changes"**

---

## 🚀 Step 6: Redeploy

1. Go back to your service dashboard (click your service name)
2. Click **"Manual Deploy"** button (top right)
3. Select **"Clear build cache & deploy"**
4. Click **"Deploy"**

---

## ✅ What Success Looks Like

### **Correct Deployment Logs:**

```
==> Cloning from https://github.com/Afiqfar1s/money-management
==> Checking out commit bf8eef2 in branch dev-afiq ✅ (CORRECT!)
==> Running build command 'bash render-build.sh'...

🔨 Starting Render build process...
📦 Installing PHP dependencies...
Loading composer repositories with package information
Installing dependencies from lock file
Package operations: 107 installs, 0 updates, 0 removals
  - Installing symfony/polyfill-ctype (v1.31.0): Extracting archive
  ...

⚡ Optimizing autoloader...
Generated optimized autoload files

🗄️  Running database migrations...
Migration table created successfully.
Migrating: 0001_01_01_000000_create_users_table
Migrated:  0001_01_01_000000_create_users_table
...

🚀 Optimizing Laravel...
Configuration cached successfully!
Routes cached successfully!
Blade templates cached successfully!

🔗 Creating storage link...
The [public/storage] link has been connected to [storage/app/public].

✅ Build complete!

==> Build successful 🎉
==> Deploying...
==> Starting with 'bash render-start.sh'...

🚀 Starting Laravel application...
📍 Port: 10000
Laravel development server started: http://0.0.0.0:10000

==> Your service is live at https://money-management.onrender.com 🎉
```

---

## 📋 Quick Settings Reference

Copy these exact values into Render:

| Setting | Value |
|---------|-------|
| **Branch** | `dev-afiq` |
| **Build Command** | `bash render-build.sh` |
| **Start Command** | `bash render-start.sh` |
| **Environment** | Docker or Native Environment |

---

## 🆘 Still Having Issues?

### Check Environment Variables

Make sure these are set in **Environment** tab:

```
APP_KEY=base64:1h6Cn5s51bt7hnPx/RAuKRE9m+UUFvdrLh8jd9wTVJo=
DB_HOST=aws-1-ap-south-1.pooler.supabase.com
DB_USERNAME=postgres.ljgoxrtxwibexxvbddbm
DB_PASSWORD=Mafinir301097!
```

### Check PHP Version

Render should auto-detect PHP from `composer.json`. If not:

Add file: `.php-version`
```
8.2
```

Then commit and push:
```bash
echo "8.2" > .php-version
git add .php-version
git commit -m "Add PHP version for Render"
git push origin dev-afiq
```

---

## ✅ After Successful Deploy

1. **Visit your URL**: Check the Render dashboard for your live URL
2. **Test login**: Use `admin@example.com` / `admin123`
3. **Check logs**: Dashboard → Logs tab (watch for any errors)
4. **Update APP_URL**: Change environment variable to your actual URL

---

## 🎯 Summary

**3 Things to Change:**
1. ✅ Branch: `main` → `dev-afiq`
2. ✅ Build Command: Delete long text → `bash render-build.sh`
3. ✅ Start Command: `bash render-start.sh`

**Then:** Click "Manual Deploy" → "Clear build cache & deploy"

**Result:** Your app will deploy successfully! 🚀
