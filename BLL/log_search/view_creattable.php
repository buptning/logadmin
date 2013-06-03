<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../js/jquery_1.7.2.js"></script>
<script type="text/javascript" src="../js/base.js"></script>
 

</head>
<body topmargin="8">
<?php


ini_set("display_errors", "On"); 
error_reporting(E_ALL);

require '../../Config.php';
LoadPHPFile::load("lib/DAL/DBShard");
$dataDAL=DBShard::shard(2);
?>


<form name="form1" action="model_creattable.php" method="post">

      <div style="height:30px;">
    &nbsp;&nbsp;&gt;&gt; 您所在的位置：建表<span style='color:gray;'>&nbsp;&nbsp;(功能介绍：所建表的主键均为自增，日期类型均为char(8),输入的各字段的类型均为int unsigned)</span>
    </div>  


<table  width="98%" border="0" align="center" cellpadding="2" cellspacing="2"  style="border: 1px solid rgb(207, 207, 207); background-color: rgb(255, 255, 255);  background-position: initial initial; background-repeat: initial initial;">
  <tbody>




    <tr>
      <td height="24"  class="bline">
        <table>
        <tbody>
          <tr>
          <td width="90">表名：</td>
          <td>
            <input name="tbl_name" type="text"   class="option" style="width:120px" value=""/>
          </td>

            <td width="90">任务下发者：</td>
          <td>
            <input name="writer" type="text" id="writer"  class="option" style="width:120px" value=""/>
          </td>

        </tr>
      </tbody></table>
      </td>
   </tr>




    <tr>
      <td height="24"  class="bline">
      	<table>
        <tbody>
          <tr>
          <td width="90">表的整型字段：</td>
          <td width="520"><input name="field_list" type="text" id="field_list" style="width:500px" value="" size="16"></td>
          <td style="color:grey;">必填，多个字段以空格隔开</td>
        </tr>
      </tbody></table>
      </td>
   </tr>
  


    <tr>
      <td height="24"  class="bline">
        <table >
        <tbody>
          <tr>
          <td width="90px">关键字(可选):</td>	
          <td width="120px"><input type="text" name="key" id="key" class="option" style="width:120px;" value=""></td>
            
          <!-- <td width="90px">字段大小(可选):</td> <td width="120px"><input type="text" name="size" id="size" class="option" style="width:120px;" value=""></td>
          <td >(varchar类型的大小，一般设置为30)</td>-->
        </tr>
      </tbody></table>
      </td>
    </tr>



      <tr>
      <td height="24" colspan="4"><input type="submit" name="submit"  style="width:56;height:24px;"></td>
      </tr>


  </tbody></table>


</form>
<div style="height:20px">

</div>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="2" id="adset" style="border: 1px solid rgb(207, 207, 207); background-color: rgb(255, 255, 255);  background-position: initial initial; background-repeat: initial initial;">

        <tbody><tr>
          <td width="90"><input type="button" id="showlog" value="查看日志部分内容：" style="height:24px"> </input></td>
          <td width="1200"><textarea name="description" rows="3" id="description" style="width:80%"></textarea></td>
          <td >&nbsp;</td>
	        <td >&nbsp;</td>
        </tr>
      </tbody></table>
</body>
     
</html>   