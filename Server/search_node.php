<?php
function load()
{
	if (!isset($_GET[PARAMETER_TERM]) || !isset($_GET[PARAMETER_NODE]))
	{
		throw new ServerException("Requête invalide");
	}
	
	$data = request($_GET[PARAMETER_TERM]);
	Word::filterNodes($data, $_GET[PARAMETER_NODE]);
	
	return $data;
}

include("./functions/loader.php");
