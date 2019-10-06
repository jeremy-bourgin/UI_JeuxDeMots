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
	check_and_delete();
	return file_put_contents(word_to_filename($word), $data);
}

function retrieve_cache ($word)
{
	$filename = word_to_filename($word);
	$result = file_get_contents($filename);

	return $result;
}

function check_and_delete()
{
	$files = glob(CACHE_DIRECTORY."\*");
	$exclude_files = array('.', '..');

	if (!in_array($files, $exclude_files)) {
		array_multisort(
			array_map('filemtime',$files),
			SORT_ASC,
			$files
		);
	}

	if(count($files) >= LIMIT_NB_CACHE_FILE)
		unlink($files[0]);
}

?>