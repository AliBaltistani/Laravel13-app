<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

echo "=== Product Images in DB ===" . PHP_EOL;
$images = ProductImage::take(5)->get();
foreach ($images as $img) {
    echo "ID: {$img->id} | Path: {$img->image_path} | Primary: " . ($img->is_primary ? 'Y' : 'N') . PHP_EOL;
    echo "  Storage::url() => " . Storage::url($img->image_path) . PHP_EOL;
    echo "  File exists on disk: " . (file_exists(storage_path('app/public/' . $img->image_path)) ? 'YES' : 'NO') . PHP_EOL;
}

echo PHP_EOL . "=== APP_URL ===" . PHP_EOL;
echo env('APP_URL') . PHP_EOL;

echo PHP_EOL . "=== Default disk ===" . PHP_EOL;
echo config('filesystems.default') . PHP_EOL;

echo PHP_EOL . "=== Storage URL config ===" . PHP_EOL;
echo config('filesystems.disks.public.url') . PHP_EOL;
