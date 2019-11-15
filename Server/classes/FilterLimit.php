<?php
declare(strict_types=1);

class FilterLimit implements IRelationFilter
{
	private $count_limited;
	
	private $relation_name;
	private $from;
	private $to;
	
	public function __construct(string $relation_name, int $page, int $limit)
	{
		$this->relation_name = $relation_name;
		$this->from = $page * $limit;
		$this->to = $this->from + $limit;
	}
	
	public function filter(int $pos, int $count_relations, int $deleted_relations, stdClass $rt, stdClass $r): bool
	{
		if ($rt->name !== $this->relation_name)
		{
			return true;
		}

		if ($pos === 0)
		{
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
