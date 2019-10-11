<?php
declare(strict_types=1);

function delete_quotes(string &$str): void
{
	$len = strlen($str);
	
	if ($str[0] === "'" && $str[$len - 1] === "'")
	{
		$str = substr($str, 1, ($len - 2));
	}

	$str = htmlentities($str, ENT_QUOTES, APP_ENCODING);
}

function get_raw_data(string &$data): string
{
	$code_splitted = explode(DATA_DELIMITER_BEGIN, $data);

	if(count($code_splitted) === 1)
	{
		throw new ServerException("Pas de résultat");
	}
		
	$data_part = $code_splitted[1];
	$code_splitted = explode(DATA_DELIMITER_END, $data_part, -1);
	$data_part = implode($code_splitted);
	$data_part = strip_tags($data_part);

	return $data_part;
}

function parse_raw_data(string &$data): array
{
	$code_splitted = preg_split(DATA_PARTS_DELIMITER, $data);
	$count = count($code_splitted) - 1;
	
	for ($i=1; $i < $count; $i++)
	{
		$code_splitted[$i] = trim($code_splitted[$i]);
		
		if ($i === 1)
		{
			$code_splitted[$i] = preg_split(DATA_DEF_LINE_DELIMITER, $code_splitted[$i]);
			array_shift($code_splitted[$i]);
		}
		else
		{
			$code_splitted[$i] = explode(DATA_LINE_DELIMITER, $code_splitted[$i]);
		}
	}

	return $code_splitted;
}

function parse_columns(string &$line): array
{
	$columns = explode(DATA_COLUMN_DELIMITER, $line);
	
	return $columns;
}

function parse_node(array &$node): array
{
	$r = array();
	
	foreach ($node as &$e)
	{
		if (empty($e))
		{
			continue;
		}

		$columns = parse_columns($e);

		// is INVALID_NODE
		if(!isset($columns[DATA_NODE_TYPE_POS]))
		{
			continue;
		}
		
		$columns[DATA_NODE_ID_POS] = (int)$columns[DATA_NODE_ID_POS];
		// $columns[DATA_NODE_WORD_POS] = (string)$columns[DATA_NODE_WORD_POS]; -> useless to cast string to string
		$columns[DATA_NODE_TYPE_POS] = (int)$columns[DATA_NODE_TYPE_POS];
		$columns[DATA_NODE_WEIGHT_POS] = (int)$columns[DATA_NODE_WEIGHT_POS];

		if (NodeType::isBlacklisted($columns[DATA_NODE_TYPE_POS]))
		{
			continue;
		}

		$count_columns = count($columns);
		delete_quotes($columns[DATA_NODE_WORD_POS]);
		
		if ($count_columns == (DATA_NODE_FNAME_POS + 1))
		{
			delete_quotes($columns[DATA_NODE_FNAME_POS]);
		}
		else
		{
			$columns[DATA_NODE_FNAME_POS] = "";
		}
		
		$r[] = $columns;
	}
	
	return $r;
}

function parse_rel(array &$rel, string $element_type): array
{
	$r = array();
	
	foreach ($rel as &$e)
	{
		if (empty($e))
		{
			continue;
		}

		$columns = parse_columns($e);
		
		$columns[DATA_TYPE_POS] = $element_type;
		$columns[DATA_REL_ID_POS] = (int)$columns[DATA_REL_ID_POS];
		$columns[DATA_RELIN_ID_POS] = (int)$columns[DATA_RELIN_ID_POS];
		$columns[DATA_RELOUT_ID_POS] = (int)$columns[DATA_RELOUT_ID_POS];
		$columns[DATA_REL_TYPE_POS] = (int)$columns[DATA_REL_TYPE_POS];
		$columns[DATA_REL_WEIGHT_POS] = (int)$columns[DATA_REL_WEIGHT_POS];
		
		if(RelationType::isBlacklisted($columns[DATA_REL_TYPE_POS]))
		{
			continue;
		}

		$r[] = $columns;
	}
	
	return $r;
}

function is_rel_out(array &$rel): bool
{
	return ($rel[DATA_TYPE_POS] === DATA_RELOUT);
}

function sort_int(int &$a, int &$b): int
{
	return ($a <=> $b);
}

function sort_node(array &$a, array &$b): int
{
	return sort_int($a[DATA_NODE_ID_POS], $b[DATA_NODE_ID_POS]);
}

function sort_rel(array &$a, array &$b): int
{
	return -(sort_int($a[DATA_REL_WEIGHT_POS], $b[DATA_REL_WEIGHT_POS]));
}

function instantiate_word(array &$data, array &$nodes): stdClass
{
	$node_word = $nodes[DATA_WORD_POS];

	$result = Word::instantiate(
		$node_word[DATA_NODE_ID_POS],
		$node_word[DATA_NODE_WORD_POS],
		$node_word[DATA_NODE_TYPE_POS],
		$node_word[DATA_NODE_WEIGHT_POS],
		$node_word[DATA_NODE_FNAME_POS],
		$data[DATA_DEF_POS]
	);

	return $result;
}

function instantiate_node_type(array &$types, stdCLass $word): void
{
	foreach ($types as &$e)
	{
		if (empty($e))
		{
			continue;
		}

		$columns = explode(DATA_COLUMN_DELIMITER, $e);
		
		$columns[DATA_NODETYPE_ID_POS] = (int)$columns[DATA_NODETYPE_ID_POS];
		// $columns[DATA_NODETYPE_NAME_POS] = (string)$columns[DATA_NODETYPE_NAME_POS];  -> useless to cast string to string
		
		if (NodeType::isBlacklisted($columns[DATA_NODETYPE_ID_POS]))
		{
			continue;
		}
		
		delete_quotes($columns[DATA_NODETYPE_NAME_POS]);

		$o = NodeType::instantiate($columns[DATA_NODETYPE_ID_POS], $columns[DATA_NODETYPE_NAME_POS]);
		$word->node_types[] = $o;
	}
}

function instantiate_rel_type(array &$types, stdClass $word): void
{
	foreach ($types as &$e)
	{
		if (empty($e))
		{
			continue;
		}

		$columns = explode(DATA_COLUMN_DELIMITER, $e);
		
		$columns[DATA_RELTYPE_ID_POS] = (int)$columns[DATA_RELTYPE_ID_POS];
		// $columns[DATA_RELTYPE_NAME_POS] = (string)$columns[DATA_RELTYPE_NAME_POS];  -> useless to cast string to string
		// $columns[DATA_RELTYPE_GPNAME_POS] = (string)$columns[DATA_RELTYPE_GPNAME_POS];  -> useless to cast string to string
		// $columns[DATA_RELTYPE_HELP_POS] = (string)$columns[DATA_RELTYPE_HELP_POS];  -> useless to cast string to string

		if(RelationType::isBlacklisted($columns[DATA_RELTYPE_ID_POS]))
		{
			continue;
		}
		
		delete_quotes($columns[DATA_RELTYPE_NAME_POS]);
		delete_quotes($columns[DATA_RELTYPE_GPNAME_POS]);
		delete_quotes($columns[DATA_RELTYPE_HELP_POS]);
		
		$o = RelationType::instantiate(
			$columns[DATA_RELTYPE_ID_POS],
			$columns[DATA_RELTYPE_NAME_POS],
			$columns[DATA_RELTYPE_GPNAME_POS],
			$columns[DATA_RELTYPE_HELP_POS]
		);
		
		$word->relation_types[] = $o;
	}
}

function instantiate_relations(array &$nodes, array &$rels, $word): void
{
	usort($nodes, "sort_node");
	usort($rels, "sort_rel");

	$rels_index = 0;
	$count_rels = count($rels);

	$temp = array();
	
	foreach ($nodes as &$n)
	{
		// instantiation du noeud
		$node_obj = Node::instantiate(
			$n[DATA_NODE_ID_POS],
			$n[DATA_NODE_WORD_POS],
			$n[DATA_NODE_TYPE_POS],
			$n[DATA_NODE_WEIGHT_POS],
			$n[DATA_NODE_FNAME_POS]
		);

		$temp[] = $node_obj;
	}
	
	foreach($rels as &$r)
	{
		$is_rel_out = is_rel_out($r);
		$rel_node_id = ($is_rel_out) ? $r[DATA_RELOUT_ID_POS] : $r[DATA_RELIN_ID_POS];
		$rel_node = binary_search($temp, $rel_node_id);

		if ($rel_node === null)
		{
			continue;
		}
		
		// instantiation de la relation + ajout de la relation dans le noeud
		$rel_obj = Relation::instantiate(
			$r[DATA_REL_ID_POS],
			$rel_node,
			$r[DATA_REL_WEIGHT_POS],
			$is_rel_out
		);
		
		$rel_type = Word::findRelationTypeById($word, $r[DATA_REL_TYPE_POS]);
		$rel_type->associated_relations[] = $rel_obj;
	}
}

function data_to_obj(array &$data): stdClass
{
	$nodes = parse_node($data[DATA_NODE_POS]);
	$rels_out = parse_rel($data[DATA_RELOUT_POS], DATA_RELOUT);
	$rels_in = parse_rel($data[DATA_RELIN_POS], DATA_RELIN);
	$rels = array_merge($rels_out, $rels_in);

	$result = instantiate_word($data, $nodes);
	instantiate_node_type($data[DATA_NODETYPE_POS], $result);
	instantiate_rel_type($data[DATA_RELTYPE_POS], $result);
	instantiate_relations($nodes, $rels, $result);

	return $result;
}

function data_parser(string &$html_data): stdClass
{
	$raw_data = get_raw_data($html_data);
	$parsed_data = parse_raw_data($raw_data);
	$data = data_to_obj($parsed_data);
	
	return $data;
}
