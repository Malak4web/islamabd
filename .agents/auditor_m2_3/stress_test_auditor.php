<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Setting;
use App\Http\Controllers\Api\SettingController;

$dirtyInputs = [
    'test_case_1' => 'Welcome to InDesign Studio',
    'test_case_2' => 'Powered by INDESIGN',
    'test_case_3' => 'Visit indesign-co.com for details',
    'test_case_4' => 'Contact indesign_co team',
    'test_case_5' => 'Create IN DESIGN solutions',
    'test_case_6' => 'شركة إن ديزاين للتصميم',
    'test_case_7' => 'مرحبا بكم في ان ديزين',
    'test_case_8' => 'خدمات ان ديزاين المتكاملة',
    'test_case_9' => 'عالم إن ديزين للإنشاءات',
];

foreach ($dirtyInputs as $key => $val) {
    Setting::updateOrCreate(['key' => $key], ['value' => $val, 'group' => 'general']);
}

$controller = new SettingController();
$response = json_decode($controller->index()->getContent(), true)['data'];

$failures = 0;
foreach ($dirtyInputs as $key => $origVal) {
    $resultVal = $response[$key] ?? '';
    // Check if result contains any legacy brand strings
    if (
        mb_stripos($resultVal, 'indesign') !== false ||
        mb_stripos($resultVal, 'in design') !== false ||
        str_contains($resultVal, 'إن ديزاين') ||
        str_contains($resultVal, 'ان ديزين') ||
        str_contains($resultVal, 'ان ديزاين') ||
        str_contains($resultVal, 'إن ديزين')
    ) {
        echo "FAIL: Key [$key] with original value '$origVal' sanitization failed! Output: '$resultVal'\n";
        $failures++;
    } else {
        echo "PASS: Key [$key] ('$origVal') -> sanitized output: '$resultVal'\n";
    }
}

// Clean up dirty test keys
foreach ($dirtyInputs as $key => $val) {
    Setting::where('key', $key)->delete();
}

if ($failures === 0) {
    echo "STRESS TEST PASSED: All 9 edge cases sanitized successfully.\n";
} else {
    echo "STRESS TEST FAILED: $failures cases failed.\n";
}
