<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = ['settings', 'projects', 'sections', 'services', 'pages', 'admins'];
$terms = ['InDesign', 'Indesign', 'indesign-co.com', 'Indesign_co', 'indesign_co', 'إن ديزاين', 'ان ديزين'];
$foundAny = false;

echo "--- START DATABASE FORENSIC SCAN ---\n";
foreach ($tables as $table) {
    if (!Schema::hasTable($table)) {
        echo "Table '$table' does not exist.\n";
        continue;
    }
    $cols = Schema::getColumnListing($table);
    foreach ($terms as $term) {
        foreach ($cols as $col) {
            $cnt = DB::table($table)->where($col, 'LIKE', '%' . $term . '%')->count();
            if ($cnt > 0) {
                echo "MATCH FOUND in table '$table', column '$col' for term '$term': $cnt rows\n";
                $rows = DB::table($table)->where($col, 'LIKE', '%' . $term . '%')->get();
                foreach ($rows as $r) {
                    echo "  Row ID " . ($r->id ?? 'N/A') . " -> $col: " . substr($r->$col, 0, 100) . "\n";
                }
                $foundAny = true;
            }
        }
    }
}
if (!$foundAny) {
    echo "NO LEGACY BRAND STRINGS FOUND in database tables: " . implode(', ', $tables) . "\n";
}
echo "--- END DATABASE FORENSIC SCAN ---\n";
