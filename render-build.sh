#!/usr/bin/env bash
set -e

echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Installing NPM dependencies..."
npm ci

echo "Building frontend assets..."
npm run build

echo "Running database migrations..."
php artisan migrate --force

echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Setting storage permissions..."
chmod -R 775 storage bootstrap/cache

echo "Build complete!"
