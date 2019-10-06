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
	
	public function filter(int $pos, int $count_relations, stdClass $r): bool
	{		
		return ($pos >= $this->from && $pos < $this->to);
	}
}
