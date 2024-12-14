<?php

// This would work only with my input

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

/*
 * Here's some christmas magic (and I don't understand fully the math behind).
 * Each 103 sec there's a horizontal resonance, and each 101 src there's a vertical resonance.
 * The first shifed at 58 from the beginning, the second is shifted 99 from the beginning.
 * I've found that visually, just looking at the dumps.
 * We can calculate when both resonances will happen at the same time. 
 * This would be a moment when 58 + 103 * n will be equal to 99 + 101 * m, given that both m and n are ints.
 */
for($m = 0; !isset($n) || !is_int($n); $m++) $n = (101 * $m + (99 - 58)) / 103;
$t = 58 + 103 * $n;

$robotsT = [];
foreach($robots as $robot) {
    $robotsT[] = getRobotAfter($robot, $t);
}

show($robotsT);
printf('Second star: %s%s', $t, PHP_EOL);

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
