<?php

$input = file('input.txt', FILE_IGNORE_NEW_LINES);

$piles0 = $guard0 = $pos = [];

$dirs = [[-1, 0], [0, 1], [1, 0], [0, -1]];

$X = strlen($input[0]);
$Y = count($input);

for($y = 0; $y < $Y; $y++) {
    for($x = 0; $x < $X; $x++) {
        switch($input[$y][$x]) {
            case '^': $guard0 = [$y, $x]; break;
            case '#': $piles0[$y][$x] = 1; break;
        }
    }
}

$guard = $guard0;
$dir = 0;
$piles = $piles0;

while ($guard[0] >= 0 && $guard[0] < $Y && $guard[1] >= 0 && $guard[1] < $X) {
    $pos[implode(',', $guard)][$dir] = 1;

    do {
        $newY = $guard[0] + $dirs[$dir][0];
        $newX = $guard[1] + $dirs[$dir][1];
        if (!isset($piles[$newY][$newX])) {
            break;
        }
        $dir = ($dir + 1) % 4;
    } while (true);

    $guard = [$newY, $newX];
};

echo 'First star: ' . count($pos) . PHP_EOL;

$count = 0;

foreach(array_keys($pos) as $obsKey)
{
    $obs = explode(',', $obsKey);
    if ($obs === $guard0) continue;

    $guard = $guard0;
    $dir = 0;
    $piles = $piles0;
    $piles[$obs[0]][$obs[1]] = 1;

    $path = [];

    do {
        do {
            $newY = $guard[0] + $dirs[$dir][0];
            $newX = $guard[1] + $dirs[$dir][1];
            if (!isset($piles[$newY][$newX])) {
                break;
            }
            $dir = ($dir + 1) % 4;
        } while (true);

        $guard = [$newY, $newX];

        if (isset($path[implode(',', $guard)][$dir])) {
            $count++;
            continue 2;
        }

        $path[implode(',', $guard)][$dir] = 1;

    } while ($guard[0] >= 0 && $guard[0] < $Y && $guard[1] >= 0 && $guard[1] < $X);
}

echo 'Second star: ' . $count . PHP_EOL;