<?php
declare(strict_types=1);

abstract class Relation
{

	public static function instantiate (int $id, stdClass $node, int $weight, bool $is_out): stdClass
	{
		$obj			= new stdClass();
		$obj->id		= $id;
		$obj->node		= $node; 
		$obj->weight	= $weight; 
		$obj->is_out	= $is_out;

		return $obj;
	}
}
