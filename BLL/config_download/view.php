<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



  <style>
  body{
	font:12px Verdana,Arial,Tahoma;
  }

 .fb span{
	float:left;
	width:120px;
	text-align:right;
	padding-right:6px;
	color:#888;
}
.ipt-txt{
	line-height:15px;
	padding:4px 5px;
	border-width:1px;
	border-style:solid;
	border-color:#666 #BBB #BBB #666;
	background-color:rgb(255, 255, 204);
	font-size:12px;
	margin-right:2px;
}


  table
  {
  border-collapse:collapse;
  }

  table,tr, td, th
  {
  border: 1px solid black;
  }
 </style>


<h1>config日志下载</h1>
</head>
<body>
<?php

error_reporting(E_ALL & ~E_NOTICE);
require '../../Config.php';
LoadPHPFile::load("lib/DAL/DBShard");
$dataDAL=DBShard::shard();
?>

<div class='userlogin'>
<form action="model.php" method="get">
  <div class='fb'>
   <span>请输入包含字符串：</span><input type="text" name="param" class='ipt-txt'/>
  </div>
   <div class='fb'>
  <span>起始日期：</span><input type="text" name="startDate" class='ipt-txt'/>
    </div>
    <div class='fb'>
  <span>计算天数：</span> <input type="text" name="days" class='ipt-txt' />
   </div>
  <input type="submit" value="提交任务" />
</form>
</div>

</body>
</html>
