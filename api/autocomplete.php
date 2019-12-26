<?php
function load()
{
    if (!isset($_GET[PARAMETER_AUTOCOMPLETE]))
    {
        throw new ServerException('Requête invalide');
    }

    $prefix = $_GET[PARAMETER_AUTOCOMPLETE];
    $prefix_len = strlen($prefix);
    $data = json_decode(file_get_contents(AUTOCOMPLETE_DATA));
    $result = array();

    foreach ($data as &$e)
    {
        $temp = substr($e, 0, $prefix_len);

        if ($temp !== $prefix)
        {
            continue;
        }

        $result[] = $e;
    }

    return $result;
}

include("./functions/loader.php");
