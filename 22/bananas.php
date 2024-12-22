<?php
ini_set('memory_limit', '300M');

$initialSecrets = file('input.txt', FILE_IGNORE_NEW_LINES);

$ans1 = 0;
$prices = [];
foreach($initialSecrets as $monkey => $secret) {
    $secret = (int)$secret;

    $sec = $secret;
    $changes = [];
    for($i = 0; $i < 2000; $i++) {
        $newSec = calc($sec);
        $changes[] = ($newSec % 10) - ($sec % 10);
        $sec = $newSec;
        if (count($changes) > 3) {
            $key = implode(',', array_slice($changes, -4));
            if (!isset($prices[$key][$monkey])) {
                $prices[$key][$monkey] = $newSec % 10;
            }
        }
    }
    
    $ans1 += $sec;

    echo $monkey . ': ' . $secret . ' -> ' . $sec . PHP_EOL;
}
echo 'First star: ' . $ans1 . PHP_EOL;

$bestScore = 0;
$bestSequence = [];
for($s1 = -9; $s1 <= 9; $s1++) for($s2 = -9; $s2 <= 9; $s2++) for($s3 = -9; $s3 <= 9; $s3++) for($s4 = -9; $s4 <= 9; $s4++)
{
    $key = implode(',', [$s1, $s2, $s3, $s4]);
    $score = array_sum($prices[$key] ?? []);

    if ($score > $bestScore) $bestScore = $score;
}

echo 'Second star: ' . $bestScore . PHP_EOL;

function calc(int $a): int
{
    $a = (($a << 6) ^ $a) & 0b111111111111111111111111;
    $a = (($a >> 5) ^ $a) & 0b111111111111111111111111;
    $a = (($a << 11) ^ $a) & 0b111111111111111111111111;
    return $a;
}