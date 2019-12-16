<?php
declare(strict_types=1);

class RelationType
{
	private static $blacklist = array(
		4,
		12,
		18,
		19,
		29,
		33,
		36,
		45,
		46,
		47,
		48,
		66,
		118,
		128,
		200,
		444,
		555,
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
		$obj->total_count 			= 0;
		$obj->count 				= 0;
		$obj->associated_relations	= array();

		return $obj;
	}
	
	public static function calcNbPages(stdClass $relation_type, int $limit): void
	{
		$calc = ceil($relation_type->count / $limit);
		$relation_type->nb_pages = $calc;
	}

	public static function filterRelations(stdClass $relation_type, array $filters): void
	{
		$temp = array();
		$count_relations = count($relation_type->associated_relations);
		$count_flters = count($filters);
		$deleted_relations = 0;
		
		for ($i = 0; $i < $count_relations; ++$i)
		{
			$r = $relation_type->associated_relations[$i];
			$j = 0;
			$kept = true;
			
			while ($j < $count_flters && $kept)
			{
				$kept = $filters[$j]->filter($i, $count_relations, $deleted_relations, $relation_type, $r);
				
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
		}
		
		$relation_type->associated_relations = $temp;
	}

    public static function isBlacklisted (int $id): ?bool
    {
    	return in_array($id, self::$blacklist, true);
    }

}
?>