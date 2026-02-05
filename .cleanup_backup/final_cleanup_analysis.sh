#!/bin/bash

echo "📊 FINAL CLEANUP ANALYSIS"
echo "========================="
echo ""

echo "1️⃣ FILES TO REMOVE:"
echo "-------------------"

echo ""
echo "Junk Files:"
find . -name ".DS_Store" -o -name "*.swp" -o -name "*.swo" 2>/dev/null | while read file; do
    echo "   ❌ $file"
done

echo ""
echo "Audit Scripts (can be archived):"
ls -1 *.sh 2>/dev/null | grep -E "audit|unused_imports_check|clear-cache" | while read file; do
    echo "   📦 $file"
done

echo ""
echo ""
echo "2️⃣ CHECKING FOR REDUNDANT VIEWS:"
echo "---------------------------------"

# Check for similar named views that might be duplicates
echo ""
echo "Checking for potential duplicate views..."

find resources/views -name "*.blade.php" | sort | while read file; do
    basename "$file"
done | awk -F'.' '{print $1}' | sort | uniq -d | while read base; do
    echo "⚠️  Multiple files with base name: $base"
    find resources/views -name "${base}*.blade.php"
done

echo ""
echo ""
echo "3️⃣ CHECKING MIDDLEWARE:"
echo "-----------------------"

MIDDLEWARE_COUNT=$(ls -1 app/Http/Middleware/*.php 2>/dev/null | wc -l | tr -d ' ')
echo "Total middleware files: $MIDDLEWARE_COUNT"

echo ""
echo "Registered middleware:"
grep -E "->middleware\(|middlewareGroups|routeMiddleware" app/Http/Kernel.php 2>/dev/null | head -20 || echo "Using bootstrap/app.php (Laravel 11+)"

echo ""
echo ""
echo "4️⃣ CHECKING REQUESTS:"
echo "---------------------"

REQUEST_COUNT=$(find app/Http/Requests -name "*.php" 2>/dev/null | wc -l | tr -d ' ')
echo "Total request files: $REQUEST_COUNT"

if [ "$REQUEST_COUNT" -gt 0 ]; then
    echo ""
    echo "Request files:"
    find app/Http/Requests -name "*.php" -exec basename {} \; | sort
fi

echo ""
echo ""
echo "5️⃣ CHECKING POLICIES:"
echo "---------------------"

POLICY_COUNT=$(find app/Policies -name "*.php" 2>/dev/null | wc -l | tr -d ' ')
echo "Total policy files: $POLICY_COUNT"

if [ "$POLICY_COUNT" -gt 0 ]; then
    echo ""
    echo "Policy files:"
    find app/Policies -name "*.php" -exec basename {} \; | sort
fi

echo ""
echo ""
echo "6️⃣ DATABASE STRUCTURE:"
echo "----------------------"

echo "Migration files: $(ls -1 database/migrations/*.php 2>/dev/null | wc -l | tr -d ' ')"
echo "Seeder files: $(ls -1 database/seeders/*.php 2>/dev/null | wc -l | tr -d ' ')"
echo "Factory files: $(ls -1 database/factories/*.php 2>/dev/null | wc -l | tr -d ' ')"

echo ""
echo ""
echo "7️⃣ CHECKING FOR EMPTY DIRECTORIES:"
echo "-----------------------------------"

find . -type d -empty 2>/dev/null | grep -v "node_modules\|vendor\|\.git" | while read dir; do
    echo "   📁 $dir (empty)"
done

echo ""
echo ""
echo "✅ Analysis complete!"
