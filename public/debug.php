<?php
// Quick debug script to check Laravel setup
echo "<h1>Laravel Debug Info</h1>";

// Check if we can access Laravel
$laravelPath = __DIR__ . '/../bootstrap/app.php';
if (file_exists($laravelPath)) {
    echo "✅ Laravel bootstrap found<br>";
    
    try {
        $app = require_once $laravelPath;
        echo "✅ Laravel loaded<br>";
        
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        echo "✅ Kernel created<br>";
        
        // Check environment
        echo "<br><strong>Environment:</strong> " . $app->environment() . "<br>";
        
        // Check database connection
        try {
            $pdo = new PDO(
                'pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD'),
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo "✅ Database connection successful<br>";
        } catch (Exception $e) {
            echo "❌ Database error: " . $e->getMessage() . "<br>";
        }
        
        // Check APP_KEY
        $appKey = getenv('APP_KEY');
        if ($appKey) {
            echo "✅ APP_KEY is set<br>";
        } else {
            echo "❌ APP_KEY is missing<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Error loading Laravel: " . $e->getMessage() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "❌ Laravel bootstrap not found at: $laravelPath<br>";
}

echo "<br><strong>PHP Version:</strong> " . phpversion() . "<br>";
echo "<strong>Extensions:</strong> " . implode(', ', get_loaded_extensions()) . "<br>";
