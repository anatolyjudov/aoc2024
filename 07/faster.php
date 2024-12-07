<?php

$ans1 = $ans2 = 0;

foreach(file('input.txt', FILE_IGNORE_NEW_LINES) as $num => $line) {
    echo $num . " ";

    list($test, $values) = explode(': ', $line);
    $values = array_map( function ($v) { return (int) $v; }, explode(' ', $values));

    $ans1 += calibrate(2, (int) $test, $values);
    $ans2 += calibrate(3, (int) $test, $values);
}

printf('%sFirst star: %s%sSecond star: %s%s', PHP_EOL, $ans1, PHP_EOL, $ans2, PHP_EOL);

function calibrate($base, $test, $values): int
{
    $opsCount = count($values) - 1;

    foreach(casesProvider($base, $opsCount) as $operators) {
        $try = $values[0];
        for($o = 0; $o < $opsCount; $o++) {
            switch($operators[$o]) {
                case '0': $try = $try + $values[$o + 1]; break;
                case '1': $try = $try * $values[$o + 1]; break;
                case '2': $try = (int)((string)$try . (string)$values[$o + 1]); break;
            }
            if ($try > $test) continue 2;
        }

        if ($try === $test) return $test;
    }

    return 0;
}

function casesProvider($base, $opsCount): \Generator
{
    for ($ii = 0; $ii < $base ** $opsCount; $ii++) {
        $case = $ii;

        $str = '';
        for ($i = $opsCount - 1; $i >= 0; $i--) {
            $power = ($base ** $i);
            $r = ($case - $case % $power) / $power;
            $case = $case % $power;
            $str .= (string)$r;
        }

        yield $str;
    }
}