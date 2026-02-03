#!/bin/bash

echo "🔍 DEEP CODE AUDIT - File by File Analysis"
echo "=========================================="
echo ""

echo "1️⃣ ANALYZING CONTROLLERS..."
echo "----------------------------------------"

for file in app/Http/Controllers/*.php; do
    if [ -f "$file" ]; then
        echo ""
        echo "📄 $(basename "$file")"
        
        # Check for unused use statements
        USE_COUNT=$(grep -c "^use " "$file" 2>/dev/null || echo 0)
        echo "   - Use statements: $USE_COUNT"
        
        # Check for methods
        METHOD_COUNT=$(grep -c "public function\|private function\|protected function" "$file" 2>/dev/null || echo 0)
        echo "   - Methods: $METHOD_COUNT"
        
        # Check for TODO/FIXME comments
        TODO_COUNT=$(grep -c "TODO\|FIXME\|XXX\|HACK" "$file" 2>/dev/null || echo 0)
        if [ "$TODO_COUNT" -gt 0 ]; then
            echo "   ⚠️  TODO/FIXME comments: $TODO_COUNT"
        fi
        
        # Check for commented code blocks
        COMMENT_LINES=$(grep -c "^\s*//\|^\s*/\*" "$file" 2>/dev/null || echo 0)
        if [ "$COMMENT_LINES" -gt 10 ]; then
            echo "   ⚠️  Commented lines: $COMMENT_LINES (may need cleanup)"
        fi
    fi
done

echo ""
echo ""
echo "2️⃣ ANALYZING MODELS..."
echo "----------------------------------------"

for file in app/Models/*.php; do
    if [ -f "$file" ]; then
        echo ""
        echo "�� $(basename "$file")"
        
        # Check for relationships
        REL_COUNT=$(grep -c "belongsTo\|hasMany\|hasOne\|belongsToMany" "$file" 2>/dev/null || echo 0)
        echo "   - Relationships: $REL_COUNT"
        
        # Check for accessors/mutators
        ACCESSOR_COUNT=$(grep -c "get.*Attribute\|set.*Attribute" "$file" 2>/dev/null || echo 0)
        if [ "$ACCESSOR_COUNT" -gt 0 ]; then
            echo "   - Accessors/Mutators: $ACCESSOR_COUNT"
        fi
    fi
done

echo ""
echo ""
echo "3️⃣ ANALYZING ROUTES..."
echo "----------------------------------------"

if [ -f "routes/web.php" ]; then
    echo "📄 web.php"
    ROUTE_COUNT=$(grep -c "Route::" "routes/web.php" 2>/dev/null || echo 0)
    echo "   - Total routes defined: $ROUTE_COUNT"
    
    # Check for commented routes
    COMMENTED_ROUTES=$(grep -c "^\s*//.*Route::" "routes/web.php" 2>/dev/null || echo 0)
    if [ "$COMMENTED_ROUTES" -gt 0 ]; then
        echo "   ⚠️  Commented routes: $COMMENTED_ROUTES (should remove)"
    fi
fi

echo ""
echo ""
echo "4️⃣ ANALYZING BLADE TEMPLATES..."
echo "----------------------------------------"

BLADE_FILES=$(find resources/views -name "*.blade.php" | wc -l | tr -d ' ')
echo "Total blade files: $BLADE_FILES"

# Check for @todo comments in views
TODO_VIEWS=$(grep -r "@todo\|TODO\|FIXME" resources/views --include="*.blade.php" 2>/dev/null | wc -l | tr -d ' ')
if [ "$TODO_VIEWS" -gt 0 ]; then
    echo "⚠️  Views with TODO comments: $TODO_VIEWS"
fi

# Check for unused Alpine.js components
ALPINE_COUNT=$(grep -r "x-data\|x-show\|x-if" resources/views --include="*.blade.php" 2>/dev/null | wc -l | tr -d ' ')
echo "Alpine.js usage: $ALPINE_COUNT instances"

echo ""
echo ""
echo "5️⃣ ANALYZING JAVASCRIPT..."
echo "----------------------------------------"

for file in resources/js/*.js; do
    if [ -f "$file" ]; then
        echo "📄 $(basename "$file")"
        LINES=$(wc -l < "$file" | tr -d ' ')
        echo "   - Lines: $LINES"
        
        # Check for console.log
        CONSOLE_COUNT=$(grep -c "console.log\|console.error\|console.warn" "$file" 2>/dev/null || echo 0)
        if [ "$CONSOLE_COUNT" -gt 0 ]; then
            echo "   ⚠️  Console statements: $CONSOLE_COUNT (should remove for production)"
        fi
    fi
done

echo ""
echo ""
echo "6️⃣ ANALYZING CSS..."
echo "----------------------------------------"

for file in resources/css/*.css; do
    if [ -f "$file" ]; then
        echo "�� $(basename "$file")"
        LINES=$(wc -l < "$file" | tr -d ' ')
        echo "   - Lines: $LINES"
        
        # Check for @import statements
        IMPORTS=$(grep -c "@import" "$file" 2>/dev/null || echo 0)
        if [ "$IMPORTS" -gt 0 ]; then
            echo "   - @import statements: $IMPORTS"
        fi
    fi
done

echo ""
echo ""
echo "7️⃣ CHECKING FOR UNUSED FILES..."
echo "----------------------------------------"

# Check for .DS_Store files
DS_STORE=$(find . -name ".DS_Store" 2>/dev/null | wc -l | tr -d ' ')
if [ "$DS_STORE" -gt 0 ]; then
    echo "⚠️  .DS_Store files found: $DS_STORE (should remove)"
fi

# Check for .env.backup or similar
ENV_BACKUPS=$(find . -name ".env.*" ! -name ".env.example" 2>/dev/null | wc -l | tr -d ' ')
if [ "$ENV_BACKUPS" -gt 0 ]; then
    echo "⚠️  .env backup files found: $ENV_BACKUPS"
fi

# Check for log files in root
LOG_FILES=$(find . -maxdepth 1 -name "*.log" 2>/dev/null | wc -l | tr -d ' ')
if [ "$LOG_FILES" -gt 0 ]; then
    echo "⚠️  Log files in root: $LOG_FILES"
fi

echo ""
echo ""
echo "8️⃣ CHECKING CONFIG FILES..."
echo "----------------------------------------"

CONFIG_COUNT=$(ls -1 config/*.php 2>/dev/null | wc -l | tr -d ' ')
echo "Config files: $CONFIG_COUNT"

echo ""
echo ""
echo "✅ Audit complete! Review warnings above."
