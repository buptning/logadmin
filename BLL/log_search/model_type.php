<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

ini_set("display_errors", "On"); 
error_reporting(E_ALL);

require '../../Config.php';



LoadPHPFile::load("lib/DAL/DBShard");

//var_dump($_POST);

class model {
	//原始文件

	public $cmd;	
	public function __construct(){
			
		$this->srcFile=$_POST['source'];

		//获取页面的去重标记，机器码标记
		$this->hidword=trim($_POST["hidword"]);
		if($this->hidword===''){
			die('机器码标识为空');
		}
		
		$this->type_field=trim($_POST["type_field"]);
		if($this->type_field===''){
			die('类型字段标识为空');
		}
		
		$this->count_field=trim($_POST['count_field']);
		if (preg_match("/^[\\s]*$/", $_POST['count_field'])){
			die("需要统计的字段列表没有填写！");
		}


		//var_dump($this->srcFile);
		/*
		if (!file_exists($this->srcFile)||is_readable(trim($this->srcFile)) == false ) { 
			die('文件不存在或不可读');
			echo "<br>";
	
		}
		*/

		$this->dataDAL=DBShard::shard();


	} 
	
	private function __get_field($field1,$field2,$line){
		if (strpos($line,$field1)!==false){
			$len=strlen($field1);
			$start=strpos($line,$field1)+$len;
			$end=strpos($line,$field2,$start);
			$result=substr($line,$start,$end-$start);
			return $result;
		}
	}
	private function __getFileList($dirname){
		$dirList=array();
		$dir=opendir($dirname);
		
		while(is_resource($dir) && ($fileName =readdir($dir))!==false){//遍历开始
			$file=$dirname.'/'.$fileName;//提取地址
			if($fileName!="." && $fileName!=".."){//去掉“.”和“..”
				if(!is_dir($file)){//判断是文件还是目录
					$dirList[]=$file;
				}
			}
		}
		closedir($dir);
		return $dirList;
	}

	public function task_run(){
		if(is_dir($this->srcFile)){
			
		}
		$rs=array();

		foreach ($this->count_field as $key) {
			//echo $key;
			if ($key===''){
				continue;
			}
		}

		//输出结果
		echo '<br>';
		foreach ($rs as $key => $value) {
			# code...
			echo $key."<br>";
			echo $value."<br><br>";


		}


	}

	public function importSql(){

		$source=array();
		$source['state']=0;
		$source['command']=$this->cmd;
		$source['starttime']=date("Y-m-d H:i:s");
		$source['name']='sogou';
		$sql="select command from task_tbl";

		$result=$this->dataDAL->get_dimensions_rows($sql);
		foreach($result as  $row){
			echo '<br>';
			if ($this->cmd==$row['command']){
				echo "
				<script language='javascript' type='text/javascript'>
					window.history.go(-1);
					alert('任务添加失败！');
                                </script>
				";
				return;
			}
		}

		$this->dataDAL->insert_one($source,'task_tbl');

		//echo exec($this->cmd);

		echo '添加完成';
		echo "
			<script language='javascript' type='text/javascript'>
           			window.location.href='../../main.php';
				alert('添加任务成功！');
		    	</script>

		";

	}

}


$model=new model();

//$model->filter();
$model->task_run();
//$model->importSql();



?>
