<?php

list($gridInput, $moves) = explode(PHP_EOL . PHP_EOL, file_get_contents('input.txt'));
$moves = str_split(str_replace(PHP_EOL, '', $moves));

$boxes = $index = $robot = [];
foreach(explode(PHP_EOL, $gridInput) as $y => $line) {
    foreach(str_split($line) as $x => $v) {
        $x2 = $x * 2;
        if ($v === 'O') {
            $num = count($boxes);
            $boxes[$num] = [[$y, $x2], [$y, $x2 + 1]];
            $index[$y][$x2] = $num;
            $index[$y][$x2 + 1] = $num;
        } elseif ($v === '@') {
            $robot = [$y, $x2];
        } elseif ($v === '#') {
            $grid[$y][$x2] = '#';
            $grid[$y][$x2 + 1] = '#';
            continue;
        }
        $grid[$y][$x2] = '.';
        $grid[$y][$x2 + 1] = '.';
    }
}

foreach($moves as $move) {
    $dir = match ($move) {'^' => [-1, 0], '>' => [0, 1], 'v' => [1, 0], '<' => [0, -1]};

    $ny = $robot[0] + $dir[0];
    $nx = $robot[1] + $dir[1];

    if ($grid[$ny][$nx] === '#') continue;
    if (isset($index[$ny][$nx]) && !shiftbox([$ny, $nx], $dir)) continue;

    $robot = [$ny, $nx];
}

$ans = 0;
foreach($boxes as $box) $ans += $box[0][0] * 100 + $box[0][1];

echo 'Second star: ' . $ans . PHP_EOL;

function shiftbox(array $push, array $dir): bool
{
    global $grid, $boxes, $index;

    $toMove = [];
    $pushed = [$index[$push[0]][$push[1]]];
    while(count($pushed) > 0) {
        $boxnum = array_shift($pushed);
        $box = $boxes[$boxnum];
        foreach($box as $side) {
            $ny = $side[0] + $dir[0];
            $nx = $side[1] + $dir[1];
            if ($grid[$ny][$nx] === '#') return false;
            if (isset($index[$ny][$nx]) && ($index[$ny][$nx] !== $boxnum) && !in_array($index[$ny][$nx], $pushed)) {
                $pushed[] = $index[$ny][$nx];
            }
        }
        $toMove[] = $boxnum;
    }

    foreach($toMove as $boxNum) {
        $boxes[$boxNum] = [
            [$boxes[$boxNum][0][0] + $dir[0], $boxes[$boxNum][0][1] + $dir[1]],
            [$boxes[$boxNum][1][0] + $dir[0], $boxes[$boxNum][1][1] + $dir[1]],
        ];
    }

    $index = [];
    foreach($boxes as $boxnum => $box) {
        $index[$box[0][0]][$box[0][1]] = $boxnum;
        $index[$box[1][0]][$box[1][1]] = $boxnum;
    }

    return true;
}