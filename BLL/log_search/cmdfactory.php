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
	public function __construct($argv){
		var_dump($argv);
		if (!isset($argv[1]) || !file_exists($argv[1])) {
			exit('params is wrong or file  not exist!');
		}

		$this->logParser=new LogParser();
		$this->designedYesDay=date("Ymd",strtotime("-1 day"));
		$this->designedate=date("Ymd");
		$this->dataDAL=DBShard::shard(2);

		$this->srcFile=$argv[1];

	} 
	

	private function __processFile($line){
		 $type=$this->logParser->get_field('&v=','&',$line);
         $hid=$this->logParser->get_field('h=','&',$line);
         if(isset($type)&& isset($hid)){
            $this->uvdataDict[$type][$hid]=1;
            $this->uvdataDict['all'][$hid]=1;

            $this->pvdataDict[$type]=(isset($this->pvdataDict[$type])?$this->pvdataDict[$type]:0)+1;
            $this->pvdataDict['all']+=1;

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


	public function importSql(){

		$cmdParams=array();
		$source=array();

		$lines=file($this->srcFile);
		var_dump($lines);
		foreach ($lines as $line) {
			echo $line."\n";	
			$temp=explode("\t", $line,2);
			$cmdParams[$temp[0]]=$temp[1];
			if ($temp[0]!=='date' && $temp[0]!=='tablename') {
				//sql转换特殊字符
				$temp[0]=$this->logParser->escapeSqlChars($temp[0]);
				//做替换
				$temp[1]=str_replace('%date%', $this->designedYesDay, $temp[1]);
				$source[$temp[0]]=exec($temp[1]);
			}

		}
		$source['date']=$this->designedate;
		var_dump($source);
		
		$filter_array=array('date'=> $this->designedate);
		$this->dataDAL->delete_one($cmdParams['tablename'],$filter_array);
		$this->dataDAL->insert_one($source,$cmdParams['tablename']);
	}

}


$model=new model($argv);
//$model->filter();
$model->importSql();

?>
