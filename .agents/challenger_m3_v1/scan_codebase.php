<?php
// scan_codebase.php - Empirical codebase scan for legacy terms and test mutations

$projectRoot = realpath(__DIR__ . '/../../');
echo "Project Root: {$projectRoot}\n";

$targetDirs = ['app', 'config', 'database', 'resources', 'routes', 'tests'];
$rootFiles = glob($projectRoot . '/*.{php,js,json,vue,md,html,blade.php}', GLOB_BRACE);

$legacyTerms = [
    'InDesign',
    'In Design',
    'إن ديزاين',
    'indesign',
    'INDESIGN',
    'in design',
    'ان ديزاين',
    'indesign-co.com',
    'indesign.com',
    'ان ديزين',
    'إنديزاين',
    'انديزاين'
];

$results = [];
$totalFilesScanned = 0;

function scanDirRecursive($dir, $legacyTerms, &$results, &$totalFilesScanned) {
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || $item === 'node_modules' || $item === 'vendor' || $item === '.git' || $item === 'storage' || $item === '.agents') {
            continue;
        }
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            scanDirRecursive($path, $legacyTerms, $results, $totalFilesScanned);
        } else {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $scannableExts = ['php', 'js', 'vue', 'json', 'ts', 'jsx', 'tsx', 'html', 'blade.php', 'css', 'scss', 'md', 'env'];
            if (in_array($ext, $scannableExts) || str_ends_with($path, '.blade.php')) {
                $totalFilesScanned++;
                $content = file_get_contents($path);
                foreach ($legacyTerms as $term) {
                    if (mb_stripos($content, $term) !== false) {
                        $lines = explode("\n", $content);
                        foreach ($lines as $lineNum => $lineContent) {
                            if (mb_stripos($lineContent, $term) !== false) {
                                $results[] = [
                                    'file' => $path,
                                    'line' => $lineNum + 1,
                                    'term' => $term,
                                    'snippet' => trim($lineContent)
                                ];
                            }
                        }
                    }
                }
            }
        }
    }
}

foreach ($targetDirs as $td) {
    $fullPath = $projectRoot . DIRECTORY_SEPARATOR . $td;
    if (is_dir($fullPath)) {
        scanDirRecursive($fullPath, $legacyTerms, $results, $totalFilesScanned);
    }
}

foreach ($rootFiles as $rf) {
    if (is_file($rf)) {
        $totalFilesScanned++;
        $content = file_get_contents($rf);
        foreach ($legacyTerms as $term) {
            if (mb_stripos($content, $term) !== false) {
                $lines = explode("\n", $content);
                foreach ($lines as $lineNum => $lineContent) {
                    if (mb_stripos($lineContent, $term) !== false) {
                        $results[] = [
                            'file' => $rf,
                            'line' => $lineNum + 1,
                            'term' => $term,
                            'snippet' => trim($lineContent)
                        ];
                    }
                }
            }
        }
    }
}

echo "Total Files Scanned: {$totalFilesScanned}\n";
echo "Total Matches Found: " . count($results) . "\n\n";

$productionMatches = [];
$testMatches = [];
$seederCleanupMatches = [];
$docMatches = [];

foreach ($results as $res) {
    $relPath = str_replace($projectRoot . DIRECTORY_SEPARATOR, '', $res['file']);
    
    // Categorize
    if (str_contains($relPath, 'SettingSeeder.php') && ($res['line'] >= 78 && $res['line'] <= 89)) {
        $seederCleanupMatches[] = "[{$res['term']}] {$relPath}:{$res['line']} -> {$res['snippet']}";
    } elseif (str_contains($relPath, 'ORIGINAL_REQUEST.md') || str_contains($relPath, 'PROJECT.md') || str_contains($relPath, 'package-lock.json')) {
        $docMatches[] = "[{$res['term']}] {$relPath}:{$res['line']} -> {$res['snippet']}";
    } elseif (str_starts_with($relPath, 'tests') || str_contains($relPath, 'tests/')) {
        $testMatches[] = "[{$res['term']}] {$relPath}:{$res['line']} -> {$res['snippet']}";
    } else {
        $productionMatches[] = "[{$res['term']}] {$relPath}:{$res['line']} -> {$res['snippet']}";
    }
}

echo "=== PRODUCTION / FRONTEND / BACKEND CODE MATCHES (" . count($productionMatches) . ") ===\n";
foreach ($productionMatches as $m) echo "  $m\n";
if (empty($productionMatches)) echo "  NONE (0 matches)\n";

echo "\n=== TEST SUITE MATCHES (" . count($testMatches) . ") ===\n";
foreach ($testMatches as $m) echo "  $m\n";
if (empty($testMatches)) echo "  NONE (0 matches)\n";

echo "\n=== SEEDER CLEANUP ARRAY MATCHES (" . count($seederCleanupMatches) . ") ===\n";
foreach ($seederCleanupMatches as $m) echo "  $m\n";

echo "\n=== PROJECT DOCS / LOCKFILE MATCHES (" . count($docMatches) . ") ===\n";
foreach ($docMatches as $m) echo "  $m\n";
