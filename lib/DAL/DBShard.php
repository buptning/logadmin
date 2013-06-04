<?php

LoadPHPFile::load("lib/DAL/DataDAL");
LoadPHPFile::load("lib/DAL/DALConfig");
class DBShard
{
    public static function shard($db_no)
    {
        return new DataDAL($db_no); 
    }
}
