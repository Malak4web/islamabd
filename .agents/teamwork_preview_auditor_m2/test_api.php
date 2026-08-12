<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = new App\Http\Controllers\Api\SettingController();

app()->setLocale('en');
$resEn = $controller->index()->getData(true);

app()->setLocale('ar');
$resAr = $controller->index()->getData(true);

echo "=== English API Response ===\n";
echo "site_name: " . ($resEn['data']['site_name'] ?? 'N/A') . "\n";
echo "site_name_en: " . ($resEn['data']['site_name_en'] ?? 'N/A') . "\n";
echo "contact_email: " . ($resEn['data']['contact_email'] ?? 'N/A') . "\n";
echo "meta_title_en: " . ($resEn['data']['meta_title_en'] ?? 'N/A') . "\n";

echo "\n=== Arabic API Response ===\n";
echo "site_name: " . ($resAr['data']['site_name'] ?? 'N/A') . "\n";
echo "site_name_ar: " . ($resAr['data']['site_name_ar'] ?? 'N/A') . "\n";
echo "contact_email: " . ($resAr['data']['contact_email'] ?? 'N/A') . "\n";
echo "meta_title_ar: " . ($resAr['data']['meta_title_ar'] ?? 'N/A') . "\n";

echo "\nFull Data Keys & Values:\n";
print_r($resEn['data']);

