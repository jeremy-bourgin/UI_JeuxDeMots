<?php
declare(strict_types=1);

class Raffinement
{
    public static function instantiate(stdClass $word): stdClass
    {
        $obj                = new stdClass();
        $obj->definition    = $word->definition;
        $obj->raff          = array();

        if (empty($word->relation_types))
        {
            return $obj;
        }

        $temp_out = $word->relation_types[0]->relations_out->data;

        foreach ($temp_out as &$e)
        {
            $obj->raff[] = $e->name;
        }

        return $obj;
    }
}