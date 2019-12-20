<?php
declare(strict_types=1);

class Raffinement
{
    public static function instantiate(stdClass $word): stdClass
    {
        $obj                = new stdClass();
        $obj->definition    = $word->definition;
        $obj->raff          = null;

        if (empty($word->relation_types))
        {
            return $obj;
        }

        $temp = $word->relation_types[0]->relations_out->data;
        $obj->raff = array();

        foreach ($temp as &$e)
        {
            $obj->raff[] = $e->name;
        }

        return $obj;
    }
}