<?php

$input =  file('input.txt', FILE_IGNORE_NEW_LINES);

$deer = $finish = [];
foreach($input as $r => $line) {
    foreach(str_split($line) as $c => $v) {
        if ($v === 'S') {
            $deer = [$r, $c, [0, 1]];
            $v = '.';
        }
        if ($v === 'E') {
            $finish = [$r, $c, [0, 1]];
            $v = '.';
        }
        $maze[$r][$c] = $v;
    }
}

$ans = lowest_score($deer, []);

echo $ans;

function lowest_score(array $deer, array $seen)
{
    global $maze, $finish;

    if ($deer[0] === $finish[0] && $deer[1] === $finish[1]) return 0;
    
    $seen[$deer[0]][$deer[1]] = 1;
    
    switch($deer[2]) {
        case [0, 1]:
            $possible = [[0, 1], [-1, 0], [1, 0]];
            break;
        case [0, -1]:
            $possible = [[0, -1], [1, 0], [-1, 0]];
            break;
        case [1, 0]:
            $possible = [[1, 0], [0, 1], [0, -1]];
            break;
        case [-1, 0]:
            $possible = [[-1, 0], [0, 1], [0, -1]];
            break;
    }

    $scoreBest = false;
    foreach($possible as $d => $dir) {
        $new_deer = [$deer[0] + $dir[0], $deer[1] + $dir[1], $dir];

        if ($maze[$new_deer[0]][$new_deer[1]] === '#') continue;
        if (isset($seen[$new_deer[0]][$new_deer[1]])) continue;
        $scorePath = lowest_score($new_deer, $seen);

        if ($scorePath === false) continue;
        $scoreMove = ($d > 0) ? 1001 : 1;
        $scoreBest = ($scoreBest === false)
            ? ($scoreMove + $scorePath)
            : min($scoreBest, $scoreMove + $scorePath);
    }

    if ($scoreBest === false) return false; // no possible moves

    return $scoreBest;
}