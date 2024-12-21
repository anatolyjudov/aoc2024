<?php

$input = file('input.txt', FILE_IGNORE_NEW_LINES);

$ans1 = $ans2 = 0;
foreach($input as $code) {
    $ans1 += getSequenceLength($code, 2) * (int)substr($code, 0, -1);
    $ans2 += getSequenceLength($code, 25) * (int)substr($code, 0, -1);
}

echo 'First star: ' . $ans1 . PHP_EOL;
echo 'Second star: ' . $ans2 . PHP_EOL;

function getSequenceLength(string $code, $depth): int
{
    foreach(getCodePrograms($code) as $program) {
        $len = 0;
        for($i = 0; $i < strlen($program); $i++)
            $len += moveLen($program[$i - 1] ?? 'A', $program[$i], $depth);
        $min = isset($min) ? min($len, $min) : $len;
    }

    return $min;
}

function moveLen($from, $to, $depth)
{
    static $cache;
    if (isset($cache[$from][$to][$depth])) return $cache[$from][$to][$depth];

    $moves = resolveMoves($from, $to, 1);

    if ($depth === 1) return strlen($moves[0]); // all moves are same length here

    foreach($moves as $move) {
        $len = 0;
        for($i = 0; $i < strlen($move); $i++)
            $len += moveLen($move[$i - 1] ?? 'A', $move[$i], $depth - 1);
        $min = isset($min) ? min($len, $min) : $len;
    }

    $cache[$from][$to][$depth] = $min;
    return $min;
}

function getCodePrograms(string $code): array
{
    $programs = [];

    for($i = 0; $i < strlen($code); $i++) {
        $moves = resolveMoves($code[$i - 1] ?? 'A', $code[$i], 0);
        if ($programs === []) {
            $programs = $moves;
        } else {
            $newPrograms = [];
            foreach($moves as $move) foreach($programs as $program) // moves[] X programs[]
                $newPrograms[] = $program . $move; 
            $programs = $newPrograms;
        }
    }

    return $programs;
}

function resolveMoves(string $from, string $to, int $k)
{
    static $m = [
        [
            '7' => [0, 0], '8' => [1, 0], '9' => [2, 0],
            '4' => [0, 1], '5' => [1, 1], '6' => [2, 1],
            '1' => [0, 2], '2' => [1, 2], '3' => [2, 2],
                           '0' => [1, 3], 'A' => [2, 3],
        ],
        [
                           '^' => [1, 0], 'A' => [2, 0],
            '<' => [0, 1], 'v' => [1, 1], '>' => [2, 1],
        ]
    ];
    static $movesCache;

    if (isset($movesCache[$from.$to.$k])) return $movesCache[$from.$to.$k];

    $moves = getMoves($m[$k][$from], $m[$k][$to], $k);
    
    $movesCache[$from.$to.$k] = $moves;
    return $moves;
}

function getMoves(array $from, array $to, $k): array
{    
    if ($from === $to) return ['A'];

    $dirs = [];
    if ($from[0] !== $to[0]) $dirs[] = [($to[0] - $from[0]) / abs($to[0] - $from[0]), 0];
    if ($from[1] !== $to[1]) $dirs[] = [0, ($to[1] - $from[1]) / abs($to[1] - $from[1])];

    $moves = [];
    foreach($dirs as list($dx, $dy)) {
        list($nx, $ny) = [$from[0] + $dx, $from[1] + $dy];
        
        if ($k === 0) {
            if (($nx === 0 && $ny === 3) || ($nx < 0 || $nx === 3 || $ny < 0 || $ny === 4)) continue;
        } else {
            if (($nx === 0 && $ny === 0) || ($nx < 0 || $nx === 3 || $ny < 0 || $ny === 4)) continue;
        }

        $move = match([$dx, $dy]) { [-1, 0] => '<', [1, 0] => '>', [0, -1] => '^', [0, 1] => 'v'};

        foreach(getMoves([$nx, $ny], $to, $k) as $nextMoves) $moves[] = $move . $nextMoves;
    }

    return $moves;
}
