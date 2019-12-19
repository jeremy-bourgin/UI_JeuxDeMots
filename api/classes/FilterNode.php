<?php
declare(strict_types=1);

class FilterNode implements IRelationFilter
{
	private $word_filter;

	public function __construct(string $word_filter)
	{
		$this->word_filter = $word_filter;
	} 

	public function filter(int $pos, int $count_relations, int $deleted_relations, stdClass $rt, stdClass $rc, stdClass $r, bool &$is_break): bool
	{
		return (strpos($r->name, $this->word_filter) !== false);
	}
}
