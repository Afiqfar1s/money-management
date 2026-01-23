#!/bin/bash

echo "🔍 COMPREHENSIVE PROJECT AUDIT"
echo "================================"
echo ""

echo "1️⃣ CHECKING FOR UNUSED CONTROLLERS..."
echo "----------------------------------------"
CONTROLLERS=$(find app/Http/Controllers -type f -name "*.php" ! -name "Controller.php")
for controller in $CONTROLLERS; do
    BASENAME=$(basename "$controller" .php)
    COUNT=$(grep -r "$BASENAME" routes/ app/Http/ resources/views/ 2>/dev/null | grep -v "Binary" | wc -l | tr -d ' ')
    if [ "$COUNT" -lt 2 ]; then
        echo "⚠️  Potentially unused: $controller (found $COUNT references)"
    else
        echo "✅ $BASENAME ($COUNT references)"
    fi
done

echo ""
echo "2️⃣ CHECKING FOR UNUSED VIEWS..."
echo "----------------------------------------"
# Check for views that might not be referenced
VIEWS=$(find resources/views -name "*.blade.php" -type f)
for view in $VIEWS; do
    VIEWNAME=$(echo "$view" | sed 's/resources\/views\///' | sed 's/\.blade\.php$//' | sed 's/\//\./g')
    # Check if view is referenced in controllers or routes
    if echo "$view" | grep -q "components/"; then
        echo "✅ Component: $VIEWNAME"
    elif echo "$view" | grep -q "layouts/"; then
        echo "✅ Layout: $VIEWNAME"
    elif echo "$view" | grep -q "auth/"; then
        echo "✅ Auth: $VIEWNAME"
    else
        COUNT=$(grep -r "view.*$VIEWNAME\|@extends.*$VIEWNAME\|@include.*$VIEWNAME" app/ routes/ resources/views/ 2>/dev/null | wc -l | tr -d ' ')
        if [ "$COUNT" -gt 0 ]; then
            echo "✅ $VIEWNAME"
        else
            echo "⚠️  Check: $view"
        fi
    fi
done

echo ""
echo "3️⃣ CHECKING FOR UNUSED ROUTES..."
echo "----------------------------------------"
php artisan route:list 2>/dev/null | grep -E "GET|POST|PUT|DELETE" | head -20

echo ""
echo "4️⃣ CHECKING FOR DUPLICATE FILES..."
echo "----------------------------------------"
# Check for common duplicate file patterns
find . -name "*copy*" -o -name "*backup*" -o -name "*old*" -o -name "*tmp*" 2>/dev/null | grep -v node_modules | grep -v vendor

echo ""
echo "5️⃣ CHECKING FOR LARGE UNNECESSARY FILES..."
echo "----------------------------------------"
find . -type f -size +5M 2>/dev/null | grep -v node_modules | grep -v vendor | grep -v .git

echo ""
echo "6️⃣ CHECKING DOCUMENTATION FILES..."
echo "----------------------------------------"
ls -lh *.md 2>/dev/null | awk '{print $5, $9}'

echo ""
echo "7️⃣ CHECKING FOR UNUSED JAVASCRIPT/CSS..."
echo "----------------------------------------"
find resources/js resources/css -type f 2>/dev/null | while read file; do
    echo "📄 $file"
done

echo ""
echo "8️⃣ CHECKING PUBLIC ASSETS..."
echo "----------------------------------------"
find public -type f ! -path "*/build/*" ! -path "*/storage/*" 2>/dev/null

echo ""
echo "9️⃣ CHECKING FOR TEST FILES..."
echo "----------------------------------------"
find tests -type f -name "*.php" 2>/dev/null

echo ""
echo "🔟 CHECKING CONFIG FILES..."
echo "----------------------------------------"
ls -1 config/*.php | wc -l | xargs echo "Config files:"

echo ""
echo "✅ Audit complete!"
