#!/bin/bash

echo "🧹 FINAL DEEP CLEANUP - Removing Backup/Test Files"
echo "====================================================="
echo ""

# Create backup directory first
mkdir -p .cleanup_backup

echo "📦 Creating safety backup..."
cp resources/views/users/edit-backup.blade.php .cleanup_backup/ 2>/dev/null
cp resources/views/users/index-test.blade.php .cleanup_backup/ 2>/dev/null
cp resources/views/debtors/index_new.blade.php .cleanup_backup/ 2>/dev/null
cp resources/views/sessions/admin.blade.php .cleanup_backup/ 2>/dev/null

echo ""
echo "🗑️  Removing backup/test view files..."

# Remove user backup/test files
if [ -f "resources/views/users/edit-backup.blade.php" ]; then
    rm -f resources/views/users/edit-backup.blade.php
    echo "   ✅ Removed: users/edit-backup.blade.php"
fi

if [ -f "resources/views/users/index-test.blade.php" ]; then
    rm -f resources/views/users/index-test.blade.php
    echo "   ✅ Removed: users/index-test.blade.php"
fi

# Remove debtor backup files
if [ -f "resources/views/debtors/index_new.blade.php" ]; then
    rm -f resources/views/debtors/index_new.blade.php
    echo "   ✅ Removed: debtors/index_new.blade.php"
fi

# Remove unused session admin view
if [ -f "resources/views/sessions/admin.blade.php" ]; then
    rm -f resources/views/sessions/admin.blade.php
    echo "   ✅ Removed: sessions/admin.blade.php (unused, using sessions/index.blade.php)"
fi

echo ""
echo "🗑️  Removing shell scripts (keeping for reference)..."
if [ -f "cleanup_unused_files.sh" ]; then
    mv cleanup_unused_files.sh .cleanup_backup/
    echo "   ✅ Moved: cleanup_unused_files.sh to .cleanup_backup/"
fi

if [ -f "comprehensive_test.sh" ]; then
    mv comprehensive_test.sh .cleanup_backup/
    echo "   ✅ Moved: comprehensive_test.sh to .cleanup_backup/"
fi

echo ""
echo "📊 Final File Count..."
VIEW_COUNT=$(find resources/views -name "*.blade.php" | wc -l | tr -d ' ')
echo "   📄 Blade templates: $VIEW_COUNT"

CONTROLLER_COUNT=$(find app/Http/Controllers -name "*.php" ! -name "Controller.php" | wc -l | tr -d ' ')
echo "   🎛️  Controllers: $CONTROLLER_COUNT"

DOC_COUNT=$(ls *.md 2>/dev/null | wc -l | tr -d ' ')
echo "   📖 Documentation files: $DOC_COUNT"

echo ""
echo "✅ Final cleanup complete!"
echo ""
echo "Safety backup created in: .cleanup_backup/"
echo "You can delete .cleanup_backup/ folder if everything works fine."
echo ""
