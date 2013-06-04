<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangjianzhou
 * Date: 12-5-29
 * Time: 下午5:22
 * To change this template use File | Settings | File Templates.
 */
LoadPHPFile::load("DALConfig","lib/DAL/") ;
class MemcacheDAL
{
     private $memcached;
     public function __construct()
     {
         $address=DALConfig::$config_memcache["host"];
         $port=DALConfig::$config_memcache["port"];
         $this->memcached=new Memcache() ;
         $this->memcached->connect($address,$port) ;;
     }
     public function set_value($key,$value,$timeout=0)
     {
        $temp=$this->memcached->replace($key,$value,false,$timeout) ;
        if($temp)
        {
            return true;
        }

        return $this->memcached->set($key,$value,0,$timeout);
     }
    public function get_value($key)
    {
        return $this->memcached->get($key);
    }
    public function delete_value($key)
    {
        if(empty($key)||$key=="")
        {
            return ;
        }
        $temp=$this->get_value($key);
        if(isset($temp))
        {
            return $this->memcached->delete($key) ;
        }
    }
    public function delete_all()
    {
       return  $this->memcached->flush();
    }
    public function increase($key,$value)
    {
        return $this->memcached->increment($key,$value);
    }
}
