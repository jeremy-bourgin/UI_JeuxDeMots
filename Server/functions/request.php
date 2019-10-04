<?php

function make_request_url($url, $params)
{
    $delim = "?";

    foreach ($params as $p => &$e)
    {
        $url .= $delim . $p . "=" . $e;
        $delim = "&";
    }

    return $url;
}

function get_url_request($word)
{
    $params = array(
        REQUEST_SUBMIT_PARAMETER => REQUEST_SUBMIT_VALUE,
        REQUEST_TERM_PARAMETER => $word,
        REQUEST_REL_PARAMETER => ""
    );

    $url = make_request_url(REQUEST_URL, $params);

    return $url;
}

function request($word)
{
    if (!DEV_MODE && has_cache($word))
    {
        $serialized = retrieve_cache($word);

        $bench_unserialize = Benchmark::startBench("unserialize");
        $data = json_decode($serialized);
        $bench_unserialize->end();

        return $data;
    }
    else
    {
        $url = get_url_request($word);

        $request = utf8_encode(file_get_contents($url));
        $data = data_parser($request);

        $bench_serialize = Benchmark::startBench("serialize");

        $serialized = json_encode($data);
        save_cache($word, $serialized);

        $bench_serialize->end();

        return $data;
    }
}
