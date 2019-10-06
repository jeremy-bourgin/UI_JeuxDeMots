<?php
function load()
{
	if (!isset($_GET[PARAMETER_TERM]))
	{
		throw new ServerException("Requête invalide");
	}
	
	$data = request($_GET[PARAMETER_TERM]);
	
	if (isset($_GET[PARAMETER_NOT_OUT]))
	{
		Word::deleteOutRelations($data);
	}
	
	if (isset($_GET[PARAMETER_NOT_IN]))
	{
		Word::deleteInRelations($data);
	}
	
	Word::limit($data, LIMIT_NB_WORD);
	
	return $data;
}

include("./functions/loader.php");
