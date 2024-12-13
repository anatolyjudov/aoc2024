<?php

$input = file('input.txt', FILE_IGNORE_NEW_LINES);

$rules = [];
for($i = 0; $i < count($input); $i += 4) {
    list($d1, $d2, $xv, $yv) = explode(' ', $input[$i]);
    $a = [explode('+', substr($xv, 0, -1))[1], explode('+', $yv)[1]];

    list($d1, $d2, $xv, $yv) = explode(' ', $input[$i + 1]);
    $b = [explode('+', substr($xv, 0, -1))[1], explode('+', $yv)[1]];

    list($d, $xv, $yv) = explode(' ', $input[$i + 2]);
    $p = [explode('=', substr($xv, 0, -1))[1], explode('=', $yv)[1]];

    $rules[] = [
        'a' => $a, 'b' => $b, 'prize' => $p
    ];
}

$ans1 = $ans2 = 0;

foreach($rules as $rule) {
    $a = ($rule['prize'][0] * $rule['b'][1] - $rule['b'][0] * $rule['prize'][1]) /
        ($rule['a'][0] * $rule['b'][1] - $rule['b'][0] * $rule['a'][1]);
    if (!is_int($a)) continue;
    $b = ($rule['prize'][0] - $rule['a'][0] * $a) / $rule['b'][0];
    if (!is_int($b)) continue;
    $ans1 += $a * 3 + $b;
}
echo $ans1 . PHP_EOL;


foreach($rules as $rule) {
    $rule['prize'][0] += 10000000000000;
    $rule['prize'][1] += 10000000000000;
    $a = ($rule['prize'][0] * $rule['b'][1] - $rule['b'][0] * $rule['prize'][1]) /
        ($rule['a'][0] * $rule['b'][1] - $rule['b'][0] * $rule['a'][1]);
    if (!is_int($a)) continue;
    $b = ($rule['prize'][0] - $rule['a'][0] * $a) / $rule['b'][0];
    if (!is_int($b)) continue;
    $ans2 += $a * 3 + $b;
}
echo $ans2 . PHP_EOL;