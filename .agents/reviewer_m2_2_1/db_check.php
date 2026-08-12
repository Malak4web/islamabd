<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['settings', 'sections', 'pages', 'admins', 'services', 'projects'];
$foundAny = false;

foreach ($tables as $table) {
    $columns = Illuminate\Support\Facades\Schema::getColumnListing($table);
    foreach ($columns as $col) {
        $count = Illuminate\Support\Facades\DB::table($table)
            ->where($col, 'LIKE', '%indesign%')
            ->orWhere($col, 'LIKE', '%إن ديزاين%')
            ->orWhere($col, 'LIKE', '%ان ديزين%')
            ->count();
        if ($count > 0) {
            echo "Match found: {$table}.{$col} => {$count} rows\n";
            $foundAny = true;
        }
    }
}

if (!$foundAny) {
    echo "NO_LEGACY_BRAND_STRINGS_FOUND_IN_DATABASE\n";
}
