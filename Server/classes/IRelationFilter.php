<?php
interface IRelationFilter
{
	public function filter(int $pos, int $count_relations, stdClass $r): bool;
}
