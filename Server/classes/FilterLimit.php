<?php
declare(strict_types=1);

class FilterLimit implements IRelationFilter
{
	private $temp;
	private $count_limited;
	
	private $from;
	private $to;
	
	public function __construct(int $page, int $limit)
	{
		$this->from = $page * $limit;
		$this->to = $this->from + $limit;
		$this->temp = null;
	}
	
	public function filter(int $pos, int $count_relations, int $deleted_relations, stdClass $r): bool
	{
		if ($this->temp !== $r)
		{
			$this->count_limited = 0;
			$this->temp = $r;
		}
		
		$calc_pos = $pos - ($deleted_relations + $this->count_limited);
		$r = ($calc_pos >= $this->from && $calc_pos < $this->to);
		
		if (!$r)
		{
			++$this->count_limited;
		}
		
		return $r;
	}
}
