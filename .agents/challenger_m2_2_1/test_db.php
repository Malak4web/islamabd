<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

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

echo "=== 1. Checking settings table in database.sqlite ===\n";

$settings = DB::table('settings')->get();
echo "Total settings count: " . $settings->count() . "\n";

$findings = [];
foreach ($settings as $setting) {
    foreach ($terms as $term) {
        if (mb_stripos($setting->key, $term) !== false || mb_stripos($setting->value, $term) !== false) {
            $findings[] = [
                'id' => $setting->id,
                'key' => $setting->key,
                'value' => $setting->value,
                'matched_term' => $term
            ];
        }
    }
}

if (empty($findings)) {
    echo "SUCCESS: No legacy brand terms found in settings table!\n\n";
} else {
    echo "WARNING/FAILURE: Found legacy brand terms in settings table:\n";
    print_r($findings);
    echo "\n";
}

echo "=== Checking all database tables for legacy terms ===\n";
$tables = ['settings', 'sections', 'pages', 'admins', 'projects', 'services', 'users'];
$allFindings = [];

foreach ($tables as $table) {
    if (DB::getSchemaBuilder()->hasTable($table)) {
        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            $rowArray = (array)$row;
            foreach ($rowArray as $col => $val) {
                if (is_string($val)) {
                    foreach ($terms as $term) {
                        if (mb_stripos($val, $term) !== false) {
                            $allFindings[] = [
                                'table' => $table,
                                'id' => $row->id ?? null,
                                'column' => $col,
                                'value' => $val,
                                'matched_term' => $term
                            ];
                        }
                    }
                }
            }
        }
    }
}

if (empty($allFindings)) {
    echo "SUCCESS: No legacy brand terms found across all database tables!\n\n";
} else {
    echo "WARNING/FAILURE: Found legacy brand terms in DB tables:\n";
    print_r($allFindings);
    echo "\n";
}
