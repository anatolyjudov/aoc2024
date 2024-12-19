<?php

include('../utils.php');

$input = file('input.txt', FILE_IGNORE_NEW_LINES);

$patterns = array_map(fn($v) => trim($v), explode(',', $input[0]));
$designs = array_splice($input, 2);

$ans = 0;
foreach($designs as $n => $design) $ans += countSets($design);

echo 'Second star: ' . $ans . PHP_EOL;

function countSets($design): int
{
    global $patterns;
    static $cache;

    if ($design === '') return 1;

    if (isset($cache[$design])) return $cache[$design];

    $c = 0;
    foreach($patterns as $pattern) if (str_starts_with($design, $pattern)) {
        $designLeft = substr($design, strlen($pattern)); 
        $c += countSets($designLeft);
    }

    $cache[$design] = $c;

    return $c;
}