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

		$obj->relations_in 			= RelationContainer::instantiate(false);
		$obj->relations_out 		= RelationContainer::instantiate(true);

		return $obj;
	}

	public static function filterRelations(stdClass $relation_type, array $filters): void
	{
		RelationContainer::filterRelations($relation_type->relations_in, $relation_type, $filters);
		RelationContainer::filterRelations($relation_type->relations_out, $relation_type, $filters);
	}

    public static function isBlacklisted (int $id): ?bool
    {
    	return in_array($id, self::$blacklist, true);
    }

}
?>