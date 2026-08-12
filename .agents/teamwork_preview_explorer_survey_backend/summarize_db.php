<?php
$data = json_decode(file_get_contents(__DIR__ . '/db_survey_full.json'), true);

$byTable = [];
foreach ($data as $item) {
    $tbl = $item['table'];
    if (!isset($byTable[$tbl])) {
        $byTable[$tbl] = [];
    }
    $byTable[$tbl][] = $item;
}

foreach ($byTable as $tbl => $items) {
    echo "=== Table: $tbl (Count: " . count($items) . ") ===\n";
    foreach ($items as $item) {
        $pk = $item['primary_key_name'] . '=' . $item['primary_key_val'];
        if ($tbl === 'settings' && isset($item['full_value'])) {
            // print key if settings table
        }
        echo "  [PK: $pk] Col: {$item['column']} | Match: '{$item['matched_target']}' | Val: " . mb_substr($item['full_value'], 0, 80) . "\n";
    }
    echo "\n";
}
