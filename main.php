<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>



body,table{
    font-size:12px;
}
table{
    table-layout:fixed;
    empty-cells:show; 
    border-collapse: collapse;
    margin:0 auto;
}
td{
    height:20px;
}
h1,h2,h3{
    font-size:12px;
    margin:0;
    padding:0;
}

.title { background: #FFF; border: 1px solid #9DB3C5; padding: 1px; width:90%;margin:20px auto; }
    .title h1 { line-height: 31px; text-align:center;  background: #2F589C url(th_bg2.gif); background-repeat: repeat-x; background-position: 0 0; color: #FFF; }
        .title th, .title td { border: 1px solid #CAD9EA; padding: 5px; }


/*这个是借鉴一个论坛的样式*/



table.t2{
    border:1px solid #9db3c5;
    color:#666;
}
table.t2 th {
    background-image: url(th_bg2.gif);
    background-repeat::repeat-x;
    height:30px;
}
table.t2 td{
    border:1px dotted #cad9ea;
    padding:0 2px 0;
}
table.t2 th{
    border:1px solid #a7d1fd;
    padding:0 2px 0;
}
table.t2 tr.a1{
    background-color:#e8f3fd;
}


 </style>
</head>
<body>
<?php
require 'Config.php';

LoadPHPFile::load("lib/DAL/DBShard");

$dataDAL=DBShard::shard();
$sql='select * from task_tbl';
$result= $dataDAL->get_dimensions_rows($sql);

echo '<table  width="100%" id="mytab"  border="1" class="t2">	';
echo '<thead> <th>序列号</th> <th>任务名</th> <th>下发时间</th> <th>结束时间</th><th>执行状态</th><th>命令行</th></thead>';

foreach($result as $row=>$rs){
	if ($row%2===0)
		echo '<tr class="a1">';
	else 
		echo '<tr>';

	echo "<td>{$rs['id']}</td>";
	echo "<td>{$rs['name']}</td>";
	echo "<td>{$rs['starttime']}</td>";
	echo "<td>{$rs['endtime']}</td>";
	echo "<td>{$rs['state']}</td>";
	echo "<td>{$rs['command']}</td>";
	echo '</tr>';	
}

echo '</table>';

?>
<input type=button value='刷新' onclick="window.location.reload();">
</body>
</html>
