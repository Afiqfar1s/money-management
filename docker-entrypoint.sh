#!/bin/bash
set -e

echo "Starting Laravel application..."

# Check if build directory exists
echo "Checking build assets..."
if [ -d "/var/www/html/public/build" ]; then
    echo "✓ Build directory found"
    ls -la /var/www/html/public/build/
    
    # Check for manifest.json
    if [ -f "/var/www/html/public/build/.vite/manifest.json" ]; then
        echo "✓ Vite manifest found"
        cat /var/www/html/public/build/.vite/manifest.json
        
        # Verify manifest is readable by www-data
        echo "Checking manifest permissions..."
        ls -la /var/www/html/public/build/.vite/manifest.json
        
        # Ensure proper permissions
        chmod 644 /var/www/html/public/build/.vite/manifest.json
        chown www-data:www-data /var/www/html/public/build/.vite/manifest.json
        echo "✓ Manifest permissions fixed"
    else
        echo "✗ Vite manifest NOT found!"
    fi
else
    echo "✗ Build directory NOT found!"
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migration failed, continuing..."

# Clear and cache config
echo "Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache config for production (SKIP config:cache to avoid Vite manifest issues)
echo "Skipping config:cache to prevent Vite manifest path issues..."
php artisan route:cache || echo "Route cache failed, continuing..."
php artisan view:cache || echo "View cache failed, continuing..."

# Fix permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Enable detailed error logging
echo "Enabling error logs..."
sed -i 's/error_reporting = .*/error_reporting = E_ALL/' /usr/local/etc/php/php.ini-production || true
sed -i 's/display_errors = .*/display_errors = On/' /usr/local/etc/php/php.ini-production || true

# Check Laravel logs for errors
echo "Checking Laravel storage logs..."
if [ -f "/var/www/html/storage/logs/laravel.log" ]; then
    echo "Recent Laravel errors:"
    tail -50 /var/www/html/storage/logs/laravel.log || true
fi

# Test database connection
echo "Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connected successfully'; } catch (Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); }" || echo "Database test failed"

# Test if Laravel can see the Vite manifest
echo "Testing if Laravel can access Vite manifest..."
php artisan tinker --execute="echo 'Manifest path: ' . public_path('build/.vite/manifest.json') . PHP_EOL; echo 'File exists: ' . (file_exists(public_path('build/.vite/manifest.json')) ? 'YES' : 'NO') . PHP_EOL; if (file_exists(public_path('build/.vite/manifest.json'))) { echo 'Readable: ' . (is_readable(public_path('build/.vite/manifest.json')) ? 'YES' : 'NO') . PHP_EOL; }" || echo "Manifest test failed"

echo "Starting Apache..."
# Start Apache in foreground
exec apache2-foreground
