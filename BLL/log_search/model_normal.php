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

		$this->designedYesDay=date("Ymd",strtotime("-1 day"));
        $this->designedate=date("Ymd");			
		
		
		$this->srcFile=$_POST['source'];

		$this->cmdArray=array();
		//var_dump($this->srcFile);
		/*
		if (!file_exists($this->srcFile)||is_readable(trim($this->srcFile)) == false ) { 
			die('文件不存在或不可读');
			echo "<br>";
	
		}
		*/
		//$this->dataDAL=DBShard::shard();


	} 

	public function filter(){
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

		//获取页面的去重标记，机器码标记
		$hid=1;
		if('0'===$_POST['hid']){
			$hid=0;
		}
		else{
			$hidword=trim($_POST["hidword"]);
			if($hidword===''){
				die('机器码标识为空');
			}
		}


		//echo $this->cmd;
		//echo "<br>";

		$rs=array();

		//echo $_POST['count_field']."\n";

		if (preg_match("/^[\\s]*$/", $_POST['count_field'])){
			die("需要统计的字段列表没有填写！");
		}
		$count_field = explode(' ',trim($_POST['count_field']));
		//var_dump($count_field);

		foreach ($count_field as $key) {
			//echo $key;
			if ($key===''){
				continue;
			}
			$cmd=$this->cmd;
			$cmd=$this->cmd." | grep -ai '$key'";

			//用户数还是次数
			if (!$hid){
				$cmd.=" | wc -l";
			}
			else{
				$cmd.="| awk -F '".$hidword."=' '{print $2}' | awk -F '[& ]' '{print $1}' | sort | uniq | wc -l";
			}

			echo $cmd.'<br>';
			$this->cmdArray[$key]=$cmd;
			$rs[$key]=exec($cmd);


		}

		//输出结果
		echo '<br>';
		foreach ($rs as $key => $value) {
			# code...
			echo $key."<br>";
			echo $value."<br><br>";


		}


	}

	public function makeProduct(){
		
		/*
		$tablename='tablename';
		$filter_array=array('date'=> $this->designedate);
        $this->dataDAL->delete_one($tablename,$filter_array);
		*/
        $tablename='tablename';
		$this->cmdArray['tablename']=$tablename;
		$this->cmdArray['date']=$this->designedate;
		var_dump($this->cmdArray);
		$fp=fopen("products/{$tablename}_{$this->designedate}", 'w');
		foreach ($this->cmdArray as $key => $value) {
			echo $key."<br>";
			echo $value."<br>";
			fwrite($fp, $key."\t".$value."\n");
		}
		fclose($fp);


	}

	//old,导入任务列表的数据库
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

$model->filter();
$model->task_run();
//$model->makeProduct();
//$model->importSql();



?>
