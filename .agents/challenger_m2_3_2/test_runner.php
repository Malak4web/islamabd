<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Setting;
use App\Http\Controllers\Api\SettingController;

echo "--- ADVANCED CASING & SPELLING STRESS TEST ---\n";

$testCases = [
    'casing_1' => 'Powered by inDesign',
    'casing_2' => 'Powered by In design',
    'casing_3' => 'Powered by IN design',
    'casing_4' => 'Powered by in DESIGN',
    'casing_5' => 'Visit indesign_Co for details',
    'arabic_1' => 'مرحبا بكم في ان ديزاين',
    'arabic_2' => 'مرحبا بكم في إن ديزاين',
    'arabic_3' => 'مرحبا بكم في ان ديزين',
    'arabic_4' => 'مرحبا بكم في إن ديزين',
];

Setting::whereIn('key', array_keys($testCases))->delete();

foreach ($testCases as $key => $val) {
    Setting::create(['key' => $key, 'value' => $val, 'group' => 'general']);
}

$controller = new SettingController();
$data = json_decode($controller->index()->getContent(), true)['data'];

foreach ($testCases as $key => $val) {
    echo "Input: [$val] => Output: [" . ($data[$key] ?? 'N/A') . "]\n";
}

Setting::whereIn('key', array_keys($testCases))->delete();
