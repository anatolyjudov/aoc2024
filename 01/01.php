<?php

$inputLines = file('input.txt');

$left = $right = array();

foreach($inputLines as $line) {
    $parts = explode('   ', trim($line));
    $left[] = $parts[0];
    $right[] = $parts[1];
}

sort($left);
sort($right);

$sum = 0;
for($i = 0; $i < count($left); $i++) {
    $sum += abs($left[$i] - $right[$i]);
}

echo $sum . PHP_EOL;