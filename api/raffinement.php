<?php
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

function jsoned_data(string &$json)
{
    $bench_cache = Benchmark::startBench("cache");

    $term_cache = 'raff.' . $_GET[PARAMETER_TERM];
    save_cache($term_cache, $json);

    $bench_cache->end();
}

include("./functions/loader.php");
