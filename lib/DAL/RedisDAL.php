<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangjianzhou
 * Date: 12-5-18
 * Time: 下午12:21
 * To change this template use File | Settings | File Templates.
 */
LoadPHPFile::load("ConnectToRedis","lib/DAL/") ;
LoadPHPFile::load("DALConfig","lib/DAL/") ;
class RedisDAL
{
   private $redis;
   public function __construct($server_no=0)
   {   
       //$this->redis=new Redis();
       $address=DALConfig::$config_redis[$server_no]["host"];
       $port=DALConfig::$config_redis[$server_no]["port"];
       //$this->redis->connect($address, $port);
       $this->redis=ConToRedis::GetCon($address,$port);
   }
   public function get_value($dataid,$key)
   {
       return $this->redis->hget($dataid,$key);
   }
    public function set_value($dataid,$key,$value,$time=NULL)
    {
       $data=array();
       $data[$key]=$value;
       $temp=$this->redis->hmset($dataid,$data) ;
       if($time&&$temp)
       {
          $this->redis->expireAt($dataid, time()+$time);
       }
        return $temp;
    }
    public function increase_value($dataid,$key,$skip=1)
    {
        if(is_int($skip))
        {
            $this->redis->hIncrBy($dataid,$key,$skip);
        }
    }
    public function update_value($dataid,$key,$value,$time=NULL)
    {
        if($this->is_existed($dataid,$key))
        {
            $this->clear_value($dataid,$key);
            return $this->set_value($dataid,$key,$value,$time);
        }
        else
        {
            return $this->set_value($dataid,$key,$value,$time);
        }
    }
    public function get_count($dataid)
    {
        return $this->redis->hLen($dataid);
    }
    public function clear_value($dataid,$key)
    {
        return $this->redis->hdel($dataid,$key);
    }
    public function is_existed($dataid,$key)
    {
       return $this->redis->hexists ($dataid,$key);
    }
    public function drop_table($table_name)
    {
       return $this->redis->del($table_name);
    }
    #获取一个哈希表的所有key
    public function get_hashtable_keys($table_name)
    {
        return $this->redis->hKeys($table_name);
    }
    #获取一个哈希表中的所有key以及对应的value
    public function  get_hashtable_key_and_value($table_name)
    {
       return $this->redis->hVals($table_name);
    }
    #向为key的队列中添加元素
    public function add_value_to_queue($key,$value,$pos="left")
    {
         if($pos=="left")
         {
             debug($key);
             debug($value);
             return $this->redis->lPush($key,$value) ;
         }
        else
        {
            return $this->redis->rPush($key,$value) ;
        }
    }
    #弹出为key的队列中的一个元素
    public function get_one_value_from_queue($key,$pos="left")
    {
         if($pos=="left")
         {
             return $this->redis->lPop($key)  ;
         }
        else
        {
            return $this->redis->rPop($key) ;
        }
    }
    #获取为$key的队列中的元素个数
    public function get_coung_of_list($key)
    {
        return $this->redis->lSize($key) ;
    }
    #向排序链表中添加数值,数值只能是整数
    public function add_data_to_sort_list($data_id,$key,$value=1,$is_increase=true)
    {
        if($is_increase)
        {
            return $this->redis->zIncrBy($data_id,$value,$key);
        }
        else
        {
            return $this->redis->zAdd($data_id,$value,$key);
        }
    }
    #获取排序链表中某个元素的分值
    public function get_value_from_sort_list($data_id,$key)
    {
        return $this->redis->zScore($data_id,$key);
    }
    #从排序链表中获取一定范围的元素
    public function get_range_from_sort_list($data_id,$sta,$end,$is_reverse=true,$with_score=true)
    {
       if(!$is_reverse)
       {
         return $this->redis->zRange($data_id, $sta,$end, $with_score);
       }
        else
        {
            return    $this->redis->zRevRange($data_id, $sta,$end, $with_score);
        }
    }
    #从排序链表中删除一定的元素
    public   function delete_data_from_sort_list($data_id,$key)
    {
       return $this->redis->zDelete($data_id, $key);
    }

}
