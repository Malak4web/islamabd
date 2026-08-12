<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- CHECKING DATABASE RECORDS IN SETTINGS TABLE ---\n";

$settings = DB::table('settings')->get();
$legacyFound = [];

$searchTerms = ['indesign', 'in design', 'ان ديزاين', 'ان ديزين', 'إن ديزاين', 'إن ديزين'];

foreach ($settings as $setting) {
    foreach ($searchTerms as $term) {
        if (mb_stripos($setting->value, $term) !== false || mb_stripos($setting->key, $term) !== false) {
            $legacyFound[] = [
                'id' => $setting->id,
                'key' => $setting->key,
                'value' => $setting->value,
                'matched_term' => $term
            ];
        }
    }
}

echo "Total settings records: " . count($settings) . "\n";
echo "Legacy terms found in settings table: " . count($legacyFound) . "\n";

if (count($legacyFound) > 0) {
    print_r($legacyFound);
} else {
    echo "SUCCESS: Database settings table is 100% clean of legacy brand terms!\n";
}

echo "\n--- CHECKING ALL DATABASE TABLES FOR LEGACY TERMS ---\n";
$tables = ['settings', 'pages', 'sections', 'services', 'projects', 'users'];
$totalLegacyAcrossDb = 0;

foreach ($tables as $table) {
    $rows = DB::table($table)->get();
    $count = 0;
    foreach ($rows as $row) {
        $json = json_encode($row, JSON_UNESCAPED_UNICODE);
        foreach ($searchTerms as $term) {
            if (mb_stripos($json, $term) !== false) {
                $count++;
                break;
            }
        }
    }
    echo "Table [$table]: $count legacy term matches found.\n";
    $totalLegacyAcrossDb += $count;
}

echo "Total legacy matches across entire database: $totalLegacyAcrossDb\n";
