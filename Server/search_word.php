<?php
function load()
{
	if (!isset($_GET[PARAMETER_TERM]))
	{
		throw new ServerException("Requête invalide");
	}
	
	$data = request($_GET[PARAMETER_TERM]);
	$filters = array();
	
	$bench_filter = Benchmark::startBench("filter");
	
	if (isset($_GET[PARAMETER_NOT_OUT]))
	{
		$filters[] = new FilterOut();
	}
	
	if (isset($_GET[PARAMETER_NOT_IN]))
	{
		$filters[] = new FilterIn();
	}
	
	if (isset($_GET[PARAMETER_NODE]))
	{
		$filters[] = new FilterNode($_GET[PARAMETER_NODE]);
	}

	$nb_terms = (isset($_GET[PARAMETER_NB_TERMS]) && is_numeric($_GET[PARAMETER_NB_TERMS]))
		? $_GET[PARAMETER_NB_TERMS]
		: LIMIT_NB_WORD;
	
	foreach ($data->relation_types as &$rt)
	{
		$relation_name = $rt->name;

		$page = (isset($_GET[$relation_name]) && is_numeric($_GET[$relation_name]))
			? $_GET[$relation_name]
			: 0;

		$filters[] = new FilterLimit($relation_name, $page, $nb_terms);
	}
	
	Word::filterRelations($data, $filters);
	Word::calcNbPages($data, $nb_terms);
	
	$bench_filter->end();
	
	return $data;
}

include("./functions/loader.php");
