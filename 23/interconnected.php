<?php

use \Ds\Set;

$input = file('input.txt', FILE_IGNORE_NEW_LINES);

$cons = [];
$all = new Set();
foreach($input as $line) {
    $peers = explode('-', $line);
    $all->add($peers[0]);
    $all->add($peers[1]);
    if (!isset($cons[$peers[0]])) $cons[$peers[0]] = new Set();
    if (!isset($cons[$peers[1]])) $cons[$peers[1]] = new Set();
    $cons[$peers[0]]->add($peers[1]);
    $cons[$peers[1]]->add($peers[0]);
}

$largest = filterLargest($all);

$passKeys = $largest->toArray();
sort($passKeys);
echo 'Second star: ' . implode(',', $passKeys) . PHP_EOL;

function filterLargest(Set $parentGroup, $level = 0): Set
{
    global $cons;

    $largest = new Set();
    $checked = new Set();
    foreach($parentGroup as $node) {
        if ($checked->contains($node)) continue;

        $group = new Set();
        $group->add($node);
        $seen = new Set();
        while(true) {
            $candidates = $cons[$group[0]];
            for($i = 1; $i < $group->count(); $i++) {
                $candidates = $candidates->intersect($cons[$group[$i]]);
            }
            $candidates = $candidates->intersect($parentGroup);
            $candidates = $candidates->diff($group);
            $candidates = $candidates->diff($seen);
            if ($candidates->count() === 0) break;
            $seen = $seen->union($candidates);
            if ($candidates->count() === 1) {
                $group->add($candidates[0]);
                break;
            }
            $filtered = filterLargest($candidates, $level + 1);
            foreach($filtered as $newConnectedNode) $group->add($newConnectedNode);
        }

        $checked = $checked->union($group);
        if ($group->count() > $largest->count()) $largest = $group;
    }

    return $largest;
}