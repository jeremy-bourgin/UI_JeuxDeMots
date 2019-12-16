<?php
declare(strict_types=1);

class Node
{
	public static function instantiate (int $id, string $name, int $type_id, int $weight, string $formated_name): stdClass
	{
		$obj 				= new stdClass();
		$obj->id 			= $id;
		$obj->name 			= ($formated_name == "") ? $name : $formated_name;
		//$obj->type_id 		= $type_id;
		//$obj->weight		= $weight;
		//$obj->formated_name	= $formated_name;

		return $obj;
	}

}

?>