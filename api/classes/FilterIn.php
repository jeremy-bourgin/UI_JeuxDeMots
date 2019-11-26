<?php
declare(strict_types=1);

class FilterIn implements IRelationFilter
{
	public function filter(int $pos, int $count_relations, int $deleted_relations, stdClass $rt, stdClass $r): bool
	{
		return $r->is_out;
	}
}
