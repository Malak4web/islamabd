<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
$matchCount = 0;

foreach ($tables as $t) {
    $table = $t->name;
    try {
        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            foreach ((array)$row as $col => $val) {
                if (is_string($val)) {
                    // Check for legacy brand variants: indesign, in design, in_design, etc. or Arabic variations
                    if (
                        mb_stripos($val, 'indesign') !== false ||
                        mb_stripos($val, 'in design') !== false ||
                        mb_stripos($val, 'in_design') !== false ||
                        (str_contains($val, 'ديزاين') && !str_contains($val, 'عبد الغني')) ||
                        str_contains($val, 'إن ديزاين') ||
                        str_contains($val, 'ان ديزين') ||
                        str_contains($val, 'ان ديزاين') ||
                        str_contains($val, 'إن ديزين')
                    ) {
                        $matchCount++;
                        $id = $row->id ?? 'N/A';
                        echo "MATCH FOUND in Table [$table], Column [$col], ID [$id]: $val\n";
                    }
                }
            }
        }
    } catch (\Throwable $e) {
        echo "Could not query table $table: " . $e->getMessage() . "\n";
    }
}

echo "Database scan completed. Total matches: $matchCount\n";
