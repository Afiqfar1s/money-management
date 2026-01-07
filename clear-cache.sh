#!/bin/bash

# Quick cache clear script
# Usage: ./clear-cache.sh

echo "🧹 Clearing all Laravel caches..."

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo ""
echo "✅ All caches cleared!"
echo ""
echo "📊 Status:"
echo "   ✓ Configuration cache: Cleared"
echo "   ✓ Route cache: Cleared"
echo "   ✓ View cache: Cleared"
echo "   ✓ Application cache: Cleared"
echo ""
echo "🚀 You can now run: php artisan serve"
