<?php

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
        $compare = $seen[$from[0]][$from[1]];
        $cy = $ny + $dy;
        $cx = $nx + $dx;
        if ($cy < 0 || $cy === $Y || $cx < 0 || $cx === $X) continue;
        if (isset($walls[$cy][$cx]) || !isset($seen[$cy][$cx])) continue;
        if ($compare > $seen[$cy][$cx] + 2) {
            $diff = $compare - $seen[$cy][$cx] - 2;
            $cuts[$diff][] = [$from, [$cy, $cx]];
        }
    }

    $from = $next;
}

$ans = 0;
foreach($cuts as $diff => $cutsList) if ($diff >= 100) $ans += count($cutsList);

echo 'First star: ' . $ans . PHP_EOL;