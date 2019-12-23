<?php
declare(strict_types=1);

class RelationContainer
{
    public static function instantiate(bool $is_out): stdClass
    {
        $obj                = new stdClass();
        $obj->data          = array();
        $obj->is_out        = $is_out;
		$obj->total_count 	= 0;
		$obj->count 		= 0;
        
        return $obj;
    }

	public static function filterRelations(stdClass $relation_container, stdClass $relation_type, array $filters): void
	{
		$temp = array();
		$count_data = $relation_container->total_count;
		$count_flters = count($filters);
		$deleted_relations = 0;
		
		for ($i = 0; $i < $count_data; ++$i)
		{
			$r = $relation_container->data[$i];
			$j = 0;
			$kept = true;
			$is_break = false;
			
			while ($j < $count_flters && $kept && !$is_break)
			{
				$kept = $filters[$j]->filter($i, $count_data, $deleted_relations, $relation_type, $relation_container, $r, $is_break);
				
				++$j;
			}
			
			if (!$kept)
			{
				++$deleted_relations;
			}
			else
			{
				$temp[] = $r;
			}

			if ($is_break)
			{
				break;
			}
		}
		
		$relation_container->data = $temp;
	}
}
