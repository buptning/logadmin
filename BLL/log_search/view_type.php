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
$dataDAL=DBShard::shard();
?>


<form name="form1" action="model_type.php" method="post">

      <div style="height:30px;">
    &nbsp;&nbsp;&gt;&gt; 您所在的位置：列表计数统计<span style='color:gray;'>&nbsp;&nbsp;(功能介绍：比如日志中某条记录含有xxx,计算文件中xxx的值的出现总次数或机器码去重次数)</span>
    </div>


<table  width="98%" border="0" align="center" cellpadding="2" cellspacing="2"  style="border: 1px solid rgb(207, 207, 207); background-color: rgb(255, 255, 255);  background-position: initial initial; background-repeat: initial initial;">
  <tbody>

    <tr>
      <td height="24"  class="bline">
      	<table>
        <tbody>
          <tr>
          <td width="90">&nbsp;日志地址：</td>
          <td width="420"><input name="source" type="text" id="source" style="width:400px" value="" size="16"></td>
           
          <td width="90">任务下发者：</td>
          <td>
          	<input name="writer" type="text" id="writer"  class="option" style="width:120px" value=""/>
          </td>
        </tr>
      </tbody></table>
      </td>
   </tr>
  
  <tr>
   <td height="24" class="bline">
      <table >
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
      <td height="24"  class="bline">
        <table >
        <tbody><tr>
          <td width="90px">过滤字符串：</td>	
          <td width="520px"><input type="text" name="keywords" id="keywords" class="option" style="width:500px;" value=""></td>

          <td style="color:grey;">选填，多个字段以空格隔开</td>
          
        </tr>
      </tbody></table>
      </td>
    </tr>


    <tr>
      <td height="24" colspan="4" class="bline">
    </td>
    </tr>





 <tr>
      <td height="24" colspan="4" class="bline">
        <table>
        <tbody>
          <tr>
          <td width="90px">类型字段:</td>
          <td width="150px"><input name="type_field" class="option" type="text" id="type_field" value="" size="16"> <span style="color:grey;">必填</span> </td>  
         
         <td width="90px">分隔字符:</td>
          <td width="150px"><input name="delimiter_field" class="option" type="text" id="delimiter_field" value="" size="16"> <span style="color:grey;"></span> </td>  
          
          <td width="90" class = "hidflag"  >机器码标识</td>
          <td width="150" class="hidflag">
          <input type="text"  name="hidword" class="np" value="" >
          </td>

        </tr>
      </tbody></table>
      </td>
  </tr>





   <tr>
      <td height="24" colspan="4" class="bline">
        <table>
        <tbody>
          <tr>
          <td width="90px">统计的字段列表:</td>
          <td width="520px"><input name="count_field" class="option" type="text" id="countfield" style="width:500px;" value="" size="16"> </td>  
         <td style="color:grey;">必填,多个字段以空格隔开,不区分大小写 </td>

        </tr>
      </tbody></table>
      </td>
   </tr>




      <tr>
      <td height="24" colspan="4"><input type="submit" name="submit"  style="width:56;height:24px;">
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