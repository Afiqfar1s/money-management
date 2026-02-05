# 🎨 Deploy Laravel to Render - Complete Guide

## Why Render?
- ✅ **FREE tier:** 750 hours/month
- ✅ **Docker support** for Laravel
- ✅ **Works with Supabase** (your existing DB)
- ✅ **GitHub auto-deploy**
- ✅ **Custom domains** supported
- ✅ **SSL certificates** included

---

## 📋 Prerequisites

- [x] GitHub account
- [x] Render account (sign up at https://render.com)
- [x] Project pushed to GitHub
- [x] Supabase database credentials

---

## 🎯 Step-by-Step Deployment

### Step 1: Prepare Your Project (Already Done! ✅)

Files created for you:
- ✅ `Dockerfile` - Docker container configuration
- ✅ `render-build.sh` - Build script
- ✅ `render.yaml` - Render service configuration

### Step 2: Push to GitHub

```bash
git add Dockerfile render-build.sh render.yaml
git commit -m "Add Render deployment configuration"
git push origin dev-afiq
```

### Step 3: Sign Up for Render

1. Go to https://render.com
2. Click **"Get Started"**
3. Sign up with your **GitHub account**
4. Authorize Render to access your repositories

---

### Step 4: Create New Web Service

1. Click **"New +"** → **"Web Service"**
2. Connect your GitHub repository: `Afiqfar1s/money-management`
3. Select branch: `dev-afiq` (or `main`)

Render will auto-detect your Dockerfile.

---

### Step 5: Configure Service

**Basic Settings:**
- **Name:** `money-management` (or your choice)
- **Region:** Singapore (closest to your Supabase)
- **Branch:** `dev-afiq`
- **Runtime:** Docker (auto-detected)
- **Instance Type:** Free

**Advanced Settings:**
- **Dockerfile Path:** `./Dockerfile`
- **Docker Context:** `.` (root)
- **Auto-Deploy:** Yes (on git push)

---

### Step 6: Add Environment Variables

Click **"Advanced"** → **"Add Environment Variable"**

Add these one by one:

```env
APP_NAME=Money Management
APP_ENV=production
APP_KEY=base64:1h6Cn5s51bt7hnPx/RAuKRE9m+UUFvdrLh8jd9wTVJo=
APP_DEBUG=false
APP_URL=https://your-app.onrender.com

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
QUEUE_CONNECTION=database

# Mail (optional - configure later)
MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Important Notes:**
- Replace `APP_KEY` with your actual key from `.env`
- Replace `DB_PASSWORD` with your actual Supabase password
- Update `APP_URL` after deployment with your actual Render URL

---

### Step 7: Create the Service

Click **"Create Web Service"**

Render will:
1. Clone your repository
2. Build Docker image (takes 5-10 minutes first time)
3. Run migrations (via Dockerfile)
4. Start the application
5. Provide you a URL like: `https://money-management-xxxx.onrender.com`

---

### Step 8: Run Database Seeders (Optional)

After first deployment, you may want to seed the database.

#### Option A: Using Render Shell

1. Go to your service dashboard
2. Click **"Shell"** tab
3. Run:

```bash
php artisan db:seed --force --class=DatabaseSeeder
```

#### Option B: SSH via Terminal

```bash
# Install Render CLI
npm install -g @render-cli/render-cli

# Login
render login

# Get your service ID from dashboard
render services

# Run command
render shell <service-id>
php artisan db:seed --force
```

---

### Step 9: Update APP_URL

1. Copy your Render URL (e.g., `https://money-management-xxxx.onrender.com`)
2. Go to **Environment** → Edit `APP_URL`
3. Paste your actual URL
4. Click **"Save Changes"**
5. Render will redeploy automatically

---

### Step 10: Test Your Deployment

1. Open your Render URL
2. You should see the login page
3. Login with admin credentials:
   - Email: `admin@example.com`
   - Password: `admin123`
4. Test all features!

---

## 🔧 Post-Deployment Configuration

### Custom Domain (Optional)

1. Go to **Settings** → **Custom Domain**
2. Click **"Add Custom Domain"**
3. Enter your domain (e.g., `money.yourdomain.com`)
4. Add CNAME record to your DNS:
   ```
   CNAME: money → your-app.onrender.com
   ```
5. Render will automatically provision SSL certificate

### Storage Configuration

For file uploads (company logos), you have 2 options:

#### Option 1: Use Render's Disk (Limited)
```env
FILESYSTEM_DISK=public
```
**Note:** Files persist but are limited. Not recommended for production.

#### Option 2: Use External Storage (Recommended)
- **AWS S3** or **Cloudinary** for production
- Update `.env` with S3/Cloudinary credentials

---

## 🔄 Auto-Deploy on Push

Render automatically deploys when you push to your connected branch:

```bash
git add .
git commit -m "Your changes"
git push origin dev-afiq
```

Render will:
1. Detect the push
2. Rebuild Docker image
3. Run migrations (if configured)
4. Deploy new version
5. Keep old version running until new one is ready (zero downtime)

---

## 📊 Monitor Your App

### Render Dashboard
- **Logs:** Real-time application logs
- **Metrics:** Memory, CPU usage
- **Deploy History:** All past deployments
- **Events:** Build and deploy events

### Access Logs
1. Go to your service dashboard
2. Click **"Logs"** tab
3. View real-time logs
4. Filter by date/severity

---

## 💰 Pricing

### Free Tier
- **750 hours/month** of usage
- Services **spin down after 15 minutes** of inactivity
- **Cold starts** when service wakes up (30-60 seconds)
- **1 web service free**

### Starter Tier ($7/month)
- Always-on service
- No cold starts
- Better performance
- More resources

**For Testing:** Free tier is perfect!
**For Production:** Consider Starter tier for always-on

---

## 🐛 Troubleshooting

### Build Fails

**Check Dockerfile:**
```bash
# Test locally first
docker build -t money-management .
docker run -p 8000:80 money-management
```

**Common Issues:**
- Composer dependencies failing → Check `composer.json`
- npm build failing → Ensure `package.json` is correct
- Permission errors → Check file permissions in Dockerfile

### Database Connection Error

**Check:**
- All `DB_*` variables are correct
- Supabase allows connections from Render IPs
- `DB_SSLMODE=require` is set
- Database exists and is accessible

**Test connection:**
```bash
# In Render Shell
php artisan db:show
```

### 500 Error

**Check logs:**
1. Render Dashboard → Logs tab
2. Look for PHP errors

**Common fixes:**
- Ensure `APP_KEY` is set
- Run `php artisan config:clear`
- Check file permissions
- Verify all environment variables

### Storage/Upload Issues

**For production:**
- Don't use local storage on Render
- Use S3 or Cloudinary for file uploads
- Render's disk is ephemeral (resets on deploy)

### Cold Starts (Free Tier)

**Issue:** App spins down after 15 min inactivity

**Solutions:**
1. Upgrade to Starter plan ($7/month)
2. Use cron job to ping your app every 10 minutes:
   ```bash
   */10 * * * * curl https://your-app.onrender.com
   ```
3. Accept cold starts for testing/development

---

## 🔒 Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production` is set
- [ ] Strong `APP_KEY` is generated
- [ ] Database credentials are secure
- [ ] HTTPS is enabled (automatic on Render)
- [ ] `.env` file is NOT in Git
- [ ] Regular security updates

---

## 🚀 Performance Tips

### 1. Optimize Dockerfile
Already optimized for you:
- Multi-stage build (if needed)
- Composer autoload optimization
- Production npm build
- Cache clearing

### 2. Enable Caching
```env
CACHE_DRIVER=database
# Or upgrade to Redis (paid)
```

### 3. Queue Jobs
```env
QUEUE_CONNECTION=database
# Or upgrade to Redis for better performance
```

### 4. CDN for Assets
- Use Cloudflare CDN
- Serve static assets from CDN
- Reduce server load

---

## 📝 Environment Variables Reference

### Required:
```env
APP_NAME=Money Management
APP_ENV=production
APP_KEY=base64:...
APP_URL=https://your-app.onrender.com
APP_DEBUG=false

DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-south-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.ljgoxrtxwibexxvbddbm
DB_PASSWORD=your-password
DB_SSLMODE=require

SESSION_DRIVER=database
CACHE_DRIVER=database
```

### Optional:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
```

---

## 🎉 Success Checklist

- [ ] Repository pushed to GitHub
- [ ] Render account created
- [ ] Web service created
- [ ] Environment variables added
- [ ] Build completed successfully
- [ ] App URL works
- [ ] Database connected
- [ ] Admin login works
- [ ] All features tested
- [ ] Logs reviewed (no errors)

---

## 📚 Additional Resources

- Render Docs: https://render.com/docs
- Laravel Deployment: https://laravel.com/docs/deployment
- Docker Docs: https://docs.docker.com
- Render Community: https://community.render.com

---

## 🔄 Quick Commands

```bash
# Check service status
render services

# View logs
render logs <service-id>

# Open shell
render shell <service-id>

# Run artisan commands
render shell <service-id>
php artisan migrate
php artisan cache:clear
php artisan config:clear
```

---

## 💡 Pro Tips

1. **Test Dockerfile locally** before deploying
2. **Use render.yaml** for Infrastructure as Code
3. **Monitor logs** regularly during first week
4. **Set up alerts** in Render dashboard
5. **Keep dependencies updated** regularly

---

## 🆘 Need Help?

- Render Docs: https://render.com/docs
- Community Forum: https://community.render.com
- Support: support@render.com
- This guide: Re-read carefully!

---

**Good luck with your deployment!** 🚀

If you encounter issues, check the troubleshooting section first!
