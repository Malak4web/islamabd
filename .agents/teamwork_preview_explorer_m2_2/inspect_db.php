<?php
$pdo = new PDO('sqlite:database/database.sqlite');
$tablesStmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
$tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

echo "TABLES FOUND: " . implode(", ", $tables) . "\n\n";

$oldBrandPatterns = ['indesign', 'in design', 'in-design', 'إن ديزاين'];

foreach ($tables as $table) {
    $rows = $pdo->query("SELECT * FROM \"$table\"")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        foreach ($row as $col => $val) {
            if ($val !== null && is_string($val)) {
                $valLower = mb_strtolower($val, 'UTF-8');
                foreach ($oldBrandPatterns as $pattern) {
                    if (str_contains($valLower, mb_strtolower($pattern, 'UTF-8'))) {
                        echo "MATCH in Table [$table] | Row ID [{$row['id']}] | Col [$col]: $val\n";
                        break;
                    }
                }
            }
        }
    }
}
