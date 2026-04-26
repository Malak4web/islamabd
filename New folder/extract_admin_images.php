<?php
$html = file_get_contents('https://indesign-co.com/administrative/');
preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches);
echo "Images:\n";
print_r(array_unique($matches[1]));

preg_match_all('/background-image:\s*url\((["\']?)([^"\']+)\1\)/i', $html, $bg_matches);
echo "\nBackgrounds:\n";
print_r(array_unique($bg_matches[2]));
