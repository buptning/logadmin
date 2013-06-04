<?php
error_reporting(E_ALL);
ini_set("display_errors",   "On");

require '../../Config.php';
LoadPHPFile::load("lib/DAL/DBShard");
$dataDAL=DBShard::shard();


$_GET['param']='sext_eliao';
$_GET['startDate']='20130218';
$_GET['days']='1';


echo exec("./../inc/chgroot.expect");
echo exec("whoami");

var_dump($_GET);

$text="#! /usr/bin/python\n# -*- coding: cp936 -*-\n";
$text.="param='".$_GET['param']."'\n";
$text.="startDate='".$_GET['startDate']."'\n";
$text.="days=".$_GET['days']."\n";

$dir='/common_config/';
$fp=fopen($dir."lib/params.py",'w');
fwrite($fp,$text);
fclose($fp);

$cmd="cd {$dir};python run_new.py>>result.txt 2>&1";
echo $cmd;
exec($cmd);

echo 'finished!';

?>
