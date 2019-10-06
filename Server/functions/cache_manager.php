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
	$dir = CACHE_DIRECTORY."/";
	$files = scandir($dir);

	if(count($files) < LIMIT_NB_CACHE_FILE + 2)
		return;

	$excluded_files = array(
		".",
		"..",
		".gitkeep"
	);


	$oldest_mtime = time();

	foreach ($files as &$file)
	{ 
		if(in_array($file, $excluded_files))
			continue;

		$filename = $dir.$file;
		$temp_mtime = filemtime($filename);

		if ($temp_mtime < $oldest_mtime)
		{
			$oldest_file = $filename;
			$oldest_mtime = $temp_mtime;

		}

	}

	unlink($oldest_file);
}

?>