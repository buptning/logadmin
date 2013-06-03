<?php
    class ConToRedis
    {
        private static $singleton;
        private function __construct(){}
        public  static function GetCon($address,$port)
        {
            try
            {
                if(!isset(self::$singleton))
                {
                   self::$singleton=self::Connect($address,$port);
                }
                return self::$singleton;
                
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }
        private static function Connect($address,$port)
        {
        	try
        	{
            	$redis=new Redis();
				$redis->connect($address, $port);
				return $redis;
        	}
        	catch(Exception $e)
        	{
        		echo $e->getMessage();
        	}
        }
        public static function destroy_link()
        {
        	
        }

    }
?>
