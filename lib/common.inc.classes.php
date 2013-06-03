<?php

class Config
{
	public static function get_ab_uri()
	{
		$url="";
		if(self::$config_ab_uri)
		{
			return self::$config_ab_uri;
		}
		else
		{
			$url=dirname(__FILE__);
		}
		$url=str_replace("\\","/",$url)."/";
		self::$config_ab_uri=$url;
		return  $url;
	}
	public  static $config_ab_uri=null;
	public  static $redis_queue="baidu_data_queue";
	public  static $config_vali_number_length=7;
	//允许访问的ip
	public  static $config_allowd_ip=array("10.129.41.11"=>1);
	public static $debug=1;
}
//加载文件类
class LoadPHPFile
{
	private static $loads;
	public static function load($file_name,$load_file_dir=null)
	{
		$file_url=Config::get_ab_uri();
		if($load_file_dir)
		{
			$file_url=$file_url.$load_file_dir.$file_name.".php";
		}
		else
		{
			$file_url=$file_url.$file_name.".php";
		}
		if(isset(self::$loads[$file_url]))
		{
			return ;
		}
		if(!file_exists($file_url))
		{
			debug($file_url);
			throw new Exception("code error:file({$file_name}) is not existed !");
		}
		self::$loads[$file_url]=1;
		include($file_url);

	}
}
?>