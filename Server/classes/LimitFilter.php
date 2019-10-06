<?php
class LimitFilter implements IRelationFilter
{
	public function filter(int $i, int $count_relations, stdClass $r): bool
	{
		return true;
	}
}
