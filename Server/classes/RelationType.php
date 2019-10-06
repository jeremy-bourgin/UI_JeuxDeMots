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
	
	public static function deleteOutRelations(stdClass $relation_type)
	{
		
	}
	
	public static function deleteInRelations(stdClass $relation_type)
	{
		
	}
	
	public static function limit(stdClass $relation_type, int $size)
	{
		
	}

    public static function isBlacklisted (int $id): ?bool
    {
    	return in_array($id, self::$blacklist, true);
    }

}
?>