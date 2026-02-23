# Migration from Supabase to XAMPP MySQL - Step by Step

## ⚠️ IMPORTANT: PHP Version Requirement

**Your Laravel application requires PHP 8.2 or higher!**

Check your current PHP version:
```cmd
C:\xampp\php\php.exe -v
```

If you see PHP 8.1.x, follow the **PHP Upgrade Guide** below before continuing with other steps.

---

## ✅ What's Already Done
- ✓ `.env` file has been created with MySQL configuration
- ✓ `composer.phar` downloaded to project directory
- ✓ Your code is already compatible with MySQL (the RLS middleware auto-skips for MySQL)

## 🔧 Steps to Complete the Migration

### Step 0: Upgrade PHP to 8.2+ (If Needed)

**Check PHP Version:**
```cmd
C:\xampp\php\php.exe -v
```

If version is below 8.2, follow these steps:

1. **Download PHP 8.3:**
   - Visit: https://windows.php.net/download/
   - Download: **PHP 8.3.x VS16 x64 Thread Safe** (ZIP file)

2. **Backup Current PHP:**
   ```cmd
   cd C:\xampp
   rename php php_old
   ```

3. **Extract New PHP:**
   - Right-click the downloaded ZIP file (e.g., `php-8.3.29-Win32-vs16-x64.zip`)
   - Click "Extract All..." or use 7-Zip/WinRAR
   - Extract to a temporary location (e.g., your Downloads folder)
   - You'll get a folder named `php-8.3.29-Win32-vs16-x64`
   - Rename that folder to just `php`
   - Move the `php` folder to `C:\xampp\` (so it becomes `C:\xampp\php`)
   
   **OR using Command Line:**
   ```cmd
   cd C:\xampp
   powershell Expand-Archive "C:\Users\YourUsername\Downloads\php-8.3.29-Win32-vs16-x64.zip" -DestinationPath "C:\xampp\php-temp"
   move php-temp\php-8.3.29-Win32-vs16-x64 php
   rmdir php-temp
   ```

4. **Copy Configuration from Old PHP:**
   ```cmd
   copy C:\xampp\php_old\php.ini C:\xampp\php\php.ini
   ```
   
   **Note:** Don't copy the `ext` folder - the new PHP already has the extensions.

5. **Enable Required Extensions:**
   
   Edit `C:\xampp\php\php.ini` and ensure these lines are uncommented (remove `;`):
   ```ini
   extension=mysqli
   extension=pdo_mysql
   extension=mbstring
   extension=openssl
   extension=curl
   extension=fileinfo
   extension=gd
   extension=zip
   ```

6. **Restart Apache** in XAMPP Control Panel

7. **Verify:**
   ```cmd
   C:\xampp\php\php.exe -v
   ```
   Should show PHP 8.3.x

---

### Step 1: Add PHP and Composer to PATH (Recommended)

Add these to your Windows PATH environment variable:
- `C:\xampp\php`
- `C:\composer` (or wherever composer.phar is located)

**OR** use full paths in commands below.

---

### Step 2: Create MySQL Database

**Option A: Using phpMyAdmin** (Easiest)
1. Open `http://localhost/phpmyadmin`
2. Click **New** in the left sidebar
3. Database name: `money_management`
4. Collation: `utf8mb4_unicode_ci`
5. Click **Create**

**Option B: Using MySQL Command Line**
```cmd
C:\xampp\mysql\bin\mysql.exe -u root -p
```
Then run:
```sql
CREATE DATABASE money_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SHOW DATABASES;
EXIT;
```

---

### Step 3: Configure .env File

The `.env` file is already created. Update these values if needed:

```env
APP_NAME="Money Management"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-server-ip-or-domain

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=money_management
DB_USERNAME=root
DB_PASSWORD=         # Add your MySQL root password if you set one
```

---

### Step 4: Install Dependencies

From the project directory:

```cmd
cd C:\xampp\htdocs\money-management

# Install PHP dependencies using the downloaded composer.phar
C:\xampp\php\php.exe composer.phar install --optimize-autoloader --no-dev

# Install Node.js dependencies
npm install

# Build frontend assets
npm run build
```

**Note:** If npm is not installed, download Node.js from https://nodejs.org/

---

### Step 5: Generate Application Key

```cmd
php artisan key:generate
```

**Without PATH:**
```cmd
C:\xampp\php\php.exe artisan key:generate
```

---

### Step 6: Run Database Migrations

This creates all tables in your MySQL database:

```cmd
php artisan migrate
```

**Without PATH:**
```cmd
C:\xampp\php\php.exe artisan migrate
```

You should see output like:
```
Migration table created successfully.
Migrating: 0001_01_01_000000_create_users_table
Migrated:  0001_01_01_000000_create_users_table
...
```

---

### Step 7: Set Proper Permissions

```cmd
# Storage and cache directories need write permissions
icacls storage /grant "Users:(OI)(CI)F" /T
icacls bootstrap\cache /grant "Users:(OI)(CI)F" /T
```

---

### Step 8: Configure Apache (Optional)

If you want cleaner URLs without `/public`, create/edit `.htaccess` in your project root:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**OR** point your Apache DocumentRoot to: `C:\xampp\htdocs\money-management\public`

---

### Step 9: Test the Application

1. Start XAMPP Apache and MySQL
2. Visit: `http://localhost/money-management/public`
3. You should see the login page

---

## 🔐 Create First User (if needed)

If migrating fresh without data:

```cmd
php artisan tinker
```

Then run:
```php
$user = new App\Models\User();
$user->name = 'Admin User';
$user->email = 'admin@example.com';
$user->password = bcrypt('your-password');
$user->role = 'admin';
$user->permissions = json_encode(['manage_users', 'manage_companies', 'manage_debtors']);
$user->save();
exit
```

---

## 📊 Migrating Data from Supabase (If you have existing data)

### Option 1: Export/Import via SQL

1. **Export from Supabase:**
   - Go to Supabase Dashboard → SQL Editor
   - For each table, run: `SELECT * FROM table_name;`
   - Export as CSV

2. **Import to MySQL:**
   - Open phpMyAdmin
   - Select `money_management` database
   - Go to Import tab
   - Upload each CSV file to corresponding table

### Option 2: Use Laravel Seeder

Create a seeder that reads from your Supabase export and inserts into MySQL.

---

## ⚠️ Important Notes

1. **Row Level Security (RLS)**: Only works with PostgreSQL/Supabase. With MySQL, you rely on Laravel's built-in authorization:
   - Policies in `app/Policies/`
   - Gates in `app/Providers/AuthServiceProvider.php`
   - Middleware checks

2. **Session Storage**: Currently set to `file` in `.env`. For better performance in production, consider:
   - `SESSION_DRIVER=database` (run `php artisan session:table && php artisan migrate` first)
   - `SESSION_DRIVER=redis` (if Redis is available)

3. **Cache**: Currently set to `file`. For production, consider `database` or `redis`.

4. **Backups**: Set up regular MySQL backups:
   ```cmd
   C:\xampp\mysql\bin\mysqldump.exe -u root -p money_management > backup.sql
   ```

---

## 🐛 Troubleshooting

### Database Connection Errors
- Check XAMPP MySQL is running
- Verify credentials in `.env`
- Test connection: `C:\xampp\mysql\bin\mysql.exe -u root -p money_management`

### "Class not found" Errors
- Run: `composer dump-autoload`

### 404 Errors
- Check `.htaccess` exists in `/public`
- Enable `mod_rewrite` in Apache config

### Permission Errors
- Run the `icacls` commands from Step 7
- Make sure XAMPP has write access to storage folders

---

## 📚 Additional Resources

- Original Supabase docs: `docs/RLS_IMPLEMENTATION.md`
- XAMPP deployment guide: `docs/XAMPP_MYSQL_DEPLOYMENT.md`
- Laravel documentation: https://laravel.com/docs

---

## ✨ Next Steps After Migration

1. Test all functionality (login, CRUD operations, etc.)
2. Set up automated backups
3. Configure SSL/HTTPS for production
4. Set `APP_DEBUG=false` in production
5. Monitor logs in `storage/logs/`

---

**Your migration is almost complete!** Follow the steps above, especially Steps 4-6 to get your application running on XAMPP MySQL.
