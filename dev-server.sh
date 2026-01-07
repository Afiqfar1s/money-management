#!/bin/bash

# Development Server Startup Script
# This script clears all caches and starts the Laravel development server

echo "🧹 Clearing all caches..."
php artisan optimize:clear

echo ""
echo "🔧 Cache status:"
echo "   ✓ Configuration cache cleared"
echo "   ✓ Route cache cleared"
echo "   ✓ View cache cleared"
echo "   ✓ Compiled cache cleared"
echo ""

echo "🚀 Starting Laravel development server..."
echo "   URL: http://127.0.0.1:8000"
echo "   Press Ctrl+C to stop"
echo ""
echo "⚠️  IMPORTANT: In development mode, DO NOT cache routes or config!"
echo ""

php artisan serve
