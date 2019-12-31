<?php
define("APP_PATH", ".");

function load()
{
    if (!isset($_GET[PARAMETER_AUTOCOMPLETE]))
    {
        throw new ServerException('Requête invalide');
    }

    $prefix = strtolower($_GET[PARAMETER_AUTOCOMPLETE]);
    $prefix_len = strlen($prefix);
    $data = json_decode(file_get_contents(AUTOCOMPLETE_DATA));
	
	$i = 0;
	$count = 0;
    $result = array();

    while ($i < 662230 && $count !== AUTCOMPLETE_COUNT)
    {
		$e = $data[$i];
        $temp = strtolower(substr($e, 0, $prefix_len));

        if ($temp === $prefix)
        {
			$result[] = $e;
			++$count;
        }

		++$i;
    }

    return $result;
}

include("./functions/loader.php");
