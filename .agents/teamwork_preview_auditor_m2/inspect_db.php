<?php

$dbPath = __DIR__ . '/../../database/database.sqlite';
if (!file_exists($dbPath)) {
    echo "Database file not found at " . $dbPath . "\n";
    exit(1);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$tablesStmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
$tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

echo "Found tables: " . implode(', ', $tables) . "\n\n";

$oldBrandFoundCount = 0;
$newBrandFoundCount = 0;

$oldBrandRegex = '/InDesign|In Design|إن ديزاين|ان ديزين/i';
$newBrandEn = 'Eslam Abdulghani Designs';
$newBrandAr = 'إسلام عبد الغني ديزاينز';

foreach ($tables as $table) {
    echo "=== Inspecting table: {$table} ===\n";
    $stmt = $pdo->query("SELECT * FROM \"{$table}\"");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($rows as $row) {
        $json = json_encode($row, JSON_UNESCAPED_UNICODE);
        
        if (preg_match($oldBrandRegex, $json, $matches)) {
            echo "🔴 OLD BRAND FOUND in table [{$table}]: match='{$matches[0]}'\n";
            echo "   Row: " . substr($json, 0, 300) . "\n";
            $oldBrandFoundCount++;
        }
        
        if (mb_strpos($json, $newBrandEn) !== false || mb_strpos($json, $newBrandAr) !== false) {
            $newBrandFoundCount++;
        }
    }
}

echo "\n--- Summary ---\n";
echo "Old brand occurrences found in DB: {$oldBrandFoundCount}\n";
echo "New brand occurrences found in DB: {$newBrandFoundCount}\n";

echo "\n--- Specific Key Values in settings ---\n";
$stmt = $pdo->query("SELECT * FROM settings WHERE key IN ('site_name_en', 'site_name_ar', 'about_short_en', 'about_short_ar', 'contact_email', 'meta_title_en', 'meta_title_ar', 'copyright_en', 'copyright_ar')");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $setting) {
    echo "Key: {$setting['key']} | Value: {$setting['value']}\n";
}

