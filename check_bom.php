<?php
foreach(glob('database/seeders/*.php') as $f) {
    $c = file_get_contents($f);
    if (substr($c, 0, 3) === "\xEF\xBB\xBF") {
        echo "$f has BOM\n";
    }
}
