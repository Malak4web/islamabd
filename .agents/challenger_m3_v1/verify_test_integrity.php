<?php
// verify_test_integrity.php - Scan test files for skipped, muted, or falsified tests

$projectRoot = realpath(__DIR__ . '/../../');

$testDirs = [
    $projectRoot . '/tests',
    $projectRoot . '/resources/js/tests'
];

$skipKeywords = [
    'markTestSkipped',
    'markTestIncomplete',
    '@skip',
    '@incomplete',
    'it.skip',
    'test.skip',
    'describe.skip',
    'xit(',
    'xdescribe(',
    'it.todo',
    'test.todo',
    'it.only',
    'test.only',
    'describe.only',
    'fit('
];

$suspiciousAssertions = [
    'assertTrue(true)',
    'assertFalse(false)',
    'assertEquals(1, 1)',
    'toBe(true)',
    'toBe(false)',
    'expect(true).toBe(true)'
];

$suspiciousFound = [];
$skipsFound = [];
$totalTestFiles = 0;

function scanTestDir($dir, $skipKeywords, $suspiciousAssertions, &$skipsFound, &$suspiciousFound, &$totalTestFiles, $projectRoot) {
    if (!is_dir($dir)) return;
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            scanTestDir($path, $skipKeywords, $suspiciousAssertions, $skipsFound, $suspiciousFound, $totalTestFiles, $projectRoot);
        } else {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (in_array($ext, ['php', 'js', 'ts'])) {
                $totalTestFiles++;
                $content = file_get_contents($path);
                $lines = explode("\n", $content);
                $relPath = str_replace($projectRoot . '/', '', $path);
                
                foreach ($lines as $lineNum => $lineContent) {
                    $lineStr = trim($lineContent);
                    foreach ($skipKeywords as $kw) {
                        if (str_contains($lineStr, $kw)) {
                            $skipsFound[] = [
                                'file' => $relPath,
                                'line' => $lineNum + 1,
                                'keyword' => $kw,
                                'snippet' => $lineStr
                            ];
                        }
                    }
                    foreach ($suspiciousAssertions as $sa) {
                        if (str_contains($lineStr, $sa)) {
                            $suspiciousFound[] = [
                                'file' => $relPath,
                                'line' => $lineNum + 1,
                                'assertion' => $sa,
                                'snippet' => $lineStr
                            ];
                        }
                    }
                }
            }
        }
    }
}

foreach ($testDirs as $td) {
    scanTestDir($td, $skipKeywords, $suspiciousAssertions, $skipsFound, $suspiciousFound, $totalTestFiles, $projectRoot);
}

echo "Total Test Files Inspected: {$totalTestFiles}\n";
echo "Total Skipped / Muted Indicators Found: " . count($skipsFound) . "\n";
if (count($skipsFound) > 0) {
    foreach ($skipsFound as $s) {
        echo "  [SKIP/MUTE] {$s['file']}:{$s['line']} ({$s['keyword']}) -> {$s['snippet']}\n";
    }
} else {
    echo "  ZERO skipped or muted tests found.\n";
}

echo "Total Suspicious / Dummy Assertions Found: " . count($suspiciousFound) . "\n";
if (count($suspiciousFound) > 0) {
    foreach ($suspiciousFound as $sa) {
        echo "  [SUSPICIOUS] {$sa['file']}:{$sa['line']} ({$sa['assertion']}) -> {$sa['snippet']}\n";
    }
} else {
    echo "  ZERO suspicious / dummy assertions found.\n";
}
