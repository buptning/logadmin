<?php
error_reporting(E_ALL);
ini_set("display_errors",   "On");

$filename=$_GET['filename'];

if (is_readable(trim($filename)) == false) { 
		die('文件不存在或不可读');
		echo "<br>";
}

$file_handle = fopen($filename, "r");

$line = fgets($file_handle);

echo $line;

?>