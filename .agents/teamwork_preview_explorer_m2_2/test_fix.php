<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

echo "--- BEFORE SEEDING & CLEANUP ---\n";
$stmt = DB::select("SELECT id, key, value FROM settings WHERE key IN ('site_name', 'footer_text', 'email_main', 'google_maps_eg', 'facebook_url', 'instagram_url', 'facebook', 'instagram')");
foreach ($stmt as $row) {
    echo "id: {$row->id} | key: {$row->key} | value: {$row->value}\n";
}

echo "\n--- TESTING SettingController::index() BEFORE FIX ---\n";
$c = new App\Http\Controllers\Api\SettingController();
$data = $c->index()->getData(true)['data'];
echo "site_name: " . ($data['site_name'] ?? 'N/A') . "\n";
echo "footer_text: " . ($data['footer_text'] ?? 'N/A') . "\n";
echo "email_main: " . ($data['email_main'] ?? 'N/A') . "\n";
