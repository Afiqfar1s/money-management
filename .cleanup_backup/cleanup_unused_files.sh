#!/bin/bash

echo "🧹 Starting cleanup of unused files..."
echo ""

# Remove empty markdown documentation files
echo "📄 Removing empty documentation files..."
rm -f ADMIN_SESSION_GUIDE.md
rm -f CLEANUP_EXECUTIVE_SUMMARY.md
rm -f CLEANUP_REPORT.md
rm -f SESSION_MANAGEMENT.md
rm -f UI_SIMPLIFICATION_SUMMARY.md
rm -f USER_MANAGEMENT_GUIDE.md

# Remove unused welcome page (not referenced in routes)
echo "🗑️  Removing unused welcome.blade.php..."
rm -f resources/views/welcome.blade.php

# Remove unused navigation.blade.php (not included in app.blade.php)
echo "🗑️  Removing unused navigation.blade.php..."
rm -f resources/views/layouts/navigation.blade.php

echo ""
echo "✅ Cleanup complete!"
echo ""
echo "Removed files:"
echo "  - 6 empty markdown documentation files"
echo "  - resources/views/welcome.blade.php"
echo "  - resources/views/layouts/navigation.blade.php"
echo ""
