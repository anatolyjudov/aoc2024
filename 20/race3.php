<?php

include('../utils.php');

$walls = [];
foreach(file('input.txt', FILE_IGNORE_NEW_LINES) as $y => $line) {
    foreach(str_split($line) as $x => $v) {
        if ($v === 'S') $start = [$y, $x];
        elseif ($v === 'E') $finish = [$y, $x];
        elseif ($v === '#') $walls[$y][$x] = '#';
    }
}

$Y = $y + 1;
$X = strlen($line);

$q = new \Ds\Deque();
$q->push([$start, 0]);

$seen = [];
while($q->count() > 0) {
    $p = $q->shift();

    $pos = $p[0];
    $score = $p[1];

    if (isset($seen[$pos[0]][$pos[1]]) && $seen[$pos[0]][$pos[1]] <= $score) continue;
    $seen[$pos[0]][$pos[1]] = $score;

    foreach([[-1, 0], [0, 1], [1, 0], [0, -1]] as list($dy, $dx)) {
        $ny = $pos[0] + $dy;
        $nx = $pos[1] + $dx;
        if (isset($walls[$ny][$nx])) continue;
        $q->push([[$ny, $nx], $score + 1]);
    }
}

$from = $finish;
$seen2 = [];
$cuts = [];
while($from !== $start) {
    $seen2[$from[0]][$from[1]] = 1;

    $next = false;
    foreach([[-1, 0], [0, 1], [1, 0], [0, -1]] as list($dy, $dx)) {
        $ny = $from[0] + $dy;
        $nx = $from[1] + $dx;
        if (isset($seen2[$ny][$nx])) continue;
        if (!isset($walls[$ny][$nx])) {
            $next = [$ny, $nx];
            continue;
        }
    }

    $compare = $seen[$from[0]][$from[1]];

    for($dy = -20; $dy <= 20; $dy++) {
        for($dx = abs($dy) - 20; $dx <= 20 - abs($dy); $dx++) {
            if ($dy === 0 && $dx === 0) continue;
            $d = abs($dy) + abs($dx);
            if ($d > 20) continue;

            $ny = $from[0] + $dy;
            $nx = $from[1] + $dx;
            if ($ny < 0 || $ny === $Y || $nx < 0 || $nx === $X) continue;

            if (!isset($seen[$ny][$nx])) continue;

            if ($compare > $seen[$ny][$nx] + $d) {
                $diff = $compare - $seen[$ny][$nx] - $d;
                if ($diff < 100) continue;
                $cuts[$diff] = ($cuts[$diff] ?? 0) + 1;
            }
        }
    }

    $from = $next;
}

$ans = 0;
foreach($cuts as $diff => $cutsCount) $ans += $cutsCount;

echo 'Second star: ' . $ans . PHP_EOL;