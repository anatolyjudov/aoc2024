<?php

$input =  file('input.txt', FILE_IGNORE_NEW_LINES);

$deer = $finish = $bestPlaces = [];

foreach($input as $r => $line) foreach(str_split($line) as $c => $v)
    if ($v === 'S') {
        $deer = [$r, $c, [0, 1], 0];
        $maze[$r][$c] = '.';
    } elseif ($v === 'E') {
        $finish = [$r, $c, [0, 1]];
        $maze[$r][$c] = '.';
    } else {
        $maze[$r][$c] = $v;
    }

$q = new \Ds\Deque();
$q->push($deer);

while($q->count() > 0) {
    $d = $q->shift();

    $d2 = implode(',', $d[2]);
    if (isset($scores[$d[0]][$d[1]][$d2]) && $scores[$d[0]][$d[1]][$d2] <= $d[3]) continue;
    $scores[$d[0]][$d[1]][$d2] = $d[3];

    foreach(getDirs($d[2]) as $dNum => $dir) {
        $d_new = [$d[0] + $dir[0], $d[1] + $dir[1], $dir, $d[3] + (($dNum > 0) ? 1001 : 1)];
        if ($maze[$d_new[0]][$d_new[1]] === '#') continue;
        $q->push($d_new);
    }
}

$bestScore = min($scores[$finish[0]][$finish[1]]);

echo 'First star: ' . $bestScore . PHP_EOL;
checkPaths($deer, []);
echo 'Second star: ' . count($bestPlaces) + 1 . PHP_EOL;

function checkPaths(array $deer, array $seen)
{
    global $maze, $finish, $scores, $bestScore, $bestPlaces;

    if ($deer[0] === $finish[0] && $deer[1] === $finish[1]) {
        if ($deer[3] > $bestScore) return;
        foreach($seen as $r => $cols) foreach($cols as $c => $_dirs) $bestPlaces[$r.','.$c] = 1;
        return;
    }

    $d2 = implode(',', $deer[2]);
    if ($scores[$deer[0]][$deer[1]][$d2] < $deer[3]) return;
    $seen[$deer[0]][$deer[1]][$d2] = 1;

    foreach(getDirs($deer[2]) as $d => $dir) {
        $new_deer = [$deer[0] + $dir[0], $deer[1] + $dir[1], $dir, $deer[3] + (($d > 0) ? 1001 : 1)];
        if ($maze[$new_deer[0]][$new_deer[1]] === '#') continue;
        if (isset($seen[$new_deer[0]][$new_deer[1]])) continue;
        checkPaths($new_deer, $seen);
    }
}

function getDirs(array $dir): array
{
    return match($dir) {
        [0,  1] => [[ 0,  1], [-1, 0], [ 1,  0]],
        [0, -1] => [[ 0, -1], [ 1, 0], [-1,  0]],
        [ 1, 0] => [[ 1,  0], [ 0, 1], [ 0, -1]],
        [-1, 0] => [[-1,  0], [ 0, 1], [ 0, -1]]
    };
}