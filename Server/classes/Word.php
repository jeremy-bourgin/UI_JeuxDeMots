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

	public static function findRelationTypeById(stdClass $word, int $id): ?stdClass
	{
		$r = binary_search($word->relation_types, $id);

		return $r;
	}
	
	public static function deleteOutRelations(stdClass $word)
	{
		
	}
	
	public static function deleteInRelations(stdClass $word)
	{
		
	}
	
	public static function limit(stdClass $word, int $size)
	{
		
	}
}
