<?php
class RelationIn extends Relation
{
	const IS_OUT = false;

	public function __construct (int $id, Node $node, int $weight)
	{
		parent::__construct($id, $node, $weight, self::IS_OUT);
	}

}
