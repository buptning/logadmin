<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangjianzhou
 * Date: 12-3-20
 * Time: 下午5:46
 * To change this template use File | Settings | File Templates.
 */
LoadPHPFile::load("MemcacheDAL","lib/DAL/");
class DataCache
{
   private static function common_cache_function($cache_id,$action,$cache_time_key=null,$value=null)
   {
       if($action=="get")
       {
           return self::get_common_cache($cache_id);
       }
       if($action=="set")
       {
           if(empty($cache_time_key)||empty($value))
           {
               return;
           }
           return self::set_common_cache($cache_id,$value,NumberConfig::$config_cache_time[$cache_time_key]);
       }
       if($action=="clear")
       {
           return self::clear_common_cache($cache_id);
       }
   }
   //查询号码的最终缓存设置
   public static function query_number_cache($number,$v,$action,$value=null)
   {
       $cache_id="query_number".$number.$v;
       return self::common_cache_function($cache_id,$action,"query_number",$value);
   }
    //成就的最终缓存设置
   public static function devote_cache_function($hid,$v,$action,$value=null)
   {
       $cache_id=$v."devote".$hid;
       return self::common_cache_function($cache_id,$action,"devote",$value);
   }
    //app升级的缓存
   public static function app_cache_function($dev,$app_ver,$type,$action,$value=null)
   {
       $cache_id="app_update".$dev.$app_ver.$type;
       return self::common_cache_function($cache_id,$action,"app",$value);
   }
    //升级数据的缓存
    public static function update_cache_function($type,$data_verison,$dev,$app_version,$action,$value=null)
    {
        $cache_id="update_data".$type.$data_verison.$dev.$app_version;
        $cache_id=md5($cache_id);
        return self::common_cache_function($cache_id,$action,"update_data",$value);
    }

  //清除所有缓存
   public static function clear_all_cache()
   {
       $mem=new MemcacheDAL();
       $mem->delete_all();

   }
    //获取最新上传号码的缓存，用于号码通官网首页
   public static function get_upload_cache()
    {
        $key="latest_upload";
        $mem=new MemcacheDAL();
        $temp=$mem->get_value($key) ;
        if($temp)
        {
            return $temp;
        }

        return false;
    }
    //写入最新上传的缓存
    public static function write_upload_cache($value)
    {
        $key="latest_upload";
        $mem=new MemcacheDAL();
        $cache_time=NumberConfig::$config_cache_time["latest_upload"] ;
        $temp=$mem->set_value($key,$value,7200) ;
        if($temp)
        {
            return ;
        }
    }
    //获取标签白名单缓存
    public static function get_tag_cache()
    {
        $key="tag";
        $mem=new MemcacheDAL();
        $temp=$mem->get_value($key) ;
        if($temp)
        {
            return $temp;
        }
        return false;
    }
    //设置标签白名单缓存
    public static function write_tag_cache($value)
    {
        $key="tag";
        $mem=new MemcacheDAL();
        $cache_time=NumberConfig::$config_cache_time["white_tag"] ;
        $temp=$mem->set_value($key,$value,$cache_time);
        if($temp)
        {
            return ;
        }
    }
    /*缓存最基本的写入，清除和查找方法*/
    public static function set_common_cache($key,$value,$time=null)
    {
        $mem=new MemcacheDAL();
        $mem->set_value($key,$value,$time);
    }
    public static function get_common_cache($key)
    {
        $mem=new MemcacheDAL();
        return $mem->get_value($key);
    }
    private static function clear_common_cache($key)
    {
        $mem=new MemcacheDAL();
        return $mem->delete_value($key) ;
    }
    /*缓存最基本操作结束*/
    //原始的数据库信息缓存
    public static function set_original_number_cache($number,$value)
    {
        $cache_key="original_number".$number;
        $value=serialize($value);
        $mem=new MemcacheDAL();
        $mem->set_value($cache_key,$value,10000) ;
    }
    public static function get_original_number_cache($number)
    {
        $cache_key="original_number".$number;
        $mem=new MemcacheDAL();
        $temp=$mem->get_value($cache_key);
        if($temp)
        {
            return unserialize($temp);
        }
        return null;
    }
    public static function clear__original_number_cache($number)
    {
        $cache_key="original_number".$number;
        $mem=new MemcacheDAL();
        return $mem->delete_value($cache_key);
    }

}
