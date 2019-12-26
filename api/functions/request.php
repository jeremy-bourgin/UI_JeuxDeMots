<?php
declare(strict_types=1);

function make_request_url(string $url, array $params): string
{
    $delim = "?";

    foreach ($params as $p => &$e)
    {
		$value_encoded = urlencode(iconv("UTF-8", "ISO-8859-1", $e));
        $url .= $delim . $p . "=" . $value_encoded;
        $delim = "&";
    }

    return $url;
}

function get_url_request(string $term): string
{
    $params = array(
        REQUEST_SUBMIT_PARAMETER => REQUEST_SUBMIT_VALUE,
        REQUEST_TERM_PARAMETER => $term,
        REQUEST_REL_PARAMETER => ""
    );

    return make_request_url(REQUEST_URL, $params);
}

function make_request(string $term): string
{
    $url = get_url_request($term);

    $bench_request = Benchmark::startBench("request");
    $request = file_get_contents($url);
    $bench_request->end();
	
    $bench_encodage = Benchmark::startBench("encodage");
    $request = utf8_encode($request);
    $request = html_entity_decode($request, ENT_QUOTES, APP_ENCODING);
    $bench_encodage->end();

    return $request;
}

function request_cache(string $term, bool $is_assoc = false)
{
    if ((DEV_MODE && !CACHE_IN_DEV_MODE) || !has_cache($term))
    {
        return null;
    }

    $bench_cache = Benchmark::startBench("cache");
    $serialized = retrieve_cache($term);
    $data = json_decode($serialized, $is_assoc);
    $bench_cache->end();

    return $data;
}

function request_search(string $term): stdClass
{
    $term_cache = 'search.' . $term;
    $cached = request_cache($term_cache);

    if ($cached !== null)
    {
        return $cached;
    }

    $request = make_request($term);

    $bench_parser = Benchmark::startBench("parser");
    $data = data_parser($request);
    $bench_parser->end();

    $bench_cache = Benchmark::startBench("cache");
    $serialized = json_encode($data);
    save_cache($term_cache, $serialized);
    $bench_cache->end();

    return $data;
}

function request_raff(string $term, int $deep): array
{
    $result = array();

    $term_cache = 'raff.' . $term;
    $cached = request_cache($term_cache, true);

    if ($cached !== null)
    {
        return $cached;
    }
	
	$request = make_request($term);
	$data = data_parser_raff($request);
	
	if ($deep > 0)
	{
		$result[$term] = $data->definition;
	}
	
	++$deep;

    foreach ($data->raff as &$e)
    {
		$temp = request_raff($e, $deep);
		$result = array_merge($result, $temp);
    }

    $bench_cache = Benchmark::startBench("cache");
    $serialized = json_encode($result);
    save_cache($term_cache, $serialized);
    $bench_cache->end();

    return $result;
}
