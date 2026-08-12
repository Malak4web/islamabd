<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = new App\Http\Controllers\Api\SettingController();

app()->setLocale('en');
$resEn = json_decode(json_encode($controller->index()->getData()), true)['data'];

app()->setLocale('ar');
$resAr = json_decode(json_encode($controller->index()->getData()), true)['data'];

echo "=== EN SETTINGS RESPONSE ===\n";
echo "site_name: " . ($resEn['site_name'] ?? 'N/A') . "\n";
echo "site_name_en: " . ($resEn['site_name_en'] ?? 'N/A') . "\n";
echo "copyright: " . ($resEn['copyright'] ?? 'N/A') . "\n";
echo "footer_text: " . ($resEn['footer_text'] ?? 'N/A') . "\n";
echo "contact_email: " . ($resEn['contact_email'] ?? 'N/A') . "\n";

echo "\n=== AR SETTINGS RESPONSE ===\n";
echo "site_name: " . ($resAr['site_name'] ?? 'N/A') . "\n";
echo "site_name_ar: " . ($resAr['site_name_ar'] ?? 'N/A') . "\n";
echo "copyright: " . ($resAr['copyright'] ?? 'N/A') . "\n";
echo "footer_text: " . ($resAr['footer_text'] ?? 'N/A') . "\n";
echo "contact_email: " . ($resAr['contact_email'] ?? 'N/A') . "\n";
