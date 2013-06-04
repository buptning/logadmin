<?php
class LogParser{
        public function __construct(){

        }

      //本函数现仅支持简单的匹配,以后修改下
	public function get_field($field1,$field2,$line){
		if (strpos($line,$field1)!==false){
			$len=strlen($field1);
			$start=strpos($line,$field1)+$len;
			preg_match("/$field2/", $line, $matches, PREG_OFFSET_CAPTURE, $start);
			//本函数仅支持简单的匹配
			if($matches){
				$end=$matches[0][1];
				//$end=strpos($line,$field2,$start);
				$result=substr($line,$start,$end-$start);
				return $result;
			}
		}
	}
	public function getFileList($dirname){
		$dirList=array();
		$dir=opendir($dirname);
		
		while(is_resource($dir) && ($fileName =readdir($dir))!==false){//±éÀú¿ªÊ¼
			$file=$dirname.'/'.$fileName;//ÌáÈ¡µØÖ·
			if($fileName!="." && $fileName!=".."){//È¥µô¡°.¡±ºÍ¡°..¡±
				if(!is_dir($file)){//ÅÐ¶ÏÊÇÎÄ¼þ»¹ÊÇÄ¿Â¼
					$dirList[]=$file;
				}
			}
		}
		closedir($dir);
		return $dirList;
	}


        public static function  line2dict($line){
                $n=strpos($line, '?');
                $p=strpos($line, ' HTTP',$n);
                $line = substr($line,$n+1,$p-$n-1);
                $tempList=explode('&', $line);
                $tempDict=array();
                foreach ($tempList as $val){
                        if ($val){
                                $tempList1 =explode('=', $val);
                                if (isset($tempList1[1])){
                                        $tempDict[$tempList1[0]]=$tempList1[1];
                                }else{
                                        $tempDict[$tempList1[0]]=Null;
                                }
                        }
                }
                return $tempDict;

        }

        public function get_pre_date($date,$n){
                date_default_timezone_set("PRC");
                return date("Ymd",strtotime($date." -$n day"));
        }

        public function get_datelist($date,$n){
                for($i=0;$i<$n;$i++){
                        $day= $this->get_pre_date($date,$i);
                        $datelist[]=$day;
                }
                return $datelist;
        }
	
	public function mkFolder($path){
	    if(!is_readable($path)){
		is_file($path) or mkdir($path);     
	    }
	    else{
		//echo 'ÎÄ¼þÃ»ÓÐ´´½¨';
	    }
	}

	public function line2Object($line){
		$pos1 = strpos($line,'fp=');
		if ($pos1>0){
			$pos2 = strpos($line,' HTTP');
			$text = substr($line,$pos1+3,$pos2-$pos1-3);
			$text = iconv('gbk','utf-8',$text);
			return json_decode($text);
		}	
	}
	public function getRsByShell($cmd){
			
		exec($cmd,$result);
		if (!isset($result[0])){
			echo $cmd."\n";
		}
		return $result[0];
		
	}

	public function escapeSqlChars($param){
		$param=str_replace('=','_',$param);
		$param=str_replace('&', '$', $param);
		return $param;

	}

	public function  twofileintersectionCmd($file1,$file2,$destfile){
		$cmd="awk 'BEGIN{count=0}NR==FNR{a[$1]=1}NR>FNR{ if(a[$1]==1){count++;print $1 >\"$destfile\"}}END{print count}' $file1 $file2";
		return $this->getRsByShell($cmd);

        }

	//file1-file2
	public function  twofilediffrentsetCmd($file1,$file2,$destfile){
		if(is_readable($file1) ){
			if( is_readable($file2) && filesize($file2)){
				$cmd="awk 'BEGIN{count=0}NR==FNR{a[$1]=1}NR>FNR{ if(a[$1]!=1){count++;print $1 >\"$destfile\"}}END{print count}' $file2 $file1";
			}
			else{
				$cmd="cp $file1 $destfile && cat $file1 | wc -l";

			}
                	return $this->getRsByShell($cmd);
		}
		else return 0;

        }

        public function url_truncation($str){
                $n1 = strpos($str,'//');
                if($n1!==false){
                        $n2= strpos($str,'/',$n1+2);
                }
                else{
                        $n2=strpos($str,'/');
                }
                if($n2===false){
                        return $str;
                }
                else{
                        return substr($str,0,$n2);
                }
        }



}
?>
