<?php

function init($argv)
{
    array_shift($argv);
    $la = [];
    $lb = [];
    $frame = 0;
    $render = "";
    $i = 0;

    foreach ($argv as $key) {
        if (!ctype_alpha($key)) {
            $la[] = $key;
            $frame += strlen($key);
        }
    }

    //    sa = [la] 1er ←→ [la] 2nd
    function sa($la, $lb, $render, $frame)
    {
        $temp = $la[0];
        $la[0] = $la[1];
        $la[1] = $temp;
        $render .= "sa → ";
        $array = [$la, $lb, $render];
        return $array;
    }

    //    sb = [lb] 1er ←→ [lb] 2nd
    function sb($la, $lb, $render, $frame)
    {
        $temp = $lb[0];
        $lb[0] = $lb[1];
        $lb[1] = $temp;
        $render .= "sb → ";
        $array = [$la, $lb, $render];
        return $array;
    }

    //    pa = [lb] 1er -→ [la] 1er
    function pa($la, $lb, $render, $frame)
    {
        array_unshift($la, $lb[0]);
        array_shift($lb);
        render($la, $lb, "pa", $frame);
        $render .= "pa → ";
        $array = [$la, $lb, $render];
        return $array;
    }

    //    pb = [la] 1er -→ [lb] 1er
    function pb($la, $lb, $render, $frame)
    {
        array_unshift($lb, $la[0]);
        array_shift($la);
        render($la, $lb, "pb", $frame);
        $render .= "pb → ";
        $array = [$la, $lb, $render];
        return $array;
    }

    //    ra = [la] rotation ←-
    function ra($la, $lb, $render, $frame)
    {
        array_push($la, $la[0]);
        array_shift($la);
        render($la, $lb, "ra", $frame);
        $render .= "ra → ";
        $array = [$la, $lb, $render];
        return $array;
    }

    //    rb = [lb] rotation ←-
    function rb($la, $lb, $render, $frame)
    {
        array_push($lb, $lb[0]);
        array_shift($lb);
        render($la, $lb, "rb", $frame);
        $render .= "rb → ";
        $array = [$la, $lb, $render];
        return $array;
    }

//    Render
    function render($la, $lb, $function, $frame)
    {
        $render_arr = "";
        $render_arr .= "\n┌ \e[1;32m" . $function . "\e[0m\n├" . str_repeat("─", $frame + 16) . "┐\n\e[0;36m  la : ";
        foreach ($la as $key) {
            $render_arr .= $key . " ";
        }
        $render_arr .= "\e[0m\n\e[0;35m  lb : ";
        foreach ($lb as $key) {
            $render_arr .= $key . " ";
        }
        $render_arr .= "\e[0m\n└" . str_repeat("─", $frame + 16) . "┘\n";
        echo $render_arr;
        usleep(500000);
    }

//    Execution et verification si trie
    while ($i < count($la) - 1) {
        if ($la[$i] > $la[$i + 1]) {
            while (count($la) > 0) {
                if ($la[0] == min($la)) {
                    $array = pb($la, $lb, $render, $frame);
                    $la = $array[0];
                    $lb = $array[1];
                    $render = $array[2];
                } else {
                    $array = ra($la, $lb, $render, $frame);
                    $la = $array[0];
                    $lb = $array[1];
                    $render = $array[2];
                }
            }

            while (count($lb) > 0) {
                $array = pa($la, $lb, $render, $frame);
                $la = $array[0];
                $lb = $array[1];
                $render = $array[2];
            }
            break;
        }
        $i++;
    }

    echo "\n\e[1;32m" . substr($render, 0, -5) . "\e[0m\n\n";
}

init($argv);