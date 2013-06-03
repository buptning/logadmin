<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

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

		$this->srcFile="/search/logfilesguan/log_".$this->designedYesDay."/log_opengamehall";
		$this->uvdataDict=array();
		$this->pvdataDict=array();
	} 
	

	private function __saveUvPv($field,$type,$hid){
		$this->uvdataDict[$field][$type][$hid]=1;
	    $this->uvdataDict[$field]['all'][$hid]=1;
        $this->pvdataDict[$field][$type]=(isset($this->pvdataDict[$field][$type])?$this->pvdataDict[$field][$type]:0)+1;
        $this->pvdataDict[$field]['all']+=1;
	}

	//业务逻辑
	private function __processFile($line){
		 $type=$this->logParser->get_field('&v=','&',$line);
         $hid=$this->logParser->get_field('h=','&',$line);
         if(isset($type)&& isset($hid)){
      		if (preg_match('/type=[013]/', $line)===1){
	       		$this->__saveUvPv('opengamehall',$type,$hid);
	        }
	        if (preg_match('/type=[02]/', $line)===1){
	       		$this->__saveUvPv('opengamehall_initiative',$type,$hid);
	        }
	        if (preg_match('/type=1/', $line)===1){
	       		$this->__saveUvPv('opengamehall_autoopen',$type,$hid);
	        }
	        if (preg_match('/type=3/', $line)===1){
	       		$this->__saveUvPv('opengamehall_afterinstall',$type,$hid);
	        }

         }
   	}

	public function task_run(){
		$fileList=$this->logParser->getFileList($this->srcFile);
		foreach ($fileList as $file) {
			$fp = fopen ( $file, "r" );
			while ( ! feof ( $fp ) ) {
				$line = fgets ( $fp );
				$this->__processFile($line);
			}
			fclose ( $fp );

		}
		$this->__importSql();

	}

	private function __importSql(){
		
		$sqlResult=array();
		foreach ($this->uvdataDict as $field => $uvFieldDict) {

			if($field==='opengamehall'){
				$uv='uv';
				$pv='pv';
			}
			else{
				$pv=$field;
				$uv=$field."_uv";
			}

			foreach ($uvFieldDict as $type => $value) {
				$sqlResult[$type][$uv]=count($this->uvdataDict[$field][$type]);
				$sqlResult[$type][$pv]=$this->pvdataDict[$field][$type];
			}
		}


		$filter_array=array('date'=> $this->designedate);
		$this->dataDAL->delete_one("gamehallversion_guan",$filter_array);
		foreach ($sqlResult as $type => $fieldArray) {
			$source['date']=$this->designedate;
			$source['version']=$type;
			foreach ($fieldArray as $field => $count) {
				$source[$field]=$count;
			}
			$this->dataDAL->insert_one($source,'gamehallversion_guan');

		}
	}

}


$model=new model();

//$model->filter();
$model->task_run();



?>
