# 🚂 Deploy Laravel to Railway - Complete Guide

## Why Railway?
- ✅ **FREE tier:** $5 credit/month (≈500 execution hours)
- ✅ **Built-in PostgreSQL** (or use your Supabase)
- ✅ **GitHub auto-deploy**
- ✅ **Custom domains** supported
- ✅ **Environment variables** management
- ✅ **Zero configuration** for Laravel

---

## 📋 Prerequisites

- [x] GitHub account
- [x] Railway account (sign up at https://railway.app)
- [x] Your Laravel project pushed to GitHub
- [x] Supabase database (already set up)

---

## 🎯 Step-by-Step Deployment

### Step 1: Prepare Your Laravel Project

#### 1.1 Create Procfile
Create a file named `Procfile` in your project root:

```bash
web: php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php -S 0.0.0.0:$PORT -t public
```

#### 1.2 Create nixpacks.toml
Create `nixpacks.toml` in project root for Railway build configuration:

```toml
[phases.setup]
nixPkgs = ['php82', 'php82Packages.composer']

[phases.install]
cmds = ['composer install --no-dev --optimize-autoloader']

[phases.build]
cmds = ['php artisan optimize']

[start]
cmd = 'php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT'
```

#### 1.3 Update .gitignore
Ensure these are in `.gitignore`:

```
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.phpunit.result.cache
```

#### 1.4 Commit Changes
```bash
git add Procfile nixpacks.toml
git commit -m "Add Railway deployment configuration"
git push origin dev-afiq
```

---

### Step 2: Sign Up for Railway

1. Go to https://railway.app
2. Click **"Start a New Project"**
3. Sign in with your **GitHub account**
4. Authorize Railway to access your repositories

---

### Step 3: Create New Project

1. Click **"New Project"**
2. Select **"Deploy from GitHub repo"**
3. Choose your repository: `Afiqfar1s/money-management`
4. Select branch: `dev-afiq` (or `main`)
5. Railway will auto-detect it's a Laravel project

---

### Step 4: Configure Environment Variables

Click on your deployment → **Variables** tab → Add these:

```env
APP_NAME="Money Management"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=error

# Database (Supabase)
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-south-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.ljgoxrtxwibexxvbddbm
DB_PASSWORD=YOUR_SUPABASE_PASSWORD
DB_SSLMODE=require

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=database
QUEUE_CONNECTION=database

# Mail (optional - configure later)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Important:** 
- Replace `YOUR_APP_KEY_HERE` with your actual app key from local `.env`
- Replace `YOUR_SUPABASE_PASSWORD` with your actual password

---

### Step 5: Generate APP_KEY (if needed)

If you don't have an APP_KEY:

```bash
php artisan key:generate --show
```

Copy the output and paste it in Railway's `APP_KEY` variable.

---

### Step 6: Deploy!

1. Railway will **automatically deploy** after you add variables
2. Watch the build logs in real-time
3. Wait for **"Build successful"** message
4. Railway will provide a URL like: `https://money-management-production-xxxx.railway.app`

---

### Step 7: Run Migrations & Seeders

Once deployed, open the **Railway CLI** or use the web interface:

#### Option A: Railway Web Interface
1. Go to your project
2. Click **"Settings"** → **"Service"** → **"CLI"**
3. Run commands:

```bash
php artisan migrate --force
php artisan db:seed --force --class=DatabaseSeeder
```

#### Option B: Railway CLI (Local)
Install Railway CLI:

```bash
# macOS
brew install railway

# Login
railway login

# Link to your project
railway link

# Run commands
railway run php artisan migrate --force
railway run php artisan db:seed --force
```

---

### Step 8: Test Your Deployment

1. Open your Railway app URL
2. Login with admin credentials:
   - Email: `admin@example.com`
   - Password: `admin123`
3. Test all features!

---

## 🔧 Post-Deployment Configuration

### Custom Domain (Optional)

1. Go to **Settings** → **Domains**
2. Click **"Add Domain"**
3. Enter your custom domain (e.g., `money.yourdomain.com`)
4. Add CNAME record to your DNS:
   ```
   CNAME: money → your-app.railway.app
   ```

### Storage Configuration

If you need file uploads (company logos):

1. Add to environment variables:
   ```env
   FILESYSTEM_DISK=public
   ```

2. Link storage:
   ```bash
   railway run php artisan storage:link
   ```

Or use external storage like **AWS S3** or **Cloudinary**.

---

## 🔄 Auto-Deploy on Push

Railway automatically deploys when you push to your connected branch:

```bash
git add .
git commit -m "Your changes"
git push origin dev-afiq
```

Railway will:
1. Detect the push
2. Build new image
3. Run migrations (if in Procfile)
4. Deploy automatically

---

## 📊 Monitor Your App

### Railway Dashboard
- **Metrics:** CPU, Memory, Network usage
- **Logs:** Real-time application logs
- **Deployments:** History of all deployments

### Access Logs
```bash
railway logs
```

---

## 💰 Pricing

**Free Tier:**
- $5 credit/month (~500 execution hours)
- No credit card required initially
- Perfect for testing/small apps

**Upgrade Later:**
- Pay-as-you-go after free credit
- ~$0.000463/GB-hour for compute
- ~$0.25/GB for bandwidth

---

## 🐛 Troubleshooting

### Build Fails
**Check:**
- `composer.json` is valid
- PHP version in `nixpacks.toml` matches your project (8.2)
- All dependencies can be installed

**Fix:**
```bash
railway logs
```

### Database Connection Error
**Check:**
- All DB_ variables are correct
- Supabase allows connections from Railway IPs
- `DB_SSLMODE=require` is set

### 500 Error
**Check logs:**
```bash
railway logs --tail
```

**Common fixes:**
- Ensure `APP_KEY` is set
- Run `php artisan config:clear`
- Check file permissions

### Storage Issues
**Solution:** Use external storage (S3, Cloudinary) or Railway Volumes

---

## 🎉 Success Checklist

- [ ] Project deploys successfully
- [ ] Can access login page
- [ ] Admin login works
- [ ] Database connection works
- [ ] Company logos display
- [ ] All CRUD operations work
- [ ] PDF downloads work
- [ ] Search functionality works

---

## 📚 Additional Resources

- Railway Docs: https://docs.railway.app
- Laravel Deployment: https://laravel.com/docs/deployment
- Railway Community: https://discord.gg/railway

---

## 🚀 Quick Commands Reference

```bash
# View logs
railway logs

# Run artisan commands
railway run php artisan migrate
railway run php artisan cache:clear
railway run php artisan config:clear

# SSH into container
railway shell

# Restart service
railway restart
```

---

**Need Help?** Check Railway logs first, then their Discord community is very responsive!

Good luck with your deployment! 🎊
