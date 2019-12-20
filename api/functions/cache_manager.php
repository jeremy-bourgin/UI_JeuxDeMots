<?php
declare(strict_types=1);

function word_to_filename(string $word): string
{
	$hashed = hash("md5", $word);
	
	return CACHE_DIRECTORY . "/" . $hashed;
}

function has_cache(string $word): bool
{
	return file_exists(word_to_filename($word));
}

function save_cache(string $word, $data): void
{
	$pref = explode(".", $word, 2)[0];
	
	check_and_delete($pref);
	file_put_contents(word_to_filename($word), $data);
}

function retrieve_cache (string $word): string
{
	$filename = word_to_filename($word);

	return file_get_contents($filename);
}

function check_and_delete(string $pref): void
{
	
	$dir = CACHE_DIRECTORY . '/' . $pref . '.*';
	$files = glob($dir);

	if(count($files) < LIMIT_NB_CACHE_FILE)
	{
		return;
	}

	$oldest_mtime = time();

	foreach ($files as &$file)
	{
		$filename = $dir . $file;
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