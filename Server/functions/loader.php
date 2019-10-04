<?php
require(__DIR__ . "/constante.php");

// chaque page de l'API doivent require ce fichier et implémenter la fonction load
// load doit retourner le résultat qui doit être envoyé au client
// ce fichier se charge de gérer les erreurs et envoyer les données au client

// toute les erreurs doivent être retourné par l'interpréteur PHP
// https://www.php.net/manual/fr/function.error-reporting.php
error_reporting(-1);

// en DEV_MODE = true on affiche les erreurs
// en DEV_MODE = false on affiche pas les erreurs
$is_display_error = (DEV_MODE) ? 1 : 0;
ini_set("display_errors", $is_display_error);
ini_set("display_startup_errors", $is_display_error);

header("content-type: application/json; charset=utf-8");

// get classes
require(__DIR__ . "/../classes/Benchmark.php");
require(__DIR__ . "/../classes/Node.php");
require(__DIR__ . "/../classes/NodeType.php");
require(__DIR__ . "/../classes/Relation.php");
require(__DIR__ . "/../classes/RelationType.php");
require(__DIR__ . "/../classes/ServerException.php");
require(__DIR__ . "/../classes/Word.php");

// get functions
require(__DIR__ . "/function.php");
require(__DIR__ . "/cache_manager.php");
require(__DIR__ . "/data_parser.php");
require(__DIR__ . "/request.php");

$obj = new stdClass();
$obj->error = false;

try
{
	// appel de la fonction load
	$r = load();
	$obj->result = $r;

	if (DEV_MODE)
	{
		$bench = Benchmark::getBench();
		$obj->bench = $bench;
	}
}
catch(Throwable $e)
{
	if (DEV_MODE)
	{
		$message = $e->getMessage();
		$trace = $e->getTraceAsString();
		
		$r = $message;
		$r .= "<br><hr><br>";
		$r .= $trace;
	}
	else
	{
		$message = ($e instanceof Exception) ? $e->getMessage() : "Une erreur s'est produite";
		$r = $message;
	}
	
	$obj->error = true;
	$obj->message = $r;
}

// envoie des données au client au format JSON
$json = json_encode($obj);
echo $json;
