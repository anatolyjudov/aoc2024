<?php

$input = file('input.txt', FILE_IGNORE_NEW_LINES);

$cons = [];
foreach($input as $line) {
    $peers = explode('-', $line);
    if (!isset($cons[$peers[0]])) $cons[$peers[0]] = new \Ds\Set();
    if (!isset($cons[$peers[1]])) $cons[$peers[1]] = new \Ds\Set();
    $cons[$peers[0]]->add($peers[1]);
    $cons[$peers[1]]->add($peers[0]);
}

$groups = new \Ds\Set();
foreach($cons as $node => $peers) {
    for($p1 = 0; $p1 < count($peers) - 1; $p1++) for($p2 = $p1 + 1; $p2 < count($peers); $p2++) {
        $peer1 = $peers[$p1];
        $peer2 = $peers[$p2];
        if ($cons[$peer1]->contains($peer2)) {
            if ($node[0] === 't' || $peer1[0] === 't' || $peer2[0] === 't') {
                $group = [$node, $peer1, $peer2];
                sort($group);
                $groups->add($group);
            }
        }
    }
}

echo 'First star: ' . count($groups) . PHP_EOL;