<?php
/**
 * Laravel System Tool - Manual Cache Clearing & Storage Symlink
 * 
 * Usage:
 * 1. Upload this file to your 'public' directory on your server.
 * 2. Visit https://your-domain.com/clear-cache.php
 * 3. IMPORTANT: Delete this file after use for security reasons.
 */

$start = microtime(true);
$messages = [];

try {
    // 1. Attempt to bootstrap Laravel for Artisan access
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    // Create the application kernel
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    // 2. Clear Caches via Artisan
    $commands = [
        'optimize:clear' => 'All Optimizations (optimize:clear)',
        'cache:clear' => 'Application Cache',
        'config:clear' => 'Configuration Cache',
        'route:clear' => 'Route Cache',
        'view:clear' => 'View Cache',
    ];

    foreach ($commands as $command => $name) {
        try {
            \Illuminate\Support\Facades\Artisan::call($command);
            $messages[] = "<span style='color:green;'>✓ $name cleared successfully via Artisan.</span>";
        } catch (\Exception $e) {
            $messages[] = "<span style='color:orange;'>⚠ Failed to clear $name using Artisan: " . $e->getMessage() . "</span>";
        }
    }
} catch (\Exception $e) {
    $messages[] = "<span style='color:red;'>✗ Failed to bootstrap Laravel framework. Falling back to native PHP operations. Error: " . $e->getMessage() . "</span>";
}

// 3. Native PHP Fallback for cached files
// This helps if Artisan crashes because of an outdated config cache
$cacheDirectories = [
    __DIR__.'/../bootstrap/cache/' => ['config.php', 'events.php', 'packages.php', 'routes.php', 'services.php'],
];

foreach ($cacheDirectories as $dir => $files) {
    foreach ($files as $file) {
        $filePath = $dir . $file;
        if (file_exists($filePath)) {
            try {
                if (unlink($filePath)) {
                     $messages[] = "<span style='color:green;'>✓ Hard Cache Deletion: Deleted $file manually.</span>";
                } else {
                     $messages[] = "<span style='color:red;'>✗ Hard Cache Deletion: Failed to delete $file manually. Check permissions.</span>";
                }
            } catch (\Exception $e) {}
        }
    }
}

$time = round(microtime(true) - $start, 4);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Optimizer</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; padding: 2rem; background: #f8fafc; color: #0f172a; line-height: 1.5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); }
        h1 { margin-top: 0; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 1rem; font-size: 1.5rem; }
        .message-list { list-style: none; padding: 0; margin: 0; }
        .message-list li { padding: 1rem 0; border-bottom: 1px solid #f1f5f9; }
        .message-list li:last-child { border-bottom: none; }
        .footer { margin-top: 2rem; font-size: 0.875rem; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 1rem;}
        .warning { background: #fffbeb; color: #b45309; padding: 1rem; border-radius: 6px; border-left: 4px solid #f59e0b; margin-bottom: 1.5rem; }
        .btn-refresh { display: inline-block; background: #2563eb; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; margin-top: 1rem; font-weight: 500; }
        .btn-refresh:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laravel Cache & Optimization Utility</h1>
        
        <div class="warning">
            <strong>Security Warning:</strong> For security reasons, please <strong>delete</strong> this file (<code><?php echo basename(__FILE__); ?></code>) from your server to prevent unauthorized users from clearing your caches once you are done.
        </div>

        <ul class="message-list">
            <?php foreach ($messages as $message): ?>
                <li><?php echo $message; ?></li>
            <?php endforeach; ?>
        </ul>

        <div>
            <a href="?run=1" class="btn-refresh">Run Again / Refresh</a>
        </div>

        <div class="footer">
            Task completed in <?php echo $time; ?> seconds.
        </div>
    </div>
</body>
</html>
