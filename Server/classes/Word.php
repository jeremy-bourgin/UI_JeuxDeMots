<?php
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
	
	public static function filterRelations(stdClass $word, array $filters)
	{
		foreach ($word->relation_types as &$rt)
		{
			RelationType::filterRelations($rt, $filters);
		}
	}

	public static function findRelationTypeById(stdClass $word, int $id): ?stdClass
	{
		$r = binary_search($word->relation_types, $id);

		return $r;
	}
}
