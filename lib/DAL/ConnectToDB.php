<?php
    class ConToDB
    {
        private static $singleton=array();
        private function __construct(){}
        public  static function GetCon($address,$name,$pwd,$dbname,$encode="UTF8")
        {
            try
            {
                if(!isset(self::$singleton[$address][$dbname]))
                {
                   self::$singleton[$address][$dbname]=self::Connect($address,$name,$pwd,$dbname,$encode);
                }
                return self::$singleton[$address][$dbname];
                
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }
        private static function Connect($address,$name,$pwd,$dbname,$encode)
        {
           $link=mysql_connect($address,$name,$pwd,"new_link");
           mysql_query('SET NAMES '.$encode);
           if($link && mysql_selectdb($dbname))
           {
               return $link;
           }
           else
           {
               die(mysql_error());
           }
        }
        public static function destroy_link()
        {
            foreach (self::$singleton as $k=>$v)
            {
               foreach($v as $row)
               {

                   mysql_close($row);
               }
            }

        }

    }
?>
