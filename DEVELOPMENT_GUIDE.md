# Development Workflow Guide

## ⚠️ Why Your Changes Don't Show Up

When you run `php artisan serve`, your changes might not appear because Laravel is using **cached** versions of:

1. **Routes** (cached with `php artisan route:cache`)
2. **Configuration** (cached with `php artisan config:cache`)
3. **Views** (compiled Blade templates)
4. **Application** (optimized autoloader)

---

## 🔧 The Problem

You previously ran these commands which created caches:
```bash
php artisan route:cache     # ❌ Don't use in development!
php artisan config:cache    # ❌ Don't use in development!
```

**These commands are for PRODUCTION only!** In development, they prevent Laravel from detecting your changes.

---

## ✅ Solution: Clear All Caches

### Option 1: Quick Clear (Recommended)
```bash
php artisan optimize:clear
```

This single command clears:
- ✓ Configuration cache
- ✓ Route cache
- ✓ View cache
- ✓ Compiled services
- ✓ Bootstrap cache

### Option 2: Individual Cache Clearing
```bash
php artisan config:clear    # Clear config cache
php artisan route:clear     # Clear route cache
php artisan view:clear      # Clear compiled views
php artisan cache:clear     # Clear application cache
```

---

## 🚀 Proper Development Workflow

### Starting Your Server (New Way)

**Use the provided script:**
```bash
./dev-server.sh
```

This automatically:
1. Clears all caches
2. Starts the server
3. Ensures changes are detected

**Or manually:**
```bash
# Clear caches first
php artisan optimize:clear

# Then start server
php artisan serve
```

---

## 📋 Development Best Practices

### ✅ DO in Development:

1. **Always start fresh:**
   ```bash
   php artisan optimize:clear && php artisan serve
   ```

2. **After making changes:**
   - **Views (.blade.php)**: Changes appear immediately (no cache needed)
   - **Routes (web.php)**: Changes appear immediately (if no route cache)
   - **Controllers**: Changes appear immediately (if no config cache)
   - **Config (.env)**: Run `php artisan config:clear` after changes
   - **CSS/JS (Vite)**: Run `npm run build` after changes

3. **Clear browser cache:**
   - **Mac:** Cmd + Shift + R
   - **Windows/Linux:** Ctrl + Shift + R
   - Or use **Incognito/Private** mode

### ❌ DON'T in Development:

1. **Never run these in development:**
   ```bash
   php artisan route:cache     # ❌ Routes won't update!
   php artisan config:cache    # ❌ Config won't update!
   php artisan optimize        # ❌ Creates caches!
   ```

2. **Don't cache unless deploying to production**

---

## 🔄 Common Scenarios

### Scenario 1: Changed a Controller
**Problem:** Controller changes don't appear
**Solution:**
```bash
# Stop server (Ctrl+C)
php artisan optimize:clear
php artisan serve
```

### Scenario 2: Changed Routes
**Problem:** New routes show 404
**Solution:**
```bash
# Stop server (Ctrl+C)
php artisan route:clear
php artisan serve
```

### Scenario 3: Changed .env File
**Problem:** Config values don't update
**Solution:**
```bash
php artisan config:clear
# No need to restart server
```

### Scenario 4: Changed Blade View
**Problem:** HTML changes don't show
**Solution:**
```bash
php artisan view:clear
# Refresh browser (Cmd+Shift+R)
```

### Scenario 5: Changed CSS/JS
**Problem:** Styles or scripts don't update
**Solution:**
```bash
npm run build
# Refresh browser (Cmd+Shift+R)
```

---

## 🎯 Quick Reference Commands

### Development (Use These):
```bash
# Clear everything
php artisan optimize:clear

# Start server with clean slate
./dev-server.sh

# Or manually
php artisan optimize:clear && php artisan serve
```

### When to Clear What:
| Changed | Command |
|---------|---------|
| Controller | `php artisan optimize:clear` |
| Route | `php artisan route:clear` |
| .env | `php artisan config:clear` |
| View | `php artisan view:clear` |
| CSS/JS | `npm run build` |
| Everything | `php artisan optimize:clear` |

---

## 🏭 Production vs Development

### Development (Your Current Setup):
```bash
# ✅ Good - No caching
php artisan optimize:clear
php artisan serve

# Changes appear immediately
```

### Production (Deployment):
```bash
# ✅ Good - Cache everything for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Fast performance, but changes need re-caching
```

---

## 🛠️ Your New Workflow

### Every Time You Start Coding:

1. **Open Terminal**
   ```bash
   cd /Users/iffahrosani/Desktop/Main_Code/money-management
   ```

2. **Start Server (Choose One):**
   
   **Option A - Using Script (Easiest):**
   ```bash
   ./dev-server.sh
   ```
   
   **Option B - Manual:**
   ```bash
   php artisan optimize:clear && php artisan serve
   ```

3. **Open Browser:**
   - Go to `http://127.0.0.1:8000`
   - Login as admin: `admin@example.com` / `admin123`

### When You Make Changes:

1. **Backend Changes (Controller, Routes, Models):**
   ```bash
   # Stop server (Ctrl+C)
   php artisan optimize:clear
   php artisan serve
   ```

2. **Frontend Changes (Blade Views):**
   ```bash
   php artisan view:clear
   # Just refresh browser
   ```

3. **CSS/JS Changes:**
   ```bash
   npm run build
   # Refresh browser with Cmd+Shift+R
   ```

4. **Config Changes (.env):**
   ```bash
   php artisan config:clear
   # No restart needed
   ```

---

## 🔍 Troubleshooting

### "Changes still don't show up"

1. **Clear Laravel caches:**
   ```bash
   php artisan optimize:clear
   ```

2. **Clear browser cache:**
   - Hard refresh: Cmd+Shift+R (Mac) or Ctrl+Shift+R (Windows)
   - Or use Incognito mode

3. **Rebuild assets:**
   ```bash
   npm run build
   ```

4. **Check for errors:**
   ```bash
   tail -50 storage/logs/laravel.log
   ```

5. **Restart server:**
   ```bash
   # Stop with Ctrl+C
   ./dev-server.sh
   ```

### "Server shows old version"

**Cause:** Browser cache or service worker
**Solution:**
1. Clear browser cache
2. Use Incognito/Private mode
3. Disable service workers in DevTools

### "Route not found (404)"

**Cause:** Route cache is active
**Solution:**
```bash
php artisan route:clear
php artisan serve
```

---

## 📊 Cache Status Check

To see if caches exist:
```bash
# Check if route cache exists
ls -la bootstrap/cache/routes-v7.php

# Check if config cache exists
ls -la bootstrap/cache/config.php

# If these files exist, you have caching enabled!
```

To remove them:
```bash
php artisan optimize:clear
```

---

## 🎯 Summary

### The Main Issue:
You were running `php artisan route:cache` and `php artisan config:cache`, which are **production commands**. They freeze your routes and config, preventing changes from appearing.

### The Fix:
Always use **`php artisan optimize:clear`** before starting your server in development.

### The New Way:
```bash
./dev-server.sh
```
This script does everything for you!

---

## 🚀 Quick Start (Right Now)

Run this in your terminal:
```bash
cd /Users/iffahrosani/Desktop/Main_Code/money-management
./dev-server.sh
```

Your changes will now appear immediately! 🎉

---

**Last Updated:** January 5, 2026
**Your Environment:** Development (macOS)
**Server Port:** 8000 or 8001
