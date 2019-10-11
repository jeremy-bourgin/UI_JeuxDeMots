<?php
declare(strict_types=1);

function make_request_url(string $url, array $params): string
{
    $delim = "?";

    foreach ($params as $p => &$e)
    {
        $url .= $delim . $p . "=" . $e;
        $delim = "&";
    }

    return $url;
}

function get_url_request(string $word): string
{
    $params = array(
        REQUEST_SUBMIT_PARAMETER => REQUEST_SUBMIT_VALUE,
        REQUEST_TERM_PARAMETER => $word,
        REQUEST_REL_PARAMETER => ""
    );

    $url = make_request_url(REQUEST_URL, $params);

    return $url;
}

function request(string $word): stdClass
{
    if ((!DEV_MODE || CACHE_IN_DEV_MODE) && has_cache($word))
    {
        $bench_cache = Benchmark::startBench("cache");
        $serialized = retrieve_cache($word);
        $data = json_decode($serialized);
        $bench_cache->end();

        return $data;
    }
    else
    {
        $url = get_url_request($word);

        $bench_request = Benchmark::startBench("request");
        $request = file_get_contents($url);
        $bench_request->end();

        $bench_parser = Benchmark::startBench("parser");
        $request = utf8_encode($request);
        $request = html_entity_decode($request, ENT_QUOTES, APP_ENCODING);
        $data = data_parser($request);
        $bench_parser->end();

        $bench_cache = Benchmark::startBench("cache");
        $serialized = json_encode($data);
        save_cache($word, $serialized);
        $bench_cache->end();

        return $data;
    }
}
