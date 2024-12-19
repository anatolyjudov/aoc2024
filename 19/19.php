<?php

include('../utils.php');

$input = file('input.txt', FILE_IGNORE_NEW_LINES);

$patterns = array_map(fn($v) => trim($v), explode(',', $input[0]));
$designs = array_splice($input, 2);

$new_patterns = $patterns;
while(true) {
    foreach($new_patterns as $n => $pattern) {
        $splice = $new_patterns;
        array_splice($splice, $n, 1);
        if (possible($pattern, $splice)) {
            $new_patterns = $splice;
            continue 2;
        }
    }
    break;
}

$ans1 = 0;
foreach($designs as $n => $design) if (possible($design, $new_patterns)) $ans1++;

echo 'First star: ' . $ans1 . PHP_EOL;

function possible($design, $patterns): bool
{
    if ($design === '') return true;

    for($p = 0; $p < count($patterns); $p++) {
        if (str_starts_with($design, $patterns[$p])) {
            $designLeft = substr($design, strlen($patterns[$p])); 
            if ($designLeft === '') return true;
            if (possible($designLeft, $patterns)) return true;
        }
    }

    return false;
}