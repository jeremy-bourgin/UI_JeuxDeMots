<?php
class FilterLimit implements IRelationFilter
{
	private $from;
	private $to;
	
	public function __construct(int $page, int $limit)
	{
		$this->from = $page * $limit;
		$this->to = $this->from + $limit;
	}
	
	public function filter(int $pos, int $count_relations, int $deleted_relations, stdClass $r): bool
	{
		$calc_pos = $pos - $deleted_relations;
		
		return ($calc_pos >= $this->from && $calc_pos < $this->to);
	}
}
