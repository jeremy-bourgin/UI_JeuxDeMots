<?php
define("APP_PATH", ".");

function load()
{
    if (!isset($_GET[PARAMETER_TERM]))
	{
        throw new ServerException('Requête invalide');
    }

    $term = $_GET[PARAMETER_TERM];
    $data = request_raff($term, 0);

    return $data;
}

include("./functions/loader.php");
