<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = Schema::getTableListing();

$targets = [
    'InDesign',
    'Indesign',
    'In Design',
    'إن ديزاين',
    'indesign'
];

$allMatches = [];

foreach ($tables as $table) {
    $columns = Schema::getColumnListing($table);
    $tableRecords = DB::table($table)->get();
    
    foreach ($tableRecords as $row) {
        $rowArr = (array)$row;
        foreach ($rowArr as $col => $val) {
            if (!is_string($val)) continue;
            foreach ($targets as $target) {
                if (mb_stripos($val, $target) !== false) {
                    $keyName = isset($rowArr['id']) ? 'id' : (isset($rowArr['key']) ? 'key' : array_keys($rowArr)[0]);
                    $keyValue = $rowArr[$keyName];
                    $allMatches[] = [
                        'table' => $table,
                        'primary_key_name' => $keyName,
                        'primary_key_val' => $keyValue,
                        'column' => $col,
                        'matched_target' => $target,
                        'full_value' => $val,
                    ];
                    break; // match found for this column/val
                }
            }
        }
    }
}

file_put_contents(__DIR__ . '/db_survey_full.json', json_encode($allMatches, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Found " . count($allMatches) . " matched entries in DB.\n";
