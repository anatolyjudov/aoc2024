<?php

preg_match_all(
    '/don\'t\(\)|do\(\)|mul\((\d+),(\d+)\)/', 
    file_get_contents('input.txt'), $matches, PREG_SET_ORDER
);

$res1 = $res2 = 0;
$do = true;

foreach($matches as $match) {
    switch(substr($match[0], 0, 3)) {
        case 'don': $do = false; break;
        case 'do(': $do = true; break;
        case 'mul': 
            $res1 += (int)$match[1] * (int)$match[2];
            $res2 += $do ? (int)$match[1] * (int)$match[2] : 0;
    }
}

printf('First star: %s%sSecond star: %s%s', $res1, PHP_EOL, $res2, PHP_EOL);
