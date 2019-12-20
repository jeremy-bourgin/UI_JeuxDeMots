<?php
function load()
{
	if (!isset($_GET[PARAMETER_TERM]))
	{
		throw new ServerException("Requête invalide");
	}
	
	$data = request_search($_GET[PARAMETER_TERM]);
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

	$is_pagined = (
		isset($_GET[PARAMETER_PAGE])
		&& isset($_GET[PARAMETER_PAGE_NAME])
		&& isset($_GET[PARAMETER_PAGE_INOUT])
		&& is_numeric($_GET[PARAMETER_PAGE])
		&& ($_GET[PARAMETER_PAGE_INOUT] === PARAMETER_PAGE_IN || $_GET[PARAMETER_PAGE_INOUT] === PARAMETER_PAGE_OUT)
	);

	$page = ($is_pagined) ? $_GET[PARAMETER_PAGE] : 0;
	$filters[] = new FilterLimit($page, $nb_terms);

	if ($is_pagined)
	{
		$relation_type =  Word::findRelationContainerByName($data, $_GET[PARAMETER_PAGE_NAME]);
		$data = ($_GET[PARAMETER_PAGE_INOUT] === PARAMETER_PAGE_IN)
			? $relation_type->relations_in
			: $relation_type->relations_out;
		
		RelationContainer::filterRelations($data, $relation_type, $filters);
	}
	else
	{
		Word::filterRelations($data, $filters);

		if (empty($data->relation_types))
		{
			throw new ServerException('Pas de résultat');
		}
	}

	$bench_filter->end();

	return $data;
}

function jsoned_data(string &$json)
{

}

include("./functions/loader.php");
