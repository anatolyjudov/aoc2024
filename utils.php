<?php

function showGrid(array $grid, $callback = null)
{
    for($r = 0; $r < count($grid); $r++) {
        for($c = 0; $c < count($grid[0]); $c++) {
            if ($callback === null)
                echo $grid[$r][$c];
            else
                $callback($grid[$r][$c], $r, $c);
        }
        echo PHP_EOL;
    }
}

function dump($var)
{
    echo stringify($var) . PHP_EOL;
}

function stringify(int|string|float|array $var): string
{
    $res = '';
    if (is_array($var)) {
        $res .= '[';
        $s = 0;
        foreach($var as $k => $v) {
            $res .= stringifyVar($k) . ' => ' . stringify($v);
            if ($s !== count($var) - 1) $res .=  ', ';
            $s++;
        }
        $res .=  ']';
    } else {
        $res .=  stringifyVar($var);
    }

    return $res;
}

function stringifyVar(int|string|float $var)
{
    if (is_string($var)) {
        return '"' . $var . '"';
    }

    return $var;
}