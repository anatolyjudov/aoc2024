<?php

$input = array_map(fn($v) => (int) $v, explode(' ', trim(file_get_contents('input.txt'))));

$stones = [];

foreach($input as $stone) $stones[$stone] = ($stones[$stone] ?? 0) + 1;

printf('First star: %s%sSecond star: %s%s', blink(25, $stones), PHP_EOL, blink(75, $stones), PHP_EOL);

function blink(int $times, array $stones): int
{
    for($blink = 0; $blink < $times; $blink++) {
        $new_stones = [];
        foreach($stones as $stone => $amount) {
            if ($stone === 0) {
                $new_stones[1] = ($new_stones[1] ?? 0) + $amount;
                continue;
            }
            if (strlen((string)$stone) % 2 === 0) {
                list($a, $b) = str_split((string)$stone, strlen((string)$stone) / 2);
                $a = (int)$a;
                $b = (int)$b;
                $new_stones[$a] = ($new_stones[$a] ?? 0) + $amount;
                $new_stones[$b] = ($new_stones[$b] ?? 0) + $amount;
                continue;
            }
            $new_value = $stone * 2024;
            $new_stones[$new_value] = ($new_stones[$new_value] ?? 0) + $amount;
        }
        $stones = $new_stones;
    }
    return array_sum($stones);
}