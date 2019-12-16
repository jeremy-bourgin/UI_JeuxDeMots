<?php
declare(strict_types=1);

class FilterLimit implements IRelationFilter
{
	private $last_rt;
	private $count_limited;
	
	private $from;
	private $to;
	
	public function __construct(int $page, int $limit)
	{
		$this->last_rt = null;
		$this->from = $page * $limit;
		$this->to = $this->from + $limit;
	}
	
	public function filter(int $pos, int $count_relations, int $deleted_relations, stdClass $rt, stdClass $r): bool
	{
		++$rt->count;

		if ($this->last_rt !== $rt)
		{
			$this->last_rt = $rt;
			$this->count_limited = 0;
		}
		
		$calc_pos = $pos - ($deleted_relations - $this->count_limited);
		$result = ($calc_pos >= $this->from && $calc_pos < $this->to);
		
		if (!$result)
		{
			++$this->count_limited;
		}
		
		return $result;
	}
}
