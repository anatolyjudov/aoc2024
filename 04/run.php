<?php

$ans1 = $ans2 = 0;
$map = [];

foreach(file('input.txt', FILE_IGNORE_NEW_LINES) as $line) {
    $map[] = str_split($line, 1);
}

for($y = 0; $y < count($map); $y++) {
    for($x = 0; $x < count($map[0]); $x++) {
        switch($map[$y][$x]) {
            case 'X': $ans1 += findXmas($map, $y, $x); break;
            case 'A': $ans2 += findMas($map, $y, $x) ? 1 : 0; break;
        }
    }
}

printf('First star: %s%sSecond star: %s%s', $ans1, PHP_EOL, $ans2, PHP_EOL);

function findMas(&$map, $y, $x) {
    $res = 0;

    foreach([[-1, -1], [1, 1], [1, -1], [-1, 1]] as $p) {
        if ((isset($map[$y + $p[0]][$x + $p[1]]) && $map[$y + $p[0]][$x + $p[1]] === 'M')
        && (isset($map[$y - $p[0]][$x - $p[1]]) && $map[$y - $p[0]][$x - $p[1]] === 'S') ) {
            $res++;
        }
    }

    return $res === 2;
}

function findXmas(&$map, $y, $x) {
    $dirs = [[1, 0], [0, 1], [-1, 0], [0, -1], [1, 1], [-1, 1], [-1, -1], [1, -1]];
    $letters = ['M', 'A', 'S'];
    $res = 0;

    foreach($dirs as $dir) {
        for($s = 1; $s <= 3; $s++)
            if ((!isset($map[$y + $dir[0]*$s][$x + $dir[1]*$s])) || ($map[$y + $dir[0]*$s][$x + $dir[1]*$s] !== $letters[$s - 1]))
                continue 2;
        
        $res++;
    }

    return $res;
}