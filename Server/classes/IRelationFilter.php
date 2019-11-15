<?php
declare(strict_types=1);

interface IRelationFilter
{
	public function filter(int $pos, int $count_relations, int $deleted_relations, stdClass $rt, stdClass $r): bool;
}
