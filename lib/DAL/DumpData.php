<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangjianzhou
 * Date: 12-9-20
 * Time: 下午5:06
 * To change this template use File | Settings | File Templates.
 */
LoadPHPFile::load("lib/DAL/DBShard") ;
class DumpData
{
     private $dal ;
     private $max_count=10000;
     public function __construct()
     {
         $this->dal=DBShard::shard("r");
     }


     public function dump_table($table_name,$col_name=array(),$file=null)
     {
        $part_sql="";
        $result=array();
        foreach($col_name as $row)
        {
             $part_sql=$part_sql.$row.",";
        }
        $part_num=$this->max_count;
        $part_sql=substr($part_sql,0,-1);
        $count=$this->get_count_of_table($table_name);
        $sql="SELECT ".$part_sql." FROM ".$table_name;
        $inden=0;
        $temp=array();
        while($inden<$count)
        {
          $new_sql=$sql;
          $query_sql=$new_sql." LIMIT $inden ,$part_num";
          $inden=$inden+$part_num;
          $temp=$this->dal->get_dimensions_rows($query_sql);
          if($file)
          {
            $this->log_data($temp,$file)  ;
             unset($temp);
          }
          else
          {
              foreach($temp as $row);
              {
                  $result[]=$row;
              }
              unset($temp);
          }
        }
         return $result;

     }
    private function log_data($data,$file,$segment=" ")
    {
        $fp=fopen($file,"a");
        foreach($data as $row)
        {
            foreach($row as $v)
            {
                fwrite($fp,$v.$segment);
            }
            fwrite($fp,"\n");
        }
        fclose($fp);
    }
    private function get_count_of_table($table)
    {
        $sql='SELECT  COUNT(*) FROM '.$table;
        $temp=$this->dal->get_dimensions_one_row($sql);
        return $temp["COUNT(*)"];
    }
}
