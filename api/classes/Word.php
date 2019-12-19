<?php
declare(strict_types=1);

class Word
{
	public static function instantiate (int $id, string $name, int $type_id, int $weight, string $formated_name, array $definition): stdClass
	{
		$obj					= Node::instantiate($id, $name, $type_id, $weight, $formated_name);
		$obj->definition		= $definition;
		$obj->relation_types	= array();

		return $obj;
	}
	
	public static function calcNbPages(stdClass $word, int $limit): void
	{
		foreach ($word->relation_types as &$rt)
		{
			RelationType::calcNbPages($rt, $limit);
		}
	}

	public static function filterRelations(stdClass $word, array $filters): void
	{
		$temp = array();
		
		foreach ($word->relation_types as &$rt)
		{
			RelationType::filterRelations($rt, $filters);
			
			if (empty($rt->relations_in->data) && empty($rt->relations_out->data))
			{
				continue;
			}
			
			$temp[] = $rt;
		}
		
		$word->relation_types = $temp;
	}

	public static function findRelationTypeById(stdClass $word, int $id): ?stdClass
	{
		return binary_search($word->relation_types, $id);
	}

	public static function findRelationContainerByName(stdClass $word, string $name): ?stdClass
	{
		$i = 0;
		$count = count($word->relation_types);
		$r = null;

		while ($i < $count && $r === null)
		{
			$e = $word->relation_types[$i];

			if ($e->name === $name)
			{
				$r = $e;
			}

			++$i;
		}

		return $e;
	}
}
