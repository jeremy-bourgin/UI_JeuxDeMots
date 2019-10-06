<?php
interface IRelationFilter
{
	public function filter(int $pos, int $count_relations, int $deleted_relations, stdClass $r): bool;
}
