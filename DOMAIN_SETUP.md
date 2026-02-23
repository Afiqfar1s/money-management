# Custom Domain Setup Complete ✓

## Your Application is Now Accessible At:

```
http://moneymanagement.com
http://www.moneymanagement.com
```

## What Was Configured

1. **Virtual Host**: Created Apache virtual host configuration for `moneymanagement.com`
2. **Hosts File**: Added entries to `C:\Windows\System32\drivers\etc\hosts`
   ```
   127.0.0.1 moneymanagement.com
   127.0.0.1 www.moneymanagement.com
   ```
3. **APP_URL**: Updated `.env` file to use `http://moneymanagement.com`
4. **Laravel Cache**: Cleared configuration and application cache

## Accessing Your Application

Open your browser and navigate to:
- **http://moneymanagement.com**
- **http://moneymanagement.com/login** (Login page)

**Login Credentials:**
- Email: `admin@test.com`
- Password: `admin123`

## Configuration Files

### Virtual Host Configuration
Location: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

```apache
<VirtualHost *:80>
    ServerName moneymanagement.com
    ServerAlias www.moneymanagement.com
    DocumentRoot "C:/xampp/htdocs/money-management/public"
    
    <Directory "C:/xampp/htdocs/money-management/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/moneymanagement-error.log"
    CustomLog "logs/moneymanagement-access.log" common
</VirtualHost>
```

### Hosts File
Location: `C:\Windows\System32\drivers\etc\hosts`

```
127.0.0.1 moneymanagement.com
127.0.0.1 www.moneymanagement.com
```

## Additional Domains

To add more domains (e.g., for testing or staging):

1. **Add to hosts file:**
   ```
   127.0.0.1 staging.moneymanagement.com
   ```

2. **Create virtual host** in `httpd-vhosts.conf`:
   ```apache
   <VirtualHost *:80>
       ServerName staging.moneymanagement.com
       DocumentRoot "C:/xampp/htdocs/money-management/public"
       SetEnv APP_ENV staging
       # ... rest of config
   </VirtualHost>
   ```

3. **Restart Apache:**
   ```powershell
   C:\xampp\apache\bin\httpd.exe -k restart
   ```

## Troubleshooting

### Domain Not Working

**Clear browser cache:**
- Chrome/Edge: `Ctrl + Shift + Delete`
- Try incognito/private mode

**Verify hosts file:**
```powershell
Get-Content C:\Windows\System32\drivers\etc\hosts | Select-String "moneymanagement"
```

**Check Apache is running:**
```powershell
Get-NetTCPConnection -LocalPort 80 -ErrorAction SilentlyContinue
```

**Restart Apache:**
```powershell
C:\xampp\apache\bin\httpd.exe -k restart
```

### Still Showing localhost

Clear Laravel cache:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Check Virtual Host Configuration

Test Apache configuration:
```powershell
C:\xampp\apache\bin\httpd.exe -t
```

View virtual hosts:
```powershell
C:\xampp\apache\bin\httpd.exe -S
```

### Access Logs

**Apache Error Log:**
```powershell
Get-Content C:\xampp\apache\logs\moneymanagement-error.log -Tail 20
```

**Apache Access Log:**
```powershell
Get-Content C:\xampp\apache\logs\moneymanagement-access.log -Tail 20
```

**Laravel Log:**
```powershell
Get-Content C:\xampp\htdocs\money-management\storage\logs\laravel.log -Tail 20
```

## Reverting to localhost

If you want to switch back:

1. **Update .env:**
   ```
   APP_URL=http://localhost
   ```

2. **Clear cache:**
   ```bash
   php artisan config:clear
   ```

3. **Optional:** Comment out the virtual host in `httpd-vhosts.conf` and restart Apache

## Production Deployment

For production with a real domain:

1. Point your domain's DNS A record to your server's public IP
2. Update virtual host `ServerName` to your real domain
3. Set up SSL certificate (Let's Encrypt recommended)
4. Update `APP_URL` in `.env` to use HTTPS
5. Set `APP_ENV=production` and `APP_DEBUG=false`

---

**Setup Date**: February 20, 2026  
**Domain**: moneymanagement.com  
**Server**: Apache/2.4.58 (XAMPP on Windows)
