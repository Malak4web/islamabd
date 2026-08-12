<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$rawTables = Schema::getTables();
$tables = array_map(fn($t) => is_array($t) ? $t['name'] : $t->name, $rawTables);
$terms = ['InDesign', 'Indesign', 'indesign-co.com', 'Indesign_co', 'indesign_co', 'إن ديزاين', 'ان ديزين'];
$foundAny = false;

echo "--- START ALL TABLES FORENSIC SCAN ---\n";
foreach ($tables as $table) {
    if (in_array($table, ['migrations', 'sqlite_sequence'])) continue;
    $cols = Schema::getColumnListing($table);
    foreach ($terms as $term) {
        foreach ($cols as $col) {
            try {
                $cnt = DB::table($table)->where($col, 'LIKE', '%' . $term . '%')->count();
                if ($cnt > 0) {
                    echo "MATCH FOUND in table '$table', column '$col' for term '$term': $cnt rows\n";
                    $foundAny = true;
                }
            } catch (\Throwable $e) {}
        }
    }
}
if (!$foundAny) {
    echo "NO LEGACY BRAND STRINGS FOUND in ANY database tables.\n";
}
echo "--- END ALL TABLES FORENSIC SCAN ---\n";
