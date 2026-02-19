# XAMPP MySQL Deployment Guide

This guide helps you migrate from Supabase (PostgreSQL) to local MySQL with XAMPP.

## Prerequisites

- XAMPP installed with Apache + MySQL running
- PHP 8.2+ (check with `php -v`)
- Composer installed
- Node.js & npm installed

## Step 1: Export Data from Supabase

Go to **Supabase Dashboard** → **SQL Editor** and export each table:

```sql
-- Run each query and click Export → CSV

SELECT * FROM users;
-- Save as: users.csv

SELECT * FROM companies;
-- Save as: companies.csv

SELECT * FROM company_user;
-- Save as: company_user.csv

SELECT * FROM debtors;
-- Save as: debtors.csv

SELECT * FROM payments;
-- Save as: payments.csv

SELECT * FROM balance_adjustments;
-- Save as: balance_adjustments.csv
```

## Step 2: Create MySQL Database

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click **New** in the left sidebar
3. Database name: `money_management`
4. Collation: `utf8mb4_unicode_ci`
5. Click **Create**

## Step 3: Copy Project Files

### Windows:
```cmd
xcopy /E /I "C:\path\to\money-management" "C:\xampp\htdocs\money-management"
```

### Linux:
```bash
cp -r /path/to/money-management /opt/lampp/htdocs/money-management
```

## Step 4: Configure Environment

```bash
cd /path/to/money-management

# Copy MySQL example environment
cp .env.mysql.example .env

# Generate new app key
php artisan key:generate
```

Edit `.env` and update:
```env
APP_URL=http://localhost/money-management/public
# or http://your-server-ip/money-management/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=money_management
DB_USERNAME=root
DB_PASSWORD=
```

## Step 5: Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install and build frontend assets
npm install
npm run build
```

## Step 6: Run Migrations

This creates all the tables in MySQL:

```bash
php artisan migrate
```

## Step 7: Import Data from CSV

### Option A: Using phpMyAdmin (Easiest)

For each CSV file:
1. Open phpMyAdmin → Select `money_management` database
2. Click the table name (e.g., `users`)
3. Click **Import** tab
4. Choose the corresponding CSV file
5. Format: CSV
6. Check "The first line contains column names"
7. Click **Go**

**Import order (important for foreign keys):**
1. `users` (first - no dependencies)
2. `companies` (no dependencies)
3. `company_user` (depends on users, companies)
4. `debtors` (depends on users, companies)
5. `payments` (depends on debtors)
6. `balance_adjustments` (depends on debtors)

### Option B: Using Command Line

```bash
# Connect to MySQL
mysql -u root -p money_management

# Import each CSV (adjust paths)
LOAD DATA LOCAL INFILE '/path/to/users.csv'
INTO TABLE users
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
```

## Step 8: Fix Auto-Increment Sequences

After importing, reset auto-increment to avoid conflicts:

```sql
-- Run in phpMyAdmin SQL tab
ALTER TABLE users AUTO_INCREMENT = 1000;
ALTER TABLE companies AUTO_INCREMENT = 1000;
ALTER TABLE debtors AUTO_INCREMENT = 1000;
ALTER TABLE payments AUTO_INCREMENT = 1000;
ALTER TABLE balance_adjustments AUTO_INCREMENT = 1000;
ALTER TABLE company_user AUTO_INCREMENT = 1000;
```

## Step 9: Configure Apache Virtual Host (Optional but Recommended)

### Windows: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
### Linux: `/opt/lampp/etc/extra/httpd-vhosts.conf`

```apache
<VirtualHost *:80>
    ServerName money.local
    DocumentRoot "C:/xampp/htdocs/money-management/public"
    
    <Directory "C:/xampp/htdocs/money-management/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/money-error.log"
    CustomLog "logs/money-access.log" common
</VirtualHost>
```

Add to hosts file:
- Windows: `C:\Windows\System32\drivers\etc\hosts`
- Linux: `/etc/hosts`

```
127.0.0.1 money.local
```

Restart Apache.

## Step 10: Set Permissions (Linux only)

```bash
cd /opt/lampp/htdocs/money-management
chmod -R 775 storage bootstrap/cache
chown -R daemon:daemon storage bootstrap/cache
```

## Step 11: Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## Step 12: Test the Application

Visit: http://localhost/money-management/public
or http://money.local (if you set up virtual host)

Login with your existing credentials.

---

## Troubleshooting

### "SQLSTATE[HY000]: General error: 1364 Field 'xxx' doesn't have a default value"
- MySQL strict mode issue
- Edit `config/database.php` and set `'strict' => false` for mysql connection

### "Class not found" errors
```bash
composer dump-autoload
php artisan clear-compiled
```

### Blank page / 500 error
```bash
php artisan config:clear
php artisan cache:clear
tail -f storage/logs/laravel.log
```

### Permission denied errors (Linux)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Data Type Differences: PostgreSQL vs MySQL

| PostgreSQL | MySQL Equivalent |
|------------|------------------|
| `SERIAL` | `INT AUTO_INCREMENT` |
| `BIGSERIAL` | `BIGINT AUTO_INCREMENT` |
| `BOOLEAN` | `TINYINT(1)` |
| `TIMESTAMP` | `DATETIME` |
| `TEXT` | `TEXT` or `LONGTEXT` |
| `NUMERIC` | `DECIMAL` |
| `JSON` | `JSON` |

Laravel migrations handle these automatically!

---

## Note About RLS

The Row Level Security (RLS) feature only works with PostgreSQL/Supabase.
With MySQL, the `SetRlsContext` middleware automatically skips (no errors).
Access control is handled by Laravel's application-level authorization instead.
