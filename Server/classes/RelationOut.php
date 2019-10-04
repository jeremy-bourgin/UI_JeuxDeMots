<?php
class RelationOut extends Relation
{
	const IS_OUT = true;

	public function __construct (int $id, Node $node, int $weight)
	{
		parent::__construct($id, $node, $weight, self::IS_OUT);
	}
}
