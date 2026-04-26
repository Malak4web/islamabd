<?php
require_once 'C:/xampp/htdocs/indesign/vendor/autoload.php';
$app = require_once 'C:/xampp/htdocs/indesign/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$services = App\Models\Service::all();
foreach ($services as $s) {
    $galleryCount = count(json_decode($s->gallery ?? '[]', true) ?? []);
    echo "ID {$s->id}: {$s->title_en} | image: " . ($s->image ? 'YES' : 'NO') . " | gallery: {$galleryCount} images | icon: " . ($s->icon ? 'YES' : 'NO') . "\n";
}
