<?php

$input = file('input.txt', FILE_IGNORE_NEW_LINES);

$Y = count($input);
$X = strlen($input[0]);

$antennas = $antinodes1 = $antinodes2 = [];

foreach($input as $y => $line)
    for($x = 0; $x < strlen($line); $x++)
        if ($line[$x] !== '.') $antennas[$line[$x]][] = [$y, $x];

foreach($antennas as $frequency => $places) {
    for($i = 0; $i < count($places) - 1; $i++) {
        for($ii = $i + 1; $ii < count($places); $ii++) {
            $p1 = $places[$i];
            $p2 = $places[$ii];
            $diffY = $p2[0] - $p1[0];
            $diffX = $p2[1] - $p1[1];

            // part 1
            foreach([[$p1[0] - $diffY, $p1[1] - $diffX], [$p2[0] + $diffY, $p2[1] + $diffX]] as $node)
                if ($node[0] >= 0 && $node[0] < $Y && $node[1] >= 0 && $node[1] < $X) 
                    $antinodes1[$node[0]][$node[1]] = 1;

            // part 2
            foreach([-1, 1] as $dir) {
                $antinodes2[$p1[0]][$p1[1]] = 1;
                $step = 1;
                while (true) {
                    $node = [$p1[0] + $dir * $step * $diffY, $p1[1] + $dir * $step * $diffX];
                    if ($node[0] < 0 || $node[0] >= $Y || $node[1] < 0 || $node[1] >= $X) break;
                    $antinodes2[$node[0]][$node[1]] = 1;
                    $step++;
                }
            }

        }
    }
}

$ans1 = array_reduce($antinodes1, fn ($c, $i) => $c + count($i), 0);
$ans2 = array_reduce($antinodes2, fn ($c, $i) => $c + count($i), 0);

printf('First star: %s%sSecond star: %s%s', $ans1, PHP_EOL, $ans2, PHP_EOL);