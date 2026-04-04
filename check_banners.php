<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->handleRequest(\Illuminate\Http\Request::capture());

// Check banners
$banners = \App\Models\Banner::all();
echo "Total banners: " . $banners->count() . "\n";
foreach ($banners as $b) {
    echo "ID: {$b->id} | Position: {$b->position} | Active: {$b->is_active} | Image: {$b->image} | Title: {$b->title}\n";
}

// Check active banners for home-mid
echo "\n--- Active home-mid banners ---\n";
$homeMid = \App\Models\Banner::active()->position('home-mid')->orderBy('sort_order')->get();
echo "Count: " . $homeMid->count() . "\n";
foreach ($homeMid as $b) {
    echo "ID: {$b->id} | Image exists: " . (file_exists(storage_path('app/public/' . $b->image)) ? 'YES' : 'NO') . " | Image: {$b->image}\n";
}

echo "\n--- Active home-sidebar banners ---\n";
$sidebar = \App\Models\Banner::active()->position('home-sidebar')->orderBy('sort_order')->get();
echo "Count: " . $sidebar->count() . "\n";
