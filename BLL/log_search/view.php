<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../js/jquery_1.7.2.js"></script>
<script type="text/javascript" src="../js/base.js"></script>
 

<style>
.hidflag {display:none;}

 </style>

</head>
<body topmargin="8">
<?php


ini_set("display_errors", "On"); 
error_reporting(E_ALL);

require '../../Config.php';
LoadPHPFile::load("lib/DAL/DBShard");
$dataDAL=DBShard::shard();
?>

<form name="form1" action="model.php" method="post">

  <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="30"> &gt;&gt; 您所在的位置：日志条件查询</td>
    </tr>
  </table>

<table width="98%" border="0" align="center" cellpadding="2" cellspacing="2" id="adset" style="border: 1px solid rgb(207, 207, 207); background-color: rgb(255, 255, 255);  background-position: initial initial; background-repeat: initial initial;">
  <tbody><tr>
      <td height="24" colspan="4" class="bline">
      	<table width="800" border="0" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td width="90">&nbsp;日志地址：</td>
          <td width="240"><input name="source" type="text" id="source" style="width:160px" value="" size="16">
           
          <td width="90">任务下发者：</td>
          <td>
          	<input name="writer" type="text" id="writer" style="width:120px" value="">
          </td>
        </tr>
      </tbody></table>
      </td>
   </tr>
  <tr>
   <td height="24" colspan="4" class="bline">
      <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td width="90" height="22">区分大小写：</td>
          <td width="240">
          <input type="radio" name="case" class="np" value="0" checked="1">区分
          &nbsp;
          <input type="radio" name="case" class="np" value="1">不区分
          </td>
          <td width="90">逻辑选择：</td>
	  <td>
           <input type="radio" name="logic" class="np" value="0" checked="1">逻辑与
          <input type="radio" name="logic" class="np" value="1">逻辑或
          </td>

        </tr>
      </tbody></table>
    </td>
   </tr>
    
 




    <tr>
      <td height="24" colspan="4" class="bline"><table width="800" border="0" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td width="90">过滤字符串：</td>	
          <td colspan="2"><input type="text" name="keywords" id="keywords" style="width:290px;" value=""></td>
        </tr>
      </tbody></table>
      </td>
    </tr>


<tr>
   <td height="24" colspan="4" class="bline">
      <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td width="90" height="22">结果类型：</td>
          <td width="240">
          <input type="radio" name="hid" class="np" value="0" checked="1">次数
          &nbsp;
          <input type="radio" name="hid" class="np" value="1">去重用户数
          </td>
          <td width="90" class = "hidflag"  >机器码标识</td>
	  <td class="hidflag">
           <input type="text"  name="hidword" class="np" value="" >
		
          </td>
	
        </tr>
      </tbody></table>
    </td>
   </tr>

      <tr>
      <td height="24" colspan="4">

	<input type="submit" name="submit"  style="width:56;height:20;">
       </tr>


  </tbody></table>


</form>
<div style="height:20px">

</div>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="2" id="adset" style="border: 1px solid rgb(207, 207, 207); background-color: rgb(255, 255, 255);  background-position: initial initial; background-repeat: initial initial;">

        <tbody><tr>
          <td width="90"><input type="button" id="showlog" value="查看日志部分内容："> </input></td>
          <td width="1200"><textarea name="description" rows="3" id="description" style="width:80%"></textarea></td>
          <td >&nbsp;</td>
	  <td >&nbsp;</td>
        </tr>
      </tbody></table>
</body>
     
</html>   