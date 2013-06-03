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

		var_dump($this->srcFile);
		/*
		if (is_readable(trim($this->srcFile)) == false) { 
			die('文件不存在或不可读');
	
		}
		*/

		if (trim($this->srcFile) =="") { 
			die('文件不存在或不可读');
	
		}
		
		$this->dataDAL=DBShard::shard();


	} 

	public function getParams(){
		//过滤词列表
		$keywords=array();
		echo $_POST['keywords']."\n";
		$keywords = explode(' ',trim($_POST['keywords']));
		var_dump($keywords);


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

		//去重标记
		$hid=1;
		if('0'===$_POST['hid']){
			$hid=0;
		}
		else{
			$hidword=$_POST["hidword"];
		}


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
			
			//用户数还是次数
			if (!$hid){
				$this->cmd.=" | wc -l";
			}
			else{
				$this->cmd.="| awk -F '".$hidword."=' '{print $2}' | awk -F '[& ]' '{print $1}' | sort | uniq | wc -l ";
			}
		}
		else {
			echo '过滤字符串不存在';
		}

		echo $this->cmd;
		echo "<br>";
		$rs=exec($this->cmd);
		//echo $rs;
		//echo "<script language='javascript' type='text/javascript' >alert(".$rs.");</script>";

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
$model->importSql();



?>
