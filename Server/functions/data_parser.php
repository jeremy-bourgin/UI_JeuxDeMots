<?php
function delete_quotes(&$str) // out str
{
	$len = strlen($str);
	
	if ($str[0] === "'" && $str[$len - 1] === "'")
	{
		$str = substr($str, 1, ($len - 2));
	}

	$str = htmlentities($str, ENT_QUOTES, APP_ENCODING);
}

function get_raw_data(&$data) // const data (/!\ ref)
{
	$code_splitted = explode(DATA_DELIMITER_BEGIN, $data);

	if(count($code_splitted) === 1)
		throw new ServerException("Pas de résultat");
		
	$data_part = $code_splitted[1];
	$code_splitted = explode(DATA_DELIMITER_END, $data_part, -1);
	$data_part = implode($code_splitted);
	$data_part = strip_tags($data_part);

	return $data_part;
}

function parse_raw_data(&$data) // const data (/!\ ref)
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

function parse_columns(&$line) // const line (/!\ ref)
{
	$columns = explode(DATA_COLUMN_DELIMITER, $line);
	
	return $columns;
}

function parse_node(&$node) // const node (/!\ ref)
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

function parse_rel(&$rel, $element_type) // const rel (/!\ ref), reltype
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

		$rel_type = (int)$columns[DATA_REL_TYPE_POS];
		
		if(RelationType::isBlacklisted($rel_type))
		{
			continue;
		}

		$r[] = $columns;
	}
	
	return $r;
}

function is_rel_out(&$rel) // const rel (/!\ ref)
{
	return ($rel[DATA_TYPE_POS] === DATA_RELOUT);
}

function sort_int(&$a, &$b) // const a (/!\ ref), const b (/!\ ref)
{
	$int_a = (int)$a;
	$int_b = (int)$b;
	
	return ($int_a <=> $int_b);
}

function sort_node(&$a, &$b) // const a (/!\ ref), const b (/!\ ref)
{
	return sort_int($a[DATA_NODE_ID_POS], $b[DATA_NODE_ID_POS]);
}

function sort_rel(&$a, &$b)
{
	return -(sort_int($a[DATA_REL_WEIGHT_POS], $b[DATA_REL_WEIGHT_POS]));
}

function instantiate_word(&$data, &$nodes) // const data (/!\ ref), const nodes (/!\ ref)
{
	$node_word = $nodes[DATA_WORD_POS];
	$word_id = (int)$node_word[DATA_NODE_ID_POS];
	$word_name = $node_word[DATA_NODE_WORD_POS];
	$word_type = (int)$node_word[DATA_NODE_TYPE_POS];
	$word_weight = (int)$node_word[DATA_NODE_WEIGHT_POS];
	$word_fname = $node_word[DATA_NODE_FNAME_POS];
	$word_def = $data[DATA_DEF_POS];

	$result = Word::instantiate($word_id, $word_name, $word_type, $word_weight, $word_fname, $word_def);

	return $result;
}

function instantiate_node_type(&$types, $word) // const types (/!\ ref)
{
	foreach ($types as &$e)
	{
		if (empty($e))
		{
			continue;
		}

		$columns = explode(DATA_COLUMN_DELIMITER, $e);
		
		$id = (int)$columns[DATA_NODETYPE_ID_POS];
		$name = $columns[DATA_NODETYPE_NAME_POS];

		if (NodeType::isBlacklisted($id))
		{
			continue;
		}
		
		delete_quotes($name);

		$o = NodeType::instantiate($id, $name);
		$word->node_types[] = $o;
	}
}

function instantiate_rel_type(&$types, $word) // const types (/!\ ref)
{	
	foreach ($types as &$e)
	{
		if (empty($e))
		{
			continue;
		}

		$columns = explode(DATA_COLUMN_DELIMITER, $e);
		
		$id = (int)$columns[DATA_RELTYPE_ID_POS];
		$name = $columns[DATA_RELTYPE_NAME_POS];
		$gpname = $columns[DATA_RELTYPE_GPNAME_POS];
		$help = $columns[DATA_RELTYPE_HELP_POS];

		if(RelationType::isBlacklisted($id))
		{
			continue;
		}
		
		delete_quotes($name);
		delete_quotes($gpname);
		delete_quotes($help);
		
		$o = RelationType::instantiate($id, $name, $gpname, $help);
		$word->relation_types[] = $o;
	}
}

function instantiate_relations(&$nodes, &$rels, $word) // ref nodes, const rels (/!\ ref), word
{
	usort($nodes, "sort_node");
	usort($rels, "sort_rel");

	$rels_index = 0;
	$count_rels = count($rels);

	$temp = array();
	
	foreach ($nodes as &$n)
	{
		$node_id = (int)$n[DATA_NODE_ID_POS];
		$node_word = $n[DATA_NODE_WORD_POS];
		$node_type = (int)$n[DATA_NODE_TYPE_POS];
		$node_weight = (int)$n[DATA_NODE_WEIGHT_POS];
		$node_fname = $n[DATA_NODE_FNAME_POS];

		// instantiation du noeud
		$node_obj = Node::instantiate($node_id, $node_word, $node_type, $node_weight, $node_fname);

		$temp[] = $node_obj;
	}
	
	foreach($rels as &$r)
	{
		$is_rel_out = is_rel_out($r);

		$rel_id = (int)$r[DATA_REL_ID_POS];

		$rel_node_id = (int)(($is_rel_out) ? $r[DATA_RELOUT_ID_POS] : $r[DATA_RELIN_ID_POS]);
		$rel_node = binary_search($temp, $rel_node_id);

		if ($rel_node === null)
		{
			continue;
		}

		$rel_type_id = (int)$r[DATA_REL_TYPE_POS];
		$rel_type = Word::findRelationTypeById($word, $rel_type_id);
		
		$rel_weight = (int)$r[DATA_REL_WEIGHT_POS];

		// instantiation de la relation + ajout de la relation dans le noeud
		$rel_obj = Relation::instantiate($rel_id, $rel_node, $rel_weight, $is_rel_out);
		$rel_type->associated_relations[] = $rel_obj;
	}
}

function data_to_obj(&$data) // const data (/!\ ref)
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

function data_parser(&$html_data)
{
	$raw_data = get_raw_data($html_data);
	$parsed_data = parse_raw_data($raw_data);
	$data = data_to_obj($parsed_data);
	
	return $data;
}
