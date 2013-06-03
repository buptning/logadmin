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
		$this->pvdataDict['all']=0;
	} 
	

	private function __saveUvPv($field,$type,$hid){
		$this->uvdataDict[$field][$type][$hid]=1;
	    $this->uvdataDict[$field]['all'][$hid]=1;
        $this->pvdataDict[$field][$type]=(isset($this->pvdataDict[$type])?$this->pvdataDict[$type]:0)+1;
        $this->pvdataDict[$field]['all']+=1;
	}

	private function __processFile($line){
		 $type=$this->logParser->get_field('&v=','&',$line);
         $hid=$this->logParser->get_field('h=','&',$line);
         if(isset($type)&& isset($hid)){
      		if (preg_match('/type=[013]/', $line)===1){
	            $this->uvdataDict['opengamehall'][$type][$hid]=1;
	            $this->uvdataDict['opengamehall']['all'][$hid]=1;

	            $this->pvdataDict['opengamehall'][$type]=(isset($this->pvdataDict[$type])?$this->pvdataDict[$type]:0)+1;
	            $this->pvdataDict['opengamehall']['all']+=1;
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
		$filter_array=array('date'=> $this->designedate);

		$this->dataDAL->delete_one("gamehallversion_guan",$filter_array);

		foreach ($this->uvdataDict as $type => $value) {
			$source=array();
			$source['date']=$this->designedate;
			$source['version']=$type;
			$source['uv']=count($value);
			$source['pv']=$this->pvdataDict[$type];
			$this->dataDAL->insert_one($source,'gamehallversion_guan');
		}
	}

}


$model=new model();

//$model->filter();
$model->task_run();



?>
