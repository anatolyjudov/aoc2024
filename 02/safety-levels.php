<?php

$inputLines = file('input.txt');

$safeReports1 = 0;

foreach($inputLines as $line) {
    $levels = explode(' ', $line);
    $diffs = [];
    for($i = 1; $i < count($levels); $i++) {
        $diffs[] = $levels[$i] - $levels[$i - 1];
    }

    foreach($diffs as $diff) {
        if (($diff > 0) !== ($diffs[0] > 0)) {
            continue 2;
        }
        if (abs($diff) < 1 || abs($diff) > 3) {
            continue 2;
        }
    }

    $safeReports1++;
}

echo sprintf('First star: %s%s', $safeReports1, PHP_EOL);