<?php

$grid = [];

foreach(file('input.txt', FILE_IGNORE_NEW_LINES) as $row) $grid[] = array_map(fn($v) => (int)($v === '.' ? -1 : $v), str_split($row));

$R = count($grid);
$C = count($grid[0]);

$ans1 = $ans2 = 0;

foreach($grid as $r => $row) {
    foreach($row as $c => $col) {
        if ($grid[$r][$c] !== 0) continue;
        $ans1 += score($r, $c, 0, true);
        $ans2 += score($r, $c, 0, false);
    }
}

printf('First star: %s%sSecond star: %s%s', $ans1, PHP_EOL, $ans2, PHP_EOL);

function score(int $r, int $c, int $from, bool $part1) {
    global $R, $C, $grid;
    static $peaks;

    if ($from === 0) $peaks = [];

    if ($grid[$r][$c] === 9) {
        if ($part1 && in_array([$r, $c], $peaks)) return 0;
        $peaks[] = [$r, $c];
        return 1;
    }

    $score = 0;

    foreach([[-1, 0], [1, 0], [0, -1], [0, 1]] as list($dr, $dc)) {
        if ((($r + $dr) === $R) || (($r + $dr) < 0) || (($c + $dc) === $C) || (($c + $dc) < 0)) continue;
        if ($grid[$r + $dr][$c + $dc] === ($from + 1)) $score += score($r + $dr, $c + $dc, $from + 1, $part1);
    }

    return $score;
}