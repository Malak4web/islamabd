<?php
require_once 'C:/xampp/htdocs/indesign/vendor/autoload.php';
$app = require_once 'C:/xampp/htdocs/indesign/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$services = App\Models\Service::all();
foreach ($services as $s) {
    echo "ID {$s->id}: {$s->title_en} | is_active: " . ($s->is_active ? 'TRUE' : 'FALSE') . "\n";
}
