<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangjianzhou
 * Date: 12-9-12
 * Time: 下午12:33
 * To change this template use File | Settings | File Templates.
 */
$file=$_SERVER["PHP_SELF"];
$data=explode("/",$file);
$count=count($data);
$last=$data[$count-1];
if(strcmp($last,"DALConfig")==0)
{
    exit();
}
unset($data);
unset($count);
unset($last);
class DALConfig
{
    public static $config_mysql=array(
        "db0"=>array("uname"=>"","pwd"=>"","host"=>"","db_name"=>"","encode"=>"UTF8"),
        "db1"=>array("uname"=>"","pwd"=>"","host"=>"","db_name"=>"","encode"=>"gbk"),
        "db2"=>array("uname"=>"","pwd"=>"","host"=>"","db_name"=>"","encode"=>"gbk"),
    );
    //memcache的信息
    public static $config_memcache=array("host"=>"","port"=>11211) ;
    //redis配置信息
    //redis配置信息
    public static $config_redis=array(
        0=>array("host"=>"","port"=>6379),
        1=>array("host"=>"","port"=>6379)
    );
}
