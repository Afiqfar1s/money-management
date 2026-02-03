#!/bin/bash

echo "🔍 Checking for Unused Imports in Controllers..."
echo "================================================"

for file in app/Http/Controllers/*.php; do
    if [ -f "$file" ]; then
        filename=$(basename "$file")
        
        # Extract all use statements
        uses=$(grep "^use " "$file" | sed 's/use //;s/;//' | awk -F'\\' '{print $NF}')
        
        has_unused=false
        
        for class in $uses; do
            # Skip common Laravel classes that might be used indirectly
            if [[ "$class" =~ ^(Request|Validator|Auth|DB|Hash|Storage|Mail|Notification|Event|Log|Cache|Config|View|Redirect|Session|Route|Schema|Artisan|Gate|Policy)$ ]]; then
                continue
            fi
            
            # Check if class is actually used in the file
            usage_count=$(grep -c "\b$class\b" "$file" | tail -1)
            
            # If only found once (the import itself), it's unused
            if [ "$usage_count" -eq 1 ]; then
                if [ "$has_unused" = false ]; then
                    echo ""
                    echo "⚠️  $filename"
                    has_unused=true
                fi
                echo "   - Possibly unused: $class"
            fi
        done
    fi
done

echo ""
echo ""
echo "🔍 Checking for Commented Code Blocks..."
echo "========================================"

for file in app/Http/Controllers/*.php; do
    if [ -f "$file" ]; then
        filename=$(basename "$file")
        
        # Find large blocks of commented code (5+ consecutive comment lines)
        awk '
        /^\s*\/\// { 
            count++
            if (count == 1) line = NR
        }
        !/^\s*\/\// { 
            if (count >= 5) {
                if (!printed) {
                    print "\n⚠️  " FILENAME
                    printed = 1
                }
                print "   - Lines " line "-" (NR-1) ": " count " commented lines"
            }
            count = 0
        }
        END {
            if (count >= 5) {
                if (!printed) print "\n⚠️  " FILENAME
                print "   - Lines " line "-" NR ": " count " commented lines"
            }
        }
        ' "$file" | sed "s|$file|$filename|"
    fi
done

echo ""
echo "✅ Check complete!"
