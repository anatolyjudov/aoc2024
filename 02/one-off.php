<?php

$inputLines = file('input.txt');

$res = $res2 = 0;

foreach($inputLines as $line) {
    $levels = explode(' ', $line);

    if (isSafe($levels)) {
        $res++;
    }

    for($i = 0; $i < count($levels); $i++) {
        $tryLevels = $levels;
        array_splice($tryLevels, $i, 1);
        if (isSafe($tryLevels)) {
            $res2++;
            break;
        }
    }
}

echo sprintf('First star: %s%s', $res, PHP_EOL);
echo sprintf('Second star: %s%s', $res2, PHP_EOL);


function isSafe(array $levels): bool
{
    $dir = ($levels[1] - $levels[0]) > 0;

    for($i = 1; $i < count($levels); $i++) {
        $diff = $levels[$i] - $levels[$i - 1];
        if (($diff > 0) !== $dir) {
            return false;
        }
        if (abs($diff) < 1 || abs($diff) > 3) {
            return false;
        }
    }

    return true;
}