#!/usr/bin/env bash
# Render.com start script for Laravel

echo "🚀 Starting Laravel application..."
echo "📍 Port: $PORT"

# Start PHP built-in server
# Render provides PORT environment variable
php artisan serve --host=0.0.0.0 --port=$PORT
