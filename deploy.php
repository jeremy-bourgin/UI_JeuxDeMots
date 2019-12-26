<?php
$api_src_dir = "./api";
$dest_dest_dir = "./public/api";

$api_src_const = $api_src_dir . "/functions/constante.php";
$api_dest_const = $dest_dest_dir . "/functions/constante.php";

$gitkeep = "./public/.gitkeep";

$const = array(
	"DEV_MODE" => false
);

function xcopy($src, $dst)
{
    // open the source directory
    $dir = opendir($src);
	
    // Make the destination directory if not exist
	@rmdir($dst);
    @mkdir($dst);
	
    // Loop through the files in source directory 
    while($file = readdir($dir))
	{
        if ($file === '.' || $file === '..')
		{
			continue;
		}
		
		$src_file = $src . '/' . $file;
		$dest_file = $dst . '/' . $file;
		
		if (is_dir($src_file))
		{
			// Recursively calling custom copy function
			// for sub directory
			xcopy($src_file, $dest_file);
		}
		else
		{
			copy($src_file, $dest_file);
		}
    }
	
    closedir($dir);
}

function make_const($src, $dest, $const)
{
	require($src);
	
	$src_const = get_defined_constants(true)["user"];
	
	foreach($const as $const_name => &$const_value)
	{
		$src_const[$const_name] = $const_value;
	}
	
	$r = "<?php" . "\n";
	
	foreach ($src_const as $const_name => &$const_value)
	{
		$temp = var_export($const_value, true);
		$r .= "define(\"" . $const_name . "\", " . $temp . ");" . "\n";
	}
	
	file_put_contents($dest, $r);
}

xcopy($api_src_dir, $dest_dest_dir);

make_const($api_src_const, $api_dest_const, $const);

touch($gitkeep);
