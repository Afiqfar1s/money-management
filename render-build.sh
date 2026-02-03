#!/usr/bin/env bash
# Render.com build script for Laravel
# exit on error
set -o errexit

echo "🔨 Starting Render build process..."

# Install PHP dependencies (production only, optimized)
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Generate optimized autoload files
echo "⚡ Optimizing autoloader..."
composer dump-autoload --optimize

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force --no-interaction

# Clear and cache config for better performance
echo "🚀 Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if it doesn't exist
echo "🔗 Creating storage link..."
php artisan storage:link || true

echo "✅ Build complete!"
