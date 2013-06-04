<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
/*
 * author:guanning
*/
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

		//var_dump($this->srcFile);
		
		if (is_readable(trim($this->srcFile)) == false) { 
			die('文件不存在或不可读');
			echo "<br>";
	
		}
		
		//$this->dataDAL=DBShard::shard();


	} 
	private function _get_field($field1,$field2,$line){
		if (strpos($line,$field1)!==false){
			
		}
	}

	public function getParams(){
		//过滤词列表
		$keywords=array();
		//echo $_POST['keywords']."\n";
		$keywords = explode(' ',trim($_POST['keywords']));
		//var_dump($keywords);


		//大小写标记
		$caseFlag="";
		if('1'===$_POST['case']){
			$caseFlag=" -i ";
		}
		//逻辑与标记
		$logic=1;
		if('0'===$_POST['logic']){
			$logic=0;
		}



		//过滤词
		if ($keywords){
			$this->cmd="cat ".$this->srcFile;
			//逻辑与
			if(!$logic){
				foreach($keywords as $word){
					if ($word){
						$this->cmd.=" | grep {$caseFlag}'{$word}'";
					}
				}
			}
			
			//逻辑或
			else{
				$this->cmd.=" | grep -E {$caseFlag}'";
				foreach($keywords as $word){
					if ($word){
						$this->cmd.="{$word}|";
					}
				}
				$this->cmd=rtrim($this->cmd,"|");
				$this->cmd.="'"; 
				
			}
	
		}
		else {
			echo '过滤字符串不存在';
		}


	}

	public function task_run(){
		//echo $this->cmd;
		//echo "<br>";

		$rs=array();

		//echo $_POST['count_field']."\n";
		$count_field = explode(' ',trim($_POST['count_field']));
		//var_dump($count_field);

		foreach ($count_field as $key) {
			//echo $key;

			if ($key===''){
				continue;
			}
			$cmd=$this->cmd;
			$newkey='&'.$key.'=';
			$cmd=$cmd." | grep -ai '$newkey'| awk -F '$newkey' '{print $2}'| awk -F '[& ]' '{ a+=$1;}END {print a}' ";
			//echo $this->cmd;

			$temp=exec($cmd);
			if ($temp===''){
				$temp=0;
			}
			$rs[$key]=$temp;
		}

		//输出结果
		foreach ($rs as $key => $value) {
			# code...
			echo $key."\t";
			echo $value."<br>";


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

$model->getParams();
$model->task_run();
//$model->importSql();



?>
