<?php

function binary_search (&$array, $id) // const array (/!\ ref)
{
    $count = count($array);
    if ($count === 0) 
        return false; 

    $low = 0; 
    $high = $count - 1; 

    while ($low <= $high) 
    { 
        $mid = floor(($low + $high) / 2); 

        if($array[$mid] === $id) { 
            return $mid; 
        } 

        if ($id < $array[$mid]) { 
            $high = $mid -1; 
        } 
        else { 
            $low = $mid + 1; 
        } 
    } 

    return false; 
}

?>