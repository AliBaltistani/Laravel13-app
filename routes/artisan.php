<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Artisan / Deployment Routes
|--------------------------------------------------------------------------
|
| These routes allow running common artisan commands via HTTP requests.
| Useful for shared hosting where SSH/terminal access is limited.
|
| Security: Protected by a secret token defined in your .env file.
| Add this to your .env:    ARTISAN_ROUTE_TOKEN=your-secret-token-here
|
| Usage:  GET /artisan/{command}?token=your-secret-token-here
|
*/

Route::prefix('artisan')->middleware('web')->group(function () {

    // ── Token gate ──────────────────────────────────────────────────
    Route::middleware([])->group(function () {

        /* ----------------------------------------------------------------
         * Helper: verify the token from query string
         * ----------------------------------------------------------------*/
        $verifyToken = function () {
            $token = config('app.artisan_route_token');

            if (empty($token) || request()->query('token') !== $token) {
                abort(403, 'Unauthorized – invalid or missing token.');
            }
        };

        /* ================================================================
         *  CACHE & CONFIG
         * ================================================================*/

        // Clear all caches (config + route + view + app)
        Route::get('/clear-all', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('optimize:clear');
            return response()->json([
                'success' => true,
                'command' => 'optimize:clear',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.clear-all');

        // Cache everything for production (config + route + view)
        Route::get('/cache-all', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('optimize');
            return response()->json([
                'success' => true,
                'command' => 'optimize',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.cache-all');

        // Config cache
        Route::get('/config-cache', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('config:cache');
            return response()->json([
                'success' => true,
                'command' => 'config:cache',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.config-cache');

        // Config clear
        Route::get('/config-clear', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('config:clear');
            return response()->json([
                'success' => true,
                'command' => 'config:clear',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.config-clear');

        // Route cache
        Route::get('/route-cache', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('route:cache');
            return response()->json([
                'success' => true,
                'command' => 'route:cache',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.route-cache');

        // Route clear
        Route::get('/route-clear', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('route:clear');
            return response()->json([
                'success' => true,
                'command' => 'route:clear',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.route-clear');

        // View cache
        Route::get('/view-cache', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('view:cache');
            return response()->json([
                'success' => true,
                'command' => 'view:cache',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.view-cache');

        // View clear
        Route::get('/view-clear', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('view:clear');
            return response()->json([
                'success' => true,
                'command' => 'view:clear',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.view-clear');

        /* ================================================================
         *  DATABASE / MIGRATIONS
         * ================================================================*/

        // Run pending migrations
        Route::get('/migrate', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('migrate', ['--force' => true]);
            return response()->json([
                'success' => true,
                'command' => 'migrate --force',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.migrate');

        // Migration status
        Route::get('/migrate-status', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('migrate:status');
            return response()->json([
                'success' => true,
                'command' => 'migrate:status',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.migrate-status');

        // Run seeders
        Route::get('/db-seed', function () use ($verifyToken) {
            ($verifyToken)();

            $seeder = request()->query('class');
            $params = ['--force' => true];
            if ($seeder) {
                $params['--class'] = $seeder;
            }

            Artisan::call('db:seed', $params);
            return response()->json([
                'success' => true,
                'command' => 'db:seed' . ($seeder ? " --class={$seeder}" : ''),
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.db-seed');

        /* ================================================================
         *  STORAGE & LINKS
         * ================================================================*/

        // Create storage symlink
        Route::get('/storage-link', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('storage:link');
            return response()->json([
                'success' => true,
                'command' => 'storage:link',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.storage-link');

        /* ================================================================
         *  QUEUE
         * ================================================================*/

        // Restart queue workers
        Route::get('/queue-restart', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('queue:restart');
            return response()->json([
                'success' => true,
                'command' => 'queue:restart',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.queue-restart');

        /* ================================================================
         *  MAINTENANCE MODE
         * ================================================================*/

        // Enable maintenance mode
        Route::get('/down', function () use ($verifyToken) {
            ($verifyToken)();

            $secret = request()->query('secret', null);
            $params = [];
            if ($secret) {
                $params['--secret'] = $secret;
            }

            Artisan::call('down', $params);
            return response()->json([
                'success' => true,
                'command' => 'down',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.down');

        // Disable maintenance mode
        Route::get('/up', function () use ($verifyToken) {
            ($verifyToken)();

            Artisan::call('up');
            return response()->json([
                'success' => true,
                'command' => 'up',
                'output'  => Artisan::output(),
            ]);
        })->name('artisan.up');

        /* ================================================================
         *  FULL DEPLOY (one-shot)
         * ================================================================*/

        // Run a full deployment sequence
        Route::get('/deploy', function () use ($verifyToken) {
            ($verifyToken)();

            $results = [];

            // 1. Maintenance mode ON
            Artisan::call('down', ['--secret' => 'deploy-bypass-' . date('Ymd')]);
            $results[] = ['command' => 'down', 'output' => trim(Artisan::output())];

            // 2. Clear old caches
            Artisan::call('optimize:clear');
            $results[] = ['command' => 'optimize:clear', 'output' => trim(Artisan::output())];

            // 3. Run migrations
            Artisan::call('migrate', ['--force' => true]);
            $results[] = ['command' => 'migrate --force', 'output' => trim(Artisan::output())];

            // 4. Cache for production
            Artisan::call('optimize');
            $results[] = ['command' => 'optimize', 'output' => trim(Artisan::output())];

            // 5. Storage link (idempotent)
            try {
                Artisan::call('storage:link');
                $results[] = ['command' => 'storage:link', 'output' => trim(Artisan::output())];
            } catch (\Exception $e) {
                $results[] = ['command' => 'storage:link', 'output' => 'Already linked (skipped)'];
            }

            // 6. Restart queues
            Artisan::call('queue:restart');
            $results[] = ['command' => 'queue:restart', 'output' => trim(Artisan::output())];

            // 7. Maintenance mode OFF
            Artisan::call('up');
            $results[] = ['command' => 'up', 'output' => trim(Artisan::output())];

            return response()->json([
                'success' => true,
                'command' => 'deploy (full sequence)',
                'steps'   => $results,
            ]);
        })->name('artisan.deploy');

        /* ================================================================
         *  STATUS / INFO
         * ================================================================*/

        // Application environment info
        Route::get('/info', function () use ($verifyToken) {
            ($verifyToken)();

            return response()->json([
                'success'     => true,
                'app_name'    => config('app.name'),
                'environment' => app()->environment(),
                'debug'       => config('app.debug'),
                'url'         => config('app.url'),
                'php_version' => PHP_VERSION,
                'laravel'     => app()->version(),
                'server_time' => now()->toDateTimeString(),
                'timezone'    => config('app.timezone'),
            ]);
        })->name('artisan.info');
    });
});
