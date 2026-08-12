<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== SETTINGS DETAILS ===\n";
foreach ([16, 17, 18, 36, 37, 40, 44, 45] as $id) {
    $row = DB::table('settings')->where('id', $id)->first();
    if ($row) {
        echo "ID: {$row->id} | Key: {$row->key} | Value: {$row->value}\n";
    }
}

echo "\n=== SECTIONS DETAILS ===\n";
foreach ([7, 8, 9, 10] as $id) {
    $row = DB::table('sections')->where('id', $id)->first();
    if ($row) {
        echo "ID: {$row->id} | Key: {$row->key} | PageID: {$row->page_id}\nContent: {$row->content}\n\n";
    }
}
