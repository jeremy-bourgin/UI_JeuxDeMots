<?php
function load()
{
    if (
        !isset($_POST[PARAMETER_TERM])
        || !isset($_POST[PARAMETER_RAFF])
        || !is_array($_POST[PARAMETER_RAFF])
    ) {
        throw new ServerException('Requête invalide');
    }

    $term = $_POST[PARAMETER_TERM];
    $raff = $_POST[PARAMETER_RAFF];

    $data = request_raff($term, $raff);

    return $data;
}

function jsoned_data(string &$json)
{
    $bench_cache = Benchmark::startBench("cache");

    $term_cache = 'raff.' . $_POST[PARAMETER_TERM];
    save_cache($term_cache, $json);

    $bench_cache->end();
}

include("./functions/loader.php");
