<?php
declare(strict_types=1);

class Node
{
	public static function instantiate (int $id, string $name, string $formated_name): stdClass
	{
		$obj 				= new stdClass();
		$obj->id 			= $id;
		$obj->name 			= ($formated_name == "") ? $name : $formated_name;

		return $obj;
	}

}

?>