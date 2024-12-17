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

$min = 9999999999;
for($t = 0; $t < $X * $Y; $t++) {
    $robotsT = [];
    $seen = [];
    $q = ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0];
    foreach($robots as $robot) {
        $q[getRobotQuadrant(getRobotAfter($robot, $t))]++;
    }
    $safetyFactor = $q[1] * $q[2] * $q[3] * $q[4];
    if ($t === 100) echo 'First star: ' . $safetyFactor . PHP_EOL;

    if ($safetyFactor < $min) {
        $min = $safetyFactor;
        $minT = $t;
    }
}

$robotsT = [];
foreach($robots as $robot) {
    $robotsT[] = getRobotAfter($robot, $minT);
}

show($robotsT);

echo 'Second star: ' . $minT . PHP_EOL;

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

function show(array $robots): void
{
    global $X, $Y;

    $rmap = [];
    foreach($robots as $robot) $rmap[$robot[0][1]][$robot[0][0]] = ($rmap[$robot[0][1]][$robot[0][0]] ?? 0) + 1;

    for($r = 0; $r < $Y; $r++) {
        for($c = 0; $c < $X; $c++) {
            if (isset($rmap[$r][$c])) {
                if ($rmap[$r][$c] >= 10) {
                    echo '*';
                } else {
                    echo $rmap[$r][$c];
                }
            } else {
                echo '.';
            }
        }
        echo PHP_EOL;
    }
}
