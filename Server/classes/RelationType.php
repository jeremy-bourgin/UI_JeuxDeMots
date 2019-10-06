<?php
class RelationType
{
	private static $blacklist = array(
		12, 
		29,
		45,
		46,
		47,
		48,
		66,
		118,
		200,
		1000,
		1001,
		1002,
		2001
	);

	public static function instantiate (int $id, string $name, string $gpname, string $help): stdClass
	{
		$obj						= new stdClass();
		$obj->id					= $id;
		$obj->name					= $name; 
		$obj->gpname				= $gpname;
		$obj->help					= $help;
		$obj->associated_relations	= array();

		return $obj;
	}
	
	public static function filterRelations(stdClass $relation_type, array $filters)
	{
		$temp = array();
		$count_relations = count($relation_type->associated_relations);
		$count_flters = count($filters);
		
		for ($i = 0; $i < $count_relations; ++$i)
		{
			$r = $relation_type->associated_relations[$i];
			$j = 0;
			$kept = true;
			
			while ($j < $count_flters && $kept)
			{
				$kept = $filters[$j]->filter($i, $count_relations, $r);
				
				++$j;
			}
			
			if (!$kept)
			{
				continue;
			}
			
			$temp[] = $r;
		}
		
		$relation_type->associated_relations = $temp;
	}
	
	public static function deleteOutRelations(stdClass $relation_type)
	{
		$temp = array();
		
		foreach ($relation_type->associated_relations as &$r)
		{
			if ($r->is_out)
			{
				continue;
			}
			
			$temp[] = $r;
		}
		
		$relation_type->associated_relations = $temp;
	}
	
	public static function deleteInRelations(stdClass $relation_type)
	{
		$temp = array();
		
		foreach ($relation_type->associated_relations as &$r)
		{
			if (!$r->is_out)
			{
				continue;
			}
			
			$temp[] = $r;
		}
		
		$relation_type->associated_relations = $temp;
	}
	
	public static function limit(stdClass $relation_type, int $size)
	{
		$temp = array();
		$count = sizeof($relation_type->associated_relations);
		$limit = ($count > $size) ? $size : $count;
		
		for ($i = 0; $i < $limit; ++$i)
		{
			$temp[] = $relation_type->associated_relations[$i];
		}
		
		$relation_type->associated_relations = $temp;
	}

	public static function filterNodes(stdClass $relation_type, string $filter)
	{
		$temp = array();

		foreach ($relation_type->associated_relations as $ar) 
		{
			if(strpos($ar->node->name, $filter) !== false)
				$temp[] = $ar;
		}

		$relation_type->associated_relations = $temp;
	}

    public static function isBlacklisted (int $id): ?bool
    {
    	return in_array($id, self::$blacklist, true);
    }

}
?>