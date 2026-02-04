# Setup Complete! 🎉

Your Laravel Money Management project has been set up successfully. Here's what has been done:

## ✅ Completed Steps

1. **Environment Configuration** - Created `.env` file from `.env.example`
2. **PHP Dependencies** - Installed via Composer (updated to be compatible with PHP 8.2)
3. **Node.js Dependencies** - Installed via npm
4. **Application Key** - Generated successfully
5. **Frontend Assets** - Built with Vite

## 🔧 Next Steps - Supabase Configuration

You need to configure your Supabase database connection. Follow these steps:

### 1. Get Your Supabase Credentials

1. Go to your [Supabase Dashboard](https://app.supabase.com)
2. Select your project
3. Go to **Settings** → **Database**
4. Find the **Connection String** section
5. Note down these values:
   - Host (e.g., `db.xxxxxxxxxxxxx.supabase.co`)
   - Database name (usually `postgres`)
   - Port (usually `5432`)
   - User (usually `postgres`)
   - Password

### 2. Update Your .env File

Open `.env` and update the database section with your Supabase credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxxxxxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password
```

**Important:** Replace the placeholder values with your actual Supabase credentials!

### 3. Run Database Migrations

After updating the `.env` file, run the migrations to create all database tables:

```bash
php artisan migrate
```

If you want to also seed the database with initial data (including an admin user):

```bash
php artisan migrate --seed
```

### 4. Start the Development Server

You can start the Laravel development server in two ways:

**Option A: Using the dev script**
```bash
bash dev-server.sh
```

**Option B: Manual start**

Open two terminal windows:

Terminal 1 - Backend server:
```bash
php artisan serve
```

Terminal 2 - Frontend dev server (optional, only for development):
```bash
cmd /c "npm run dev"
```

The application will be available at: **http://localhost:8000**

## 📋 Default Admin Credentials (after seeding)

If you run the seeder, you'll get a default admin account:
- **Email:** admin@example.com
- **Password:** password

**Important:** Change this password after first login!

## 🎯 Quick Commands Reference

```bash
# Run migrations
php artisan migrate

# Run migrations with seeders
php artisan migrate --seed

# Start development server
php artisan serve

# Build frontend assets for production
cmd /c "npm run build"

# Run frontend in development mode
cmd /c "npm run dev"

# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 🔍 Troubleshooting

### Can't connect to database
- Verify your Supabase credentials in `.env`
- Make sure your IP is allowed in Supabase (Settings → Database → Connection Pooling)
- Check if Supabase project is active

### npm commands not working in PowerShell
- Use `cmd /c "npm <command>"` instead
- Or enable PowerShell script execution: `Set-ExecutionPolicy RemoteSigned -Scope CurrentUser`

### PHP errors
- Make sure you're using PHP 8.2 or higher
- Run `composer install` again if needed

## 📚 Additional Resources

- See [README.md](README.md) for detailed feature documentation
- Check [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) for development tips
- Review [QUICK_REFERENCE.md](QUICK_REFERENCE.md) for common tasks

---

**Ready to proceed?** Update your `.env` file with Supabase credentials and run the migrations!
