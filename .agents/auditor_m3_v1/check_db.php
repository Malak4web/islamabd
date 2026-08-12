<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = Schema::getTableListing();

echo "Tables found via Laravel DB: " . implode(', ', $tables) . "\n";

$searchTerms = [
    'InDesign',
    'In Design',
    'indesign',
    'INDESIGN',
    'إن ديزاين',
    'ان ديزين',
    'ان ديزاين',
    'إن ديزين',
    'indesign-co.com'
];

$foundCount = 0;

foreach ($tables as $table) {
    if (in_array($table, ['migrations', 'sqlite_sequence'])) continue;
    
    $rows = DB::table($table)->get();
    $rowCount = $rows->count();
    
    foreach ($rows as $row) {
        $json = json_encode($row, JSON_UNESCAPED_UNICODE);
        foreach ($searchTerms as $term) {
            if (mb_stripos($json, $term) !== false) {
                echo "[VIOLATION] Found legacy string '{$term}' in table '{$table}':\n";
                print_r($row);
                echo "----------------------------------------\n";
                $foundCount++;
            }
        }
    }
    echo "Checked table '{$table}' ({$rowCount} rows).\n";
}

if ($foundCount === 0) {
    echo "RESULT: CLEAN. No legacy brand strings found in SQLite database.\n";
} else {
    echo "RESULT: VIOLATION. Found {$foundCount} occurrences of legacy brand strings in SQLite database.\n";
}
