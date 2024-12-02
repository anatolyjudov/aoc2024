<?php

$inputLines = file('input.txt');

$left = $right = [];

foreach($inputLines as $line) {
    $parts = explode('   ', trim($line));
    $left[] = $parts[0];

    if (isset($right[$parts[1]])) {
        $right[$parts[1]]++;
    } else {
        $right[$parts[1]] = 1;
    }
}

$sum = 0;
for($i = 0; $i < count($left); $i++) {
    if (!isset($right[$left[$i]])) continue;
    $score = $left[$i] * $right[$left[$i]];
    $sum += $score;
}

echo $sum . PHP_EOL;