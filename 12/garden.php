<?php

$grid = $seen = $regions = [];

$grid = array_map(fn($v) => str_split($v), file('input.txt', FILE_IGNORE_NEW_LINES));

$R = count($grid);
$C = count($grid[0]);

for($y = 0; $y < $R; $y++) for($x = 0; $x < $C; $x++) {
    if (isset($seen[$y][$x])) continue;
    $region = ['area' => 0, 'perimeter' => 0, 'corners' => 0, 'plant' => $grid[$y][$x]];
    $q = new \Ds\Deque();
    $q->push([$y, $x]);
    while(count($q) > 0) {
        list($r, $c) = $q->shift();
        $region['area']++;
        $seen[$r][$c] = $region['plant'];
        foreach([[1, 0], [-1, 0], [0, 1], [0, -1]] as list($dr, $dc)) {
            $nr = $r + $dr;
            $nc = $c + $dc;
            if ($nr < 0 || $nr === $R || $nc < 0 || $nc === $C) {
                $region['perimeter']++;
                continue;
            }
            if ($grid[$nr][$nc] !== $region['plant']) $region['perimeter']++;
            if (isset($seen[$nr][$nc]) || $q->contains([$nr, $nc])) continue;
            if ($grid[$nr][$nc] !== $region['plant']) continue;
            $q->push([$nr, $nc]);
        }
        foreach([[[-1, 0], [0, 1]], [[0, 1], [1, 0]], [[1, 0], [0, -1]], [[0, -1], [-1, 0]]] as $tries) {
            $p0 = $grid[$r + $tries[0][0]][$c + $tries[0][1]] ?? ' ';
            $p2 = $grid[$r + $tries[1][0]][$c + $tries[1][1]] ?? ' ';
            $p1 = $grid[$r + $tries[1][0] + $tries[0][0]][$c + $tries[1][1] + $tries[0][1]] ?? ' ';
            if ($p0 !== $region['plant'] && $p2 !== $region['plant']) $region['corners']++;
            if ($p0 === $region['plant'] && $p2 === $region['plant'] && $p1 !== $region['plant']) $region['corners']++;
        }
    }
    $regions[] = $region;
}

$ans1 = $ans2 = 0;
foreach($regions as $region) {
    $ans1 += $region['area'] * $region['perimeter'];
    $ans2 += $region['area'] * $region['corners'];
}

printf('First star: %s%sSecond star: %s%s', $ans1, PHP_EOL, $ans2, PHP_EOL);