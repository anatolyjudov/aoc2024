<?php

$input = trim(file_get_contents('input.txt'));

$index = [];
$pos = 0;
for($i = 0; $i < strlen($input); $i++) {
    if ($i % 2 === 0) {
        $index[] = [$i / 2, 0, (int)$input[$i]];
    } else {
        $index[] = [-1, 0, (int)$input[$i]];
    }
    $pos += $input[$i];
}

$res1 = part1($index);
$res2 = part2($index);

printf('First star: %s%sSecond star: %s%s', checksum($res1), PHP_EOL, checksum($res2), PHP_EOL);

function part1(array $index): array
{
    while(true) {
        // find space
        $space = false;
        foreach($index as $i => $block) {
            if ($block[0] === -1) {
                $space = $i;
                break;
            }
        }
        if ($space === false) break;
        
        $spaceSize = $index[$space][2];
    
        // find file
        while(true) {
            $last = $index[count($index) - 1];
            if ($last[0] === -1) {
                array_pop($index);
                continue;
            }
            break;
        }
    
        // switch
        if ($last[2] < $spaceSize) {
            // .... NNN
            $index[$space][2] -= $last[2];
            array_splice($index, $space, 0, [$last]);
            array_pop($index);
        } elseif ($last[2] == $spaceSize) {
            // ... NNN
            array_splice($index, $space, 1, [$last]);
            array_pop($index);
        } else {
            // ... NNNNN
            $index[count($index) - 1][2] -= $spaceSize;
            array_splice($index, $space, 1, [[$last[0], 0, $spaceSize]]);
        }
    }

    return $index;
}

function part2(array $index): array
{
    // part 2
    while(true) {
        // clear trailing spaces
        while($index[count($index) -1][0] === 1) array_pop($index);

        // find last untouch
        $l = count($index) - 1;
        while($l >= 0) {
            if (($index[$l][0] === -1) || ($index[$l][1] === 1)) {
                $l--;
                continue;
            }
            break;
        }
        if ($l === -1) break;

        $moving = $index[$l];

        // find space
        $s = 0;
        while($s < $l) {
            if (($index[$s][0] !== -1) || ($index[$s][2] < $moving[2])) {
                $s++;
                continue;
            }
            break;
        }
        if ($s === count($index)) {
            $index[$l][1] = 1; // mark as not moved
            continue;
        }

        // switch
        if ($index[$s][2] === $moving[2]) {
            // ... NNN
            array_splice($index, $l, 1, [[-1, 0, $moving[2]]]);
            array_splice($index, $s, 1, [[$moving[0], 1, $moving[2]]]);
        } else {
            // .... NNN
            array_splice($index, $l, 1, [[-1, 0, $moving[2]]]);
            $index[$s][2] -= $moving[2];
            array_splice($index, $s, 0, [[$moving[0], 1, $moving[2]]]);
        }
    }

    return $index;
}

function checksum(array &$index): int
{
    $pos = $checksum = 0;
    foreach($index as $block) {
        for($i = 0; $i < $block[2]; $i++) {
            if ($block[0] === -1) continue;
            $checksum += $block[0] * ($pos + $i);
        }
        $pos += $block[2];
    }
    return $checksum;
}