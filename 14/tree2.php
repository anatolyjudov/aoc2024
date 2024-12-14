<?php

$input = file('input.txt', FILE_IGNORE_NEW_LINES);
$X = 101; $Y = 103;

$robots = [];
foreach($input as $line) {
    list($p, $v) = explode(' ', $line);
    $robots[] = [
        array_map(fn($v) => (int)$v, explode(',', substr($p, 2))),
        array_map(fn($v) => (int)$v, explode(',', substr($v, 2))),
    ];
}

for($t = 0; $t < $X * $Y; $t++) {
    $robotsT = [];
    $seen = [];
    foreach($robots as $robot) {
        $robotT = getRobotAfter($robot, $t);
        $seen[implode(',',$robotT[0])] = 1;
    }
    if (count($seen) === count($robots)) {
        echo 'Second star: ' . $t . PHP_EOL;
        exit;
    }
}

function getRobotAfter(array $robot, int $t): array
{
    global $X, $Y;

    $dx = $robot[1][0] * $t;
    $x = ($robot[0][0] + $dx) % $X;
    if ($x < 0) $x = $X + $x;
    $dy = $robot[1][1] * $t;
    $y = ($robot[0][1] + $dy) % $Y;
    if ($y < 0) $y = $Y + $y;

    return [[$x, $y], $robot[1]];
}
