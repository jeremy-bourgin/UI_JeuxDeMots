<?php
class Filter
{
	private $callback;

	public function __construct(callable $callback)
	{
		$this->callback = $callback;
	}
	
	public abstract function filter(int $i, int $count_relations, stdClass $r);
}
