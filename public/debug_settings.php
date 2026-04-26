<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$settings = \App\Models\Setting::all()->pluck('value', 'key');
file_put_contents(__DIR__ . '/debug_settings.json', $settings->toJson());
echo "Settings dumped to public/debug_settings.json";
