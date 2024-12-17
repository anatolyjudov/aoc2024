<?php

$t0 = microtime(true);

$input = file('input.txt', FILE_IGNORE_NEW_LINES);
$program = array_map(fn($v) => (int)$v, explode(',', substr($input[4], 9)));

$output = run(substr($input[0], 12), $program);
echo 'First star: ' . implode(',', $output) . PHP_EOL;
echo 'Second star: ' . getA(0, [], 0, $program) . PHP_EOL;

$t1 = microtime(true);
printf ('Time %f sec%s', $t1 - $t0, PHP_EOL);

function getA($A, $output, $step, $program) {
    if ($step === count($program)) return $A;
    $nextOutput = array_reverse($program)[$step];
    $A = $A << 3;
    array_splice($output, 0, 0, $nextOutput);
    for($a = 0; $a < 8; $a++) {
        if (run($A + $a, $program) === $output) {
            $nextA = getA($A + $a, $output, $step + 1, $program);
            if ($nextA !== false) return $nextA;
        }
    }

    return false;
}

function run(int $A, array $executable): array {
    $B = $C = $ip = 0;
    $output = [];

    while($ip < count($executable) - 1) {
        $operand = $executable[$ip + 1];
        switch($executable[$ip]) {
            case '0':
                $A = $A >> combo($operand, $A, $B, $C);
                break;
            case '1':
                $B = $B ^ $operand;
                break;
            case '2':
                $B = combo($operand, $A, $B, $C) % 8;
                break;
            case '3':
                if ($A !== 0) $ip = $operand - 2;
                break;
            case '4':
                $B = $B ^ $C;
                break;
            case '5':
                $output[] = combo($operand, $A, $B, $C) % 8;
                break;
            case '6':
                $B = $A >> combo($operand, $A, $B, $C);
                break;
            case '7':
                $C = $A >> combo($operand, $A, $B, $C);
                break;
        }
        $ip += 2;
    }

    return $output;
}

function combo($combo, int $A, int $B, int $C) {
    return match($combo) {
        0, 1, 2, 3 => $combo,
        4 => $A,
        5 => $B,
        6 => $C,
        7 => die('reserved combo')
    };
}