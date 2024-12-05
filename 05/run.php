<?php

$inputLines = file_get_contents('input.txt');

$rules   = array_map(function($r) { return explode('|', $r); }, explode(PHP_EOL, explode(PHP_EOL . PHP_EOL, $inputLines)[0]));
$updates = array_map(function($r) { return explode(',', $r); }, explode(PHP_EOL, explode(PHP_EOL . PHP_EOL, $inputLines)[1]));

$ans1 = $ans2 = [];

foreach($updates as $update) {
    foreach($rules as $rule) {
        $met0 = $met1 = $wrong = false;

        for($i = 1; $i < count($update); $i++) {
            if ($update[$i] == $rule[0]) {
                $met0 = true;
            }
            if ($update[$i] == $rule[1]) {
                $met1 = true;
                if ($met0 === false) $wrong = true;
            }
        }

        if (($met0 && $met1) === false) continue;

        if ($wrong) {
            usort($update, function($a, $b) use ($rules) {
                foreach($rules as $rule)
                    switch($rule) {
                        case [$a, $b]: return -1;
                        case [$b, $a]: return 1;
                    }
            });
            $ans2[] = (int)$update[count($update) >> 1];
            continue 2;
        }
    }
    
    $ans1[] = (int)$update[count($update) >> 1];
}

printf('First star: %s%sSecond star: %s%s', array_sum($ans1), PHP_EOL, array_sum($ans2), PHP_EOL);