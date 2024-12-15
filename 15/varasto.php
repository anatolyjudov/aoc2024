<?php

list($gridInput, $moves) = explode(PHP_EOL . PHP_EOL, file_get_contents('input.txt'));
$moves = str_split(str_replace(PHP_EOL, '', $moves));

$boxes = $robot = [];
foreach(explode(PHP_EOL, $gridInput) as $y => $line) {
    foreach(str_split($line) as $x => $v) switch($v) {
        case 'O':
            $boxes[] = [$y, $x];
            $grid[$y][$x] = '.';
            break;
        case '@':
            $robot = [$y, $x];
            $grid[$y][$x] = '.';
            break;
        default:
            $grid[$y][$x] = $v;
    }
}

foreach($moves as $move) {
    $dir = match ($move) {'^' => [-1, 0], '>' => [0, 1], 'v' => [1, 0], '<' => [0, -1]};

    $ny = $robot[0] + $dir[0];
    $nx = $robot[1] + $dir[1];

    if ($grid[$ny][$nx] === '#') continue;
    if (in_array([$ny, $nx], $boxes) && !shiftbox([$ny, $nx], $dir)) continue;

    $robot = [$ny, $nx];
}

$ans = 0;
foreach($boxes as $box) $ans += $box[0] * 100 + $box[1];
echo 'First star: ' . $ans . PHP_EOL;

function shiftbox(array $coords, array $dir): bool
{
    global $grid, $boxes;

    $q = [array_search($coords, $boxes)];
    $ny = $coords[0];
    $nx = $coords[1];
    while(true) {
        $ny += $dir[0];
        $nx += $dir[1];
        if ($grid[$ny][$nx] === '#') return false;
        $box7 = array_search([$ny, $nx], $boxes);
        if ($box7 !== false) {
            $q[] = $box7;
            continue;
        }
        if ($grid[$ny][$nx] === '.') break;
    }

    foreach($q as $boxNum) $boxes[$boxNum] = [$boxes[$boxNum][0] + $dir[0], $boxes[$boxNum][1] + $dir[1]];

    return true;
}