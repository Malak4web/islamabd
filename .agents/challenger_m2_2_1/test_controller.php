<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\SettingController;
use Illuminate\Http\Request;

$terms = [
    'InDesign',
    'Indesign',
    'INdesign',
    'indesign-co.com',
    'Indesign_co',
    'indesign_co',
    'إن ديزاين',
    'ان ديزين',
    'indesign',
    'in design'
];

echo "=== 2. Testing SettingController index output ===\n\n";

$locales = ['en', 'ar'];

foreach ($locales as $loc) {
    app()->setLocale($loc);
    $controller = new SettingController();
    $request = Request::create('/api/v1/settings', 'GET', [], [], [], ['HTTP_ACCEPT_LANGUAGE' => $loc]);
    
    $response = $controller->index();
    $data = $response->getData(true)['data'];
    
    echo "--- Testing Locale: '$loc' ---\n";
    echo "site_name: " . ($data['site_name'] ?? 'N/A') . "\n";
    echo "site_name_en: " . ($data['site_name_en'] ?? 'N/A') . "\n";
    echo "site_name_ar: " . ($data['site_name_ar'] ?? 'N/A') . "\n";
    echo "copyright: " . ($data['copyright'] ?? 'N/A') . "\n";
    echo "footer_text: " . ($data['footer_text'] ?? 'N/A') . "\n";
    echo "email_main: " . ($data['email_main'] ?? 'N/A') . "\n";
    echo "facebook_url: " . ($data['facebook_url'] ?? 'N/A') . "\n";
    
    // Check for any legacy brand leakage in response values
    $leaks = [];
    foreach ($data as $k => $v) {
        if (is_string($v)) {
            foreach ($terms as $term) {
                if (mb_stripos($v, $term) !== false) {
                    $leaks[] = "Key '$k' contains legacy term '$term' -> '$v'";
                }
            }
        }
    }
    
    if (empty($leaks)) {
        echo "RESULT ($loc): CLEAN! No legacy terms detected.\n\n";
    } else {
        echo "RESULT ($loc): FAILED! Leaks found:\n";
        print_r($leaks);
        echo "\n";
    }
}
