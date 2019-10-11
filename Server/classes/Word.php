<?php
declare(strict_types=1);

class Word
{
	public static function instantiate (int $id, string $name, int $type_id, int $weight, string $formated_name, array $definition): stdClass
	{
		$obj					= Node::instantiate($id, $name, $type_id, $weight, $formated_name);
		$obj->definition		= $definition;
		$obj->node_types 		= array();
		$obj->relation_types	= array();

		return $obj;
	}
	
	public static function filterRelations(stdClass $word, array $filters): void
	{
		$temp = array();
		
		foreach ($word->relation_types as &$rt)
		{
			RelationType::filterRelations($rt, $filters);
			
			if (empty($rt->associated_relations))
			{
				continue;
			}
			
			$temp[] = $rt;
		}
		
		$word->relation_types = $temp;
	}

	public static function findRelationTypeById(stdClass $word, int $id): ?stdClass
	{
		return binary_search($word->relation_types, $id);
	}
}
