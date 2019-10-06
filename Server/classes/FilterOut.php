<?php
class FilterOut implements IRelationFilter
{
	public function filter(int $pos, int $count_relations, int $deleted_relations, stdClass $r): bool
	{
		return (!$r->is_out);
	}
}
