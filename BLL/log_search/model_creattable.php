<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
/*
 * author:guanning
*/
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

		//$this->dataDAL=DBShard::shard();
		$this->logParser=new LogParser();

	} 

	public function filter(){

		$tbl_name=trim($_POST['tbl_name']);
		$field_list=array();
		//echo $_POST['keywords']."\n";
		if(trim($_POST['field_list'])===''|| $tbl_name===''){
			die('表名或者字段列表没有填写！');

		}

		$field_list = explode(' ',trim($_POST['field_list']));

		$key=trim($_POST['key']);
		

		$DB_HOST='';
		
		/*
		$DB_USER='';
		$DB_PWD='';
		$DB_NAME='';
		*/

	
	
		mysql_connect($DB_HOST,$DB_USER,$DB_PWD) or die("database connect failed");
		mysql_select_db($DB_NAME);


		$sql="CREATE TABLE $tbl_name (id int unsigned auto_increment primary key, date char(8),";
		if ($key!==''){
			$sql.="$key varchar(30),";
		}




		foreach($field_list as $field){
				if (trim($field)!==''){
			        $field=$this->logParser->escapeSqlChars($field);
			        $sql.=$field.' int(10) unsigned, ';
			    }
		}

		$sql =trim($sql,', ');
		$sql.=')';
		echo $sql;

		$sql= mysql_real_escape_string($sql);
		
		echo $sql;
		
		if(!mysql_query($sql)) {
		  echo mysql_error() ;
		}
		





	}

	public function task_run(){

		

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

$model->filter();
$model->task_run();



?>
