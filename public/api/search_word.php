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

	$is_pagined = (isset($_GET[PARAMETER_PAGE]) && isset($_GET[PARAMETER_PAGE_NAME]) && is_numeric($_GET[PARAMETER_PAGE]));

	$data = ($is_pagined)
		? Word::findRelationTypeByName($data, $_GET[PARAMETER_PAGE_NAME])
		: $data;
	
	$page = ($is_pagined) ? $_GET[PARAMETER_PAGE] : 0;
	$selector = ($is_pagined) ? "RelationType" : "Word";

	$filters[] = new FilterLimit($page, $nb_terms);
	
	$selector::filterRelations($data, $filters);
	$selector::calcNbPages($data, $nb_terms);
	
	$bench_filter->end();

	return $data;
}

include("./functions/loader.php");
