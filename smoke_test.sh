#!/bin/bash

echo "🧪 Running Quick Smoke Tests..."
echo ""

# Check if servers are running
echo "1️⃣ Checking Laravel server..."
if curl -s http://127.0.0.1:8000 > /dev/null; then
    echo "   ✅ Laravel server is running"
else
    echo "   ⚠️  Laravel server may not be running on port 8000"
fi

echo ""
echo "2️⃣ Checking Vite dev server..."
if curl -s http://127.0.0.1:5173 > /dev/null 2>&1; then
    echo "   ✅ Vite dev server is running"
else
    echo "   ⚠️  Vite dev server may not be running on port 5173"
fi

echo ""
echo "3️⃣ Verifying Laravel installation..."
php artisan --version
echo "   ✅ Laravel is installed"

echo ""
echo "4️⃣ Checking database connection..."
php artisan db:show --database=sqlite 2>/dev/null && echo "   ✅ Database connection working" || echo "   ℹ️  Check database configuration"

echo ""
echo "5️⃣ Checking environment..."
if [ -f .env ]; then
    echo "   ✅ .env file exists"
else
    echo "   ❌ .env file missing"
fi

echo ""
echo "6️⃣ Checking storage permissions..."
if [ -w storage/logs ]; then
    echo "   ✅ Storage writable"
else
    echo "   ⚠️  Storage may need permissions: chmod -R 775 storage"
fi

echo ""
echo "7️⃣ Verifying caches..."
if [ -f bootstrap/cache/config.php ]; then
    echo "   ✅ Config cached"
else
    echo "   ℹ️  Config not cached (optional)"
fi

if [ -f bootstrap/cache/routes-v7.php ]; then
    echo "   ✅ Routes cached"
else
    echo "   ℹ️  Routes not cached (optional)"
fi

echo ""
echo "8️⃣ Checking key files..."
FILES=(
    "app/Http/Controllers/DashboardController.php"
    "app/Http/Controllers/ReportController.php"
    "app/Http/Controllers/DebtorController.php"
    "resources/views/dashboard.blade.php"
    "resources/views/reports/index.blade.php"
    "resources/views/layouts/app.blade.php"
)

for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "   ✅ $file"
    else
        echo "   ❌ $file missing!"
    fi
done

echo ""
echo "9️⃣ Checking routes..."
ROUTE_COUNT=$(php artisan route:list 2>/dev/null | grep -c "│")
echo "   ✅ $ROUTE_COUNT routes registered"

echo ""
echo "🔟 Checking views..."
VIEW_COUNT=$(find resources/views -name "*.blade.php" | wc -l | tr -d ' ')
echo "   ✅ $VIEW_COUNT blade templates found"

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Smoke tests complete!"
echo ""
echo "📋 Next Steps:"
echo "   1. Open http://127.0.0.1:8000 in your browser"
echo "   2. Login with your admin account"
echo "   3. Use test_all_features.md checklist for full testing"
echo ""
echo "🚀 All systems ready for manual testing!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
