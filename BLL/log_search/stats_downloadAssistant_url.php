<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
//author:guanning

ini_set("display_errors", "On"); 
error_reporting(E_ALL);

require '../../Config.php';



LoadPHPFile::load("lib/DAL/DBShard");
LoadPHPFile::load("lib/logParser");
//var_dump($_POST);

class model {
	//原始文件

	public $cmd;	
	public function __construct(){
		$this->logParser=new LogParser();
		$this->designedYesDay=date("Ymd",strtotime("-1 day"));
		$this->designedate=date("Ymd");
		
		$this->dataDAL=DBShard::shard(1);
		//原始文件，文件目录改变时，更改此处
		$this->srcFile="/search/hadoopworkguan/data/log_config_".$this->designedYesDay."downloadAssistant.gif";
		echo $this->srcFile."\n";
		$this->uvdataDict=array();
		$this->pvdataDict=array();
	} 
	

	private function __saveUvPv($field,$type,$hid){
		$this->uvdataDict[$field][$type][$hid]=1;
	    $this->uvdataDict[$field]['all'][$hid]=1;
        $this->pvdataDict[$field][$type]=(isset($this->pvdataDict[$field][$type])?$this->pvdataDict[$field][$type]:0)+1;
        $this->pvdataDict[$field]['all']=(isset($this->pvdataDict[$field]['all'])?$this->pvdataDict[$field]['all']:0)+1;
	}

	//业务逻辑
	private function __processFile($line){
		 $type=$this->logParser->get_field('referer=','[ &]',$line);
         $type=$this->logParser->url_truncation(urldecode($type));
         $hid=$this->logParser->get_field('h=','&',$line);
         if(!empty($type) && isset($hid)){
      		if (strpos($line,"type=bar_show")!==false){
	       		$this->__saveUvPv('bar_show',$type,$hid);
	        }
	        if (strpos($line,"type=bar_click")!==false){
	       		$this->__saveUvPv('bar_click',$type,$hid);
	        }
	        if (strpos($line,"type=plan_show")!==false){
	       		$this->__saveUvPv('plan_show',$type,$hid);
	        }
	        if (strpos($line,"type=plan_true")!==false){
	       		$this->__saveUvPv('plan_true',$type,$hid);
	        }

         }
   	}

	public function task_run(){
		if(!file_exists($this->srcFile)){
			die("file not exists");
		}
		$fp = fopen ($this->srcFile, "r" );
		while ( ! feof ( $fp ) ) {
			$line = fgets ( $fp );
			$this->__processFile($line);
		}
		fclose ( $fp );

		
		$this->__importSql();

	}

	private function __importSql(){
		
		$sqlResult=array();
		foreach ($this->uvdataDict as $field => $uvFieldDict) {
			$pv=$field."_pv";
			$uv=$field."_uv";
			foreach ($uvFieldDict as $type => $value) {
				$sqlResult[$type][$uv]=count($this->uvdataDict[$field][$type]);
				$sqlResult[$type][$pv]=$this->pvdataDict[$field][$type];
			}
		}


		$filter_array=array('date'=> $this->designedate);
		$this->dataDAL->delete_one("downloadAssistant_url",$filter_array);
		foreach ($sqlResult as $type => $fieldArray) {
			$source=array();//此行必须加上，否则当某条记录不存在时，会读取前一条记录的$source
			$source['date']=$this->designedate;
			$source['url']=$type;
			foreach ($fieldArray as $field => $count) {
				$source[$field]=$count;
			}
			//var_dump($source);
			$this->dataDAL->insert_one($source,'downloadAssistant_url');

		}
	}

}


$model=new model();

//$model->filter();
$model->task_run();



?>
