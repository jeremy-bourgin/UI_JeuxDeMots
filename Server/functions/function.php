<?php
declare(strict_types=1);

function binary_search (array &$array, int $id): ?stdClass
{
    $count = count($array);
    
    if ($count === 0) 
        return null; 

    $low = 0; 
    $high = $count - 1; 

    while ($low <= $high) 
    { 
        $mid = floor(($low + $high) / 2); 

        if($array[$mid]->id === $id) { 
            return $array[$mid]; 
        } 

        if ($id < $array[$mid]->id) { 
            $high = $mid -1; 
        } 
        else { 
            $low = $mid + 1; 
        } 
    } 

    return null; 
}

?>