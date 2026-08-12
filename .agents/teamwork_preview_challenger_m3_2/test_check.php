<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$controller = new App\Http\Controllers\Api\SettingController();
$response = $controller->index();
$data = json_decode($response->getContent(), true)['data'];

echo "=== 1. SETTINGS API RESULTS ===\n";
echo "logo: " . ($data['logo'] ?? 'NULL') . "\n";
echo "logo_light: " . ($data['logo_light'] ?? 'NULL') . "\n";
echo "logo_dark: " . ($data['logo_dark'] ?? 'NULL') . "\n";

$dbSettings = App\Models\Setting::whereIn('key', ['logo', 'logo_light', 'logo_dark'])->get()->pluck('value', 'key')->toArray();
echo "\n=== 2. DB RECORDS ===\n";
print_r($dbSettings);

echo "\n=== 3. PHYSICAL ASSET VERIFICATION ===\n";
$path1 = base_path('storage/app/public/settings/logo.jpg');
$path2 = base_path('public/images/logo.jpg');
$path3 = base_path('public/storage/settings/logo.jpg');

echo "Path 1 (storage/app/public/settings/logo.jpg):\n";
echo "  Exists: " . (file_exists($path1) ? "YES" : "NO") . "\n";
if (file_exists($path1)) {
    echo "  Size: " . filesize($path1) . " bytes\n";
    $info = @getimagesize($path1);
    echo "  Dimensions: " . ($info ? "{$info[0]}x{$info[1]} ({$info['mime']})" : "Unknown") . "\n";
}

echo "Path 2 (public/images/logo.jpg):\n";
echo "  Exists: " . (file_exists($path2) ? "YES" : "NO") . "\n";
if (file_exists($path2)) {
    echo "  Size: " . filesize($path2) . " bytes\n";
    $info = @getimagesize($path2);
    echo "  Dimensions: " . ($info ? "{$info[0]}x{$info[1]} ({$info['mime']})" : "Unknown") . "\n";
}

echo "Path 3 (public/storage/settings/logo.jpg via web symlink):\n";
echo "  Exists: " . (file_exists($path3) ? "YES" : "NO") . "\n";
if (file_exists($path3)) {
    echo "  Size: " . filesize($path3) . " bytes\n";
}
