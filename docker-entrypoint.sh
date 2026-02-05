#!/bin/bash
set -e

echo "Starting Laravel application..."

# Check if build directory exists
echo "Checking build assets..."
if [ -d "/var/www/html/public/build" ]; then
    echo "✓ Build directory found"
    ls -la /var/www/html/public/build/
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

# Cache config for production
php artisan config:cache || echo "Config cache failed, continuing..."
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

echo "Starting Apache..."
# Start Apache in foreground
exec apache2-foreground
