<?php

$cache = $bytes = [];
foreach(file('input.txt', FILE_IGNORE_NEW_LINES) as $n => $line) {
    list($x, $y) = array_map(fn($v) => (int)$v, explode(',', $line));
    $cache[$x][$y] = $n;
    $bytes[$n] = [$x, $y];
}

$xmax = $ymax = 70;
$t1 = 1024;

echo 'First star: ' . shortest($t1) . PHP_EOL;

for($t = count($bytes); shortest($t) === false; $t--) {}

echo 'Second star: ' . $bytes[$t + 1][0] . ',' . $bytes[$t + 1][1] . PHP_EOL;

function shortest(int $t): int|bool {
    global $xmax, $ymax;

    $q = new \DS\Deque();
    $q->push([0, 0, 0]);
    $seen = [];

    while($q->count() > 0) {
        list($x, $y, $len) = $q->shift();

        $seen[$x][$y] = $len;
        if ([$x, $y] === [$xmax, $ymax]) continue;

        foreach([[-1, 0], [0, 1], [1, 0], [0, -1]] as $dir) {
            $nx = $x + $dir[0];
            $ny = $y + $dir[1];
            if ($nx < 0 || $nx > $xmax || $ny < 0 || $ny > $ymax) continue;
            if (isCorrupted($nx, $ny, $t)) continue;
            if ($q->contains([$nx, $ny, $len + 1])) continue;
            if (isset($seen[$nx][$ny]) && $seen[$nx][$ny] <= $len) continue;
            $q->push([$nx, $ny, $len + 1]);
        }
    }

    if (!isset($seen[$xmax][$ymax])) return false;
    return $seen[$xmax][$ymax];
}

function isCorrupted($x, $y, $t): bool {
    global $cache;
    return isset($cache[$x][$y]) && $cache[$x][$y] <= $t;
}