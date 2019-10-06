<?php
class FilterNode implements IRelationFilter
{
	private $word_filter;

	public function __construct(string $word_filter)
	{
		$this->word_filter = $word_filter;
	} 

	public function filter(int $pos, int $count_relations, int $deleted_relations, stdClass $r): bool
	{
		return strpos($r->node->name, $this->word_filter) === false);
	}
}
