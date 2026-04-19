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

    // 3. Storage Link Fix via Artisan
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $messages[] = "<span style='color:green;'>✓ Storage Symlink verified/created successfully via Artisan.</span>";
    } catch (\Exception $e) {
        $messages[] = "<span style='color:orange;'>⚠ Artisan storage:link message: " . $e->getMessage() . "</span>";
    }

} catch (\Exception $e) {
    $messages[] = "<span style='color:red;'>✗ Failed to bootstrap Laravel framework. Falling back to native PHP operations. Error: " . $e->getMessage() . "</span>";
}

// 4. Native PHP Fallback for the Storage Link (Images not showing fix)
// This handles shared hosting environments where Artisan might fail
$target = __DIR__.'/../storage/app/public';
$link = __DIR__.'/storage';

if (is_link($link)) {
    $messages[] = "<span style='color:blue;'>ℹ Fallback check: The 'public/storage' symlink already exists and seems to be a valid link.</span>";
} else if (file_exists($link) && is_dir($link)) {
    $backupName = $link . '_backup_' . time();
    if (@rename($link, $backupName)) {
        $messages[] = "<span style='color:green;'>✓ Auto-Fixed: Renamed conflicting 'public/storage' folder to 'storage_backup_" . time() . "'.</span>";
        try {
            if (symlink($target, $link)) {
                $messages[] = "<span style='color:green;'>✓ Fallback check: Storage Symlink created manually using PHP symlink() function.</span>";
            } else {
                $messages[] = "<span style='color:red;'>✗ Fallback check: PHP symlink() returned false after directory rename.</span>";
            }
        } catch (\Exception $e) {
            $messages[] = "<span style='color:red;'>✗ Fallback check: Failed to create manual symlink after rename: " . $e->getMessage() . "</span>";
        }
    } else {
        $messages[] = "<span style='color:red;'><strong>🚨 CRITICAL ISSUE DETECTED:</strong> 'public/storage' exists, but it's a regular directory, NOT a symlink. We tried to auto-rename it but failed due to server permissions.<br><br><strong>FIX:</strong> Go to your File Manager (cPanel/Plesk), navigate to the 'public' directory, and RENAME or DELETE the 'storage' folder. Then refresh this page to let this script create the proper symlink.</span>";
    }
} else {
    // Attempt standard PHP symlink
    try {
        if (symlink($target, $link)) {
            $messages[] = "<span style='color:green;'>✓ Fallback check: Storage Symlink created manually using PHP symlink() function.</span>";
        } else {
            $messages[] = "<span style='color:red;'>✗ Fallback check: PHP symlink() returned false. Your server might disable the symlink function for security.</span>";
        }
    } catch (\Exception $e) {
        $messages[] = "<span style='color:red;'>✗ Fallback check: Failed to create manual symlink: " . $e->getMessage() . "</span>";
    }
}

// 5. Native PHP Fallback for cached files
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
    <title>Laravel System Fix Tool</title>
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
        <h1>Laravel Cache & Storage Symlink Utility</h1>
        
        <div class="warning">
            <strong>Security Warning:</strong> Once everything is working and your images are showing, please <strong>delete</strong> this file (<code><?php echo basename(__FILE__); ?></code>) from your server to prevent unauthorized users from clearing your caches.
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
