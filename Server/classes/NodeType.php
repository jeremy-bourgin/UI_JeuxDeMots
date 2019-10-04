<?php
class NodeType
{
	private static $blacklist = array(
		6,
		8,
		200
	);

	public static function instantiate (int $id, string $name): stdClass
	{
		$obj		= new stdClass();
		$obj->id 	= $id;
		$obj->name	= $name;

		return $obj;
	}

    public static function isBlacklisted (int $id): bool
    {
    	return in_array($id, self::$blacklist, true);
    }

}

?>