<?php
   LoadPHPFile::load("ConnectToDB","lib/DAL/") ;
   LoadPHPFile::load("DALConfig","lib/DAL/") ;
    class DataDAL
    {
        public   $con;
        public function __construct($db_no=0)
        {
            $db_no="db".$db_no;
            $address=DALConfig::$config_mysql[$db_no]["host"];
            $name=DALConfig::$config_mysql[$db_no]["uname"];
            $pwd=DALConfig::$config_mysql[$db_no]["pwd"];
            $dbname=DALConfig::$config_mysql[$db_no]["db_name"];
            $encode=DALConfig::$config_mysql[$db_no]["encode"] ;
            $this->con=ConToDB::GetCon($address,$name,$pwd,$dbname,$encode);
        }
        //插入一条数据，$source为插入的数据数组，数组键为数据表字段，值为写入数据
        //成功返回主键值，失败返回0
        public function insert_one($source=array(),$table_name)
        {
           try
           {
               $sql="INSERT INTO $table_name(";
               $value_str=NULL;
               $key_str=NULL;
               foreach($source as $key=>$value)
               {

                   $key_str=$key_str.$key.",";

               }
               //去除最后一个逗号
               $key_str = substr($key_str,0,strlen($key_str)-1);
               $sql=$sql.$key_str.") VALUES (";
               foreach($source as $key=>$value)
               {
                   $value=mysql_real_escape_string($value);
                   $value_str=$value_str." '".$value."' ,";
               }
               $value_str = substr($value_str,0,strlen($value_str)-1);
               //最后的SQL语句
               $sql=$sql.$value_str.")";
               $result=mysql_query($sql,$this->con);
               if($result)
               {
                   return mysql_insert_id($this->con);
               }
               else
               {
                   $this->DBError();
                   return 0;
               }
           }
           catch(Exception $e)
           {
               echo $e->getMessage()."\r\n" ;
           }


        }
        /*   修改一条数据，
        */
        public function update_one($source=array(),$table_name,$filter_array)
        {
            $sql="UPDATE {$table_name} SET ";
            $update_str=null;
            foreach($source as $key=>$value)
            {
                $value=mysql_real_escape_string($value);
                $update_str=$update_str.$key."='".$value."',";
            }
            //去除最后一个逗号
            $update_str=substr($update_str,0,strlen($update_str)-1);
            $filter_str="" ;
            foreach($filter_array as $k=>$v)
            {
                $filter_str=$filter_str.$k."= '".$v."' AND ";
            }
            $filter_str=substr($filter_str,0,strlen($filter_str)-4);
            $sql= $sql.$update_str."WHERE ".$filter_str;
            mysql_query($sql,$this->con);
            if(mysql_affected_rows($this->con)>0)
            {
                return true;
            }
            $this->DBError();
            return 0;

        }
        /*
        * 获得多维多行数据
        */
        public function  get_dimensions_rows($sql)
        {
           $result=array();
           $temp=mysql_query($sql,$this->con);
           if($temp)
           {   $i=0;
               while($row=mysql_fetch_assoc($temp))
               {
                  $result[$i]=$row;
                  $i++;
               }
               unset($temp);
               return $result;
           }

        }
        /*
        * 获得多维一行数据
        */
        public function get_dimensions_one_row($sql)
        {
            $temp=mysql_query($sql,$this->con);
            if($temp)
            {
                return mysql_fetch_assoc($temp);
            }
            $this->DBError();
        }
        /*
        * 获得一维多行数据
        */
        public function get_rows($sql)
        {
            $result=array();
            $temp=mysql_query($sql);
            if($temp)
            {
                while($row=mysql_fetch_row($temp))
                {
                    $result[]=$row[0];
                }
            }
            return $result;
        }
        //根据条件删除数据
        public function delete_one($table_name,$filter_array)
        {
          $filter_str="" ;
          foreach($filter_array as $k=>$v)
           {
                $filter_str=$filter_str.$k."= '".$v."' AND ";
           }
          $filter_str=substr($filter_str,0,strlen($filter_str)-4);
          $sql="DELETE FROM {$table_name} WHERE ".$filter_str;
          mysql_query($sql,$this->con);
          if(mysql_affected_rows($this->con)>0)
          {
                return true;
          }
            $this->DBError();
            return false;
        }
        public function delete_all($table_name)
        {
            try
            {
                $sql="DELETE  FROM {$table_name}";
                if(mysql_query($sql,$this->con))
                {
                    return true;
                }
                $this->DBError();
            }
            catch(Exception $e)
            {
               echo $e->getMessage();
            }
        }
        private function DBError()
        {
            debug(mysql_error($this->con) );
        }
    }
?>
