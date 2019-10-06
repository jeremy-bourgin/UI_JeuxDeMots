<?php
interface IRelationFilter
{
	public abstract function filter(int $pos, int $count_relations, stdClass $r): bool;
}
