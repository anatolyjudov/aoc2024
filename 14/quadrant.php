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

$q = [];
$t = 100;

foreach($robots as $robot) {
    $robot100 = getRobotAfter($robot, $t);
    $robots100[] = $robot100;
    $x = $robot100[0][0];
    $y = $robot100[0][1];
    $quad = getRobotQuadrant($robot100);
    $q[$quad] = ($q[$quad] ?? 0) + 1;
}

$ans = 1;
foreach($q as $quad => $r) {
    if ($quad === 0) continue;
    $ans = $ans * $r;
}
echo 'First star: ' . $ans . PHP_EOL;

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

function getRobotQuadrant(array $robot): string
{
    global $X, $Y;

    $x = $robot[0][0];
    $y = $robot[0][1];

    $qx = $qy = 0;
    if ($x < ($X - 1) / 2) {
        $qx = -1;
    } elseif ($x > ($X - 1) / 2) {
        $qx = 1;
    }
    if ($y < ($Y - 1) / 2) {
        $qy = -1;
    } elseif ($y > ($Y - 1) / 2) {
        $qy = 1;
    }

    if ($qy === -1) {
        if ($qx === -1) return 1;
        if ($qx === 1) return 2;
    }
    if ($qy === 1) {
        if ($qx === -1) return 3;
        if ($qx === 1) return 4;
    }

    return 0;
}