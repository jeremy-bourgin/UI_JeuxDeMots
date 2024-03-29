<?php
declare(strict_types=1);

function html_encode(string &$str): void
{
	$str = htmlentities($str, ENT_QUOTES, APP_ENCODING);
}

function delete_quotes(string &$str): void
{
	$len = strlen($str);
	
	if ($str[0] === "'" && $str[$len - 1] === "'")
	{
		$str = substr($str, 1, ($len - 2));
	}

	//html_encode($str);
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
	$code_imploded = implode($code_splitted);
	$code_without_warning = preg_replace(DATA_WARNING, "", $code_imploded);
	
	return strip_tags($code_without_warning);
}

function parse_raw_data(string &$data): array
{
	$code_splitted = preg_split(DATA_PARTS_DELIMITER, $data);
	$count = count($code_splitted) - 1;
	
	for ($i = 1; $i < $count; $i++)
	{
		$code_splitted[$i] = trim($code_splitted[$i]);
		
		if ($i === 1)
		{
			//html_encode($code_splitted[$i]);

			$code_splitted[$i] = preg_split(DATA_DEF_LINE_DELIMITER, $code_splitted[$i]);			
			$temp = array_shift($code_splitted[$i]);
			
			if (empty($code_splitted[$i]))
			{
				$code_splitted[$i] = (empty($temp)) ? array() : array($temp);
			}
		}
		else
		{
			$code_splitted[$i] = explode(DATA_LINE_DELIMITER, $code_splitted[$i]);
		}
	}
	
	for ($i = $count; $i < (DATA_COUNT_PARTS + 1); ++$i)
	{
		$code_splitted[$i] = array();
	}

	return $code_splitted;
}

function check_numerics_data(array &$data, array &$columns): bool
{
	$i = 0;
	$count = count($columns);
	$is_success = true;
	
	while ($i < $count && $is_success)
	{
		$c = $columns[$i];
		
		if (!isset($data[$c]) || !is_numeric($data[$c]))
		{
			$is_success = false;
		}
		else
		{
			$data[$c] = (int)$data[$c];
		}
		
		++$i;
	}
	
	return $is_success;
}

function parse_columns(string &$line): array
{	
	return explode(DATA_COLUMN_DELIMITER, $line);
}

function parse_node_type(array &$types, ?array $white_list = null): array
{
	$result = array();
	$has_white_list = ($white_list !== null);

	foreach ($types as &$e)
	{
		if (empty($e))
		{
			continue;
		}

		$columns = explode(DATA_COLUMN_DELIMITER, $e);
		
		$numeric_columns = array(
			DATA_NODETYPE_ID_POS
		);

		if (
			!check_numerics_data($columns, $numeric_columns)
			|| (
				(!$has_white_list && NodeType::isBlacklisted($columns[DATA_NODETYPE_ID_POS]))
				|| ($has_white_list && in_array($columns[DATA_NODETYPE_ID_POS], $white_list, true))
			)
		) {
			continue;
		}
		
		$result[] = $columns[DATA_NODETYPE_ID_POS];
	}

	return $result;
}

function parse_node(array &$node, array &$node_types): array
{
	$r = array();
	
	foreach ($node as &$e)
	{
		if (empty($e))
		{
			continue;
		}

		$columns = parse_columns($e);
		
		$numeric_columns = array(
			DATA_NODE_ID_POS,
			DATA_NODE_TYPE_POS,
			DATA_NODE_WEIGHT_POS
		);
		
		if (
			!check_numerics_data($columns, $numeric_columns)
			|| !in_array($columns[DATA_NODE_TYPE_POS], $node_types, true)
		) {
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

function parse_rel(array &$rel, string $element_type, ?array $white_list = null): array
{
	$r = array();
	$has_white_list = ($white_list !== null);
	
	foreach ($rel as &$e)
	{
		if (empty($e))
		{
			continue;
		}

		$columns = parse_columns($e);
		
		$numeric_columns = array(
			DATA_REL_ID_POS,
			DATA_RELIN_ID_POS,
			DATA_RELOUT_ID_POS,
			DATA_REL_TYPE_POS,
			DATA_REL_WEIGHT_POS
		);

		$columns[DATA_TYPE_POS] = $element_type;

		if(
			!check_numerics_data($columns, $numeric_columns)
			|| (
				(!$has_white_list && RelationType::isBlacklisted($columns[DATA_REL_TYPE_POS]))
				|| ($has_white_list && !in_array($columns[DATA_REL_TYPE_POS], $white_list, true))
			)
		) {
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

	return Word::instantiate(
		$node_word[DATA_NODE_ID_POS],
		$node_word[DATA_NODE_WORD_POS],
		$node_word[DATA_NODE_FNAME_POS],
		$data[DATA_DEF_POS]
	);
}

function instantiate_rel_type(array &$types, stdClass $word, ?array $white_list = null): void
{
	$has_white_list = ($white_list !== null);

	foreach ($types as &$e)
	{
		if (empty($e))
		{
			continue;
		}

		$columns = explode(DATA_COLUMN_DELIMITER, $e);
		
		$numeric_columns = array(
			DATA_RELTYPE_ID_POS
		);
		
		if(
			!check_numerics_data($columns, $numeric_columns)
			|| (
				(!$has_white_list && RelationType::isBlacklisted($columns[DATA_RELTYPE_ID_POS]))
				|| ($has_white_list && !in_array($columns[DATA_RELTYPE_ID_POS], $white_list, true))
			)
		) {
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

	$temp = array();
	
	foreach ($nodes as &$n)
	{
		// instantiation du noeud
		$node_obj = Node::instantiate(
			$n[DATA_NODE_ID_POS],
			$n[DATA_NODE_WORD_POS],
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
			$r[DATA_REL_WEIGHT_POS]
		);
		
		$rel_type = Word::findRelationTypeById($word, $r[DATA_REL_TYPE_POS]);

		$relation_container = ($is_rel_out) ? $rel_type->relations_out : $rel_type->relations_in;
		$relation_container->data[] = $rel_obj;
		++$relation_container->total_count;
	}
}

function data_to_obj(array &$data, ?array $nt_white_list = null, ?array $rt_white_list = null): stdClass
{
	$node_types = parse_node_type($data[DATA_NODETYPE_POS], $nt_white_list);
	$nodes = parse_node($data[DATA_NODE_POS], $node_types);
	
	$rels_out = parse_rel($data[DATA_RELOUT_POS], DATA_RELOUT, $rt_white_list);
	$rels_in = parse_rel($data[DATA_RELIN_POS], DATA_RELIN, $rt_white_list);
	$rels = array_merge($rels_out, $rels_in);

	$result = instantiate_word($data, $nodes);
	
	instantiate_rel_type($data[DATA_RELTYPE_POS], $result, $rt_white_list);
	instantiate_relations($nodes, $rels, $result);

	return $result;
}

function data_parser(string &$html_data, ?array $nt_white_list = null, ?array $rt_white_list = null): stdClass
{
	$raw_data = get_raw_data($html_data);
	$parsed_data = parse_raw_data($raw_data);
	
	return data_to_obj($parsed_data, $nt_white_list, $rt_white_list);
}

function data_parser_raff(string &$html_data, ?array $nt_white_list = null): stdClass
{
	$data = data_parser(
		$html_data,
		$nt_white_list, 
		array(
			1
		)
	);

	return Raffinement::instantiate($data);
}