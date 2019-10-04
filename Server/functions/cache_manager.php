<?php
function word_to_filename($word)
{
	return CACHE_DIRECTORY . "/" . hash("md5", $word);
}

function has_cache($word) 
{
	return file_exists(word_to_filename($word));
}

function save_cache($word, $data)
{
	return file_put_contents(word_to_filename($word), $data);
}

function retrieve_cache ($word)
{
	$filename = word_to_filename($word);
	$result = file_get_contents($filename);

	return $result;
}

?>