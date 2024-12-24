<?php

/*
 * No fully automatic solution today.
 * This program finds broken bits and show it in that way, so it's possible to find mistakes manually (which I did solving the task).
 * This analysis for sure can be automated, but I don't want to do that.
 */

// $a = ['swt', 'z07', 'pqc', 'z13', 'wsv', 'rjm', 'bgs', 'z31'];
// sort($a); echo implode(',', $a) . PHP_EOL; exit;

list($valuesInput, $gatesInput) = explode(PHP_EOL.PHP_EOL, file_get_contents('input.txt'));

$values = $gates = $wires = [];
foreach(explode(PHP_EOL, $valuesInput) as $valInput) {
    list($wire, $value) = explode(': ', $valInput);
    $values[$wire] = (int)$value;
    $wires[] = $wire;
}
foreach(explode(PHP_EOL, trim($gatesInput)) as $gateInput) {
    if (!preg_match("/(\w{3})\s(OR|XOR|AND)\s(\w{3})\s->\s(\w{3})/", $gateInput, $matches)) die('can\'t read: ' . $gateInput);
    $gates[$matches[4]] = [[$matches[1], $matches[3]], $matches[2]];
    $wires[] = $matches[4];
}
foreach($wires as $wire) {
    if ($wire[0] === 'x') $X = ($X ?? 0) + 1;
    if ($wire[0] === 'y') $Y = ($Y ?? 0) + 1;
    if ($wire[0] === 'z') $Z = ($Z ?? 0) + 1;
}

part1();
part2($gates);

function part2(array $gates0)
{
    echo 'Those are two example gates that was completely normal in my input, just as an example.' . PHP_EOL . PHP_EOL;
    echo showGate('z05') . PHP_EOL;
    echo showGate('z06') . PHP_EOL;

    echo 'Down below are broken gates with all their logic. Comparing to normal ones it should be easy to find four mistakes.' . PHP_EOL . PHP_EOL;
    $compromisedByBit = findCompromised($gates0);
    foreach($compromisedByBit as $bit => $gate) {
        echo 'Bit ' . $bit . PHP_EOL;

        $zGate = 'z' . str_pad((string)$bit, 2, '0', STR_PAD_LEFT);

        echo showGate($zGate);
    }
}

function showGate(string $gate, $level = 0)
{
    global $gates;
    if (!isset($gates[$gate])) return '';

    $res = '';

    $res .= str_repeat('   ', $level);
    $res .= $gate . ' = ' . $gates[$gate][0][0] . ' ' . $gates[$gate][1] . ' ' . $gates[$gate][0][1] . PHP_EOL;
    $l = showGate($gates[$gate][0][0], $level + 1);
    $r = showGate($gates[$gate][0][1], $level + 1);

    if (strlen($l) > strlen($r)) {
        $res .= $r . $l;
    } else {
        $res .= $l . $r;
    }

    return $res;
}

function findCompromised(array $gates): array
{
    global $X, $Y, $Z;

    $ensured = new \Ds\Set();
    $compromisedByBit = [];
    $testBit = 0;
    while($testBit < $X) {
        $zGate = 'z' . str_pad((string)$testBit, 2, '0', STR_PAD_LEFT);
        $tryGates = resolveGates($zGate);
        $tryGates = $tryGates->diff($ensured);

        foreach([[0, 0], [1, 0], [0, 1], [1, 1]] as list($tryX, $tryY)) {
            $tryInputs = getInputs($tryX << $testBit, $tryY << $testBit);
            $tryZ = tryWire($zGate, $tryInputs);
            if ($tryZ !== ($tryX ^ $tryY)) {
                if ($tryGates->count() > 0) {
                    $compromisedByBit[$testBit] = $tryGates;
                } else {
                    $compromisedByBit[$testBit] = new \Ds\Set();
                    $compromisedByBit[$testBit]->add($zGate);
                }
                $testBit++;
                continue 2;
            }
        }
        $ensured = $ensured->union($tryGates);
        $testBit++;
    }

    return $compromisedByBit;
}

function resolveGates(string $gate): \Ds\Set
{
    global $gates;

    $res = new \Ds\Set();
    $left = $gates[$gate][0][0];
    $right = $gates[$gate][0][1];
    if (isset($gates[$left])) {
        $res->add($left);
        $res = $res->union(resolveGates($left));
    }
    if (isset($gates[$right])) {
        $res->add($right);
        $res = $res->union(resolveGates($right));
    }
    
    return $res;
}

function getInputs(int $x, int $y): array
{
    global $X, $Y;

    $xbits = str_split(strrev(decbin($x)));
    $ybits = str_split(strrev(decbin($y)));

    for($i = 0; $i < $X; $i++) {
        $res['x'.str_pad((string)$i, 2, '0', STR_PAD_LEFT)] = $xbits[$i] ?? 0;
    }
    for($i = 0; $i < $Y; $i++) {
        $res['y'.str_pad((string)$i, 2, '0', STR_PAD_LEFT)] = $ybits[$i] ?? 0;
    }

    return $res;
}

function tryWire(string $wire, array &$inputs): int
{
    global $gates;
    
    if (isset($inputs[$wire])) return $inputs[$wire];

    return match($gates[$wire][1]) {
        'AND' => tryWire($gates[$wire][0][0], $inputs) & tryWire($gates[$wire][0][1], $inputs),
        'OR' => tryWire($gates[$wire][0][0], $inputs) | tryWire($gates[$wire][0][1], $inputs),
        'XOR' => tryWire($gates[$wire][0][0], $inputs) ^ tryWire($gates[$wire][0][1], $inputs),
    };
}

function part1(): void
{
    global $wires, $values;
    $num = [];
    foreach($wires as $wire) if ($wire[0] === 'z') $num[(int)substr($wire, 1)] = tryWire($wire, $values);
    krsort($num);
    echo 'First star: ' . bindec(implode('', $num)) . PHP_EOL;
}