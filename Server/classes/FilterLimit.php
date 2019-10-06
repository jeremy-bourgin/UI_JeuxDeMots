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
		$limit_size = $this->limit + $this->from;
		
		return ($pos >= $this->limit || $pos < $this->to);
	}
}
