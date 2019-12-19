<?php
declare(strict_types=1);

abstract class Relation
{

	public static function instantiate (int $id, stdClass $node, int $weight): stdClass
	{
		$obj			= new stdClass();

		//$obj->id		= $id;
		$obj->weight	= $weight; 
		$obj->name		= $node->name;

		return $obj;
	}
}
