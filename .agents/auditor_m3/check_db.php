<?php
$db = new PDO('sqlite:database/database.sqlite');
$found = 0;
foreach (['settings', 'sections', 'pages', 'admins'] as $table) {
    $stmt = $db->query("SELECT * FROM $table");
    if (!$stmt) continue;
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        foreach ($row as $col => $val) {
            if (is_string($val)) {
                if (stripos($val, 'indesign') !== false || str_contains($val, 'إن ديزاين') || str_contains($val, 'ان ديزين') || str_contains($val, 'ان ديزاين')) {
                    echo "Found in $table column '$col': $val\n";
                    $found++;
                }
            }
        }
    }
}
if ($found === 0) {
    echo "CLEAN: 0 legacy brand occurrences found in database!\n";
}
