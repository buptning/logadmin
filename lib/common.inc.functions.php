<?php

// 报错级别设定,一般在开发环境中用E_ALL,这样能够看到所有错误提示
// 系统正常运行后,直接设定为E_ALL || ~E_NOTICE,取消错误显示
error_reporting(E_ALL);
//error_reporting(E_ALL || ~E_NOTICE);

/////////////////////////wang//////////////////////////////
//拒绝请求这个脚本
function refuse_http($page_name)
{
	$file=$_SERVER["PHP_SELF"];
	$data=explode("/",$file);
	$count=count($data);
	$last=$data[$count-1];
	if(strcmp($last,$page_name)==0)
	{
		exit();
	}
}
//调试程序
function debug($data,$continue=true)
{
	if(Config::$debug)
	{
		var_dump($data);
		if(!$continue)
		{
			exit();
		}
	}
}



//////////////////////////////dedecms///////////////////////////////


if (version_compare(PHP_VERSION, '5.3.0', '<'))
{
	set_magic_quotes_runtime(0);
}

function _RunMagicQuotes(&$svar)
{
	if(!get_magic_quotes_gpc())
	{
		if( is_array($svar) )
		{
			foreach($svar as $_k => $_v) $svar[$_k] = _RunMagicQuotes($_v);
		}
		else
		{
			if( strlen($svar)>0 && preg_match('#^(cfg_|GLOBALS|_GET|_POST|_COOKIE)#',$svar) )
			{
				exit('Request var not allow!');
			}
			$svar = addslashes($svar);
		}
	}
	return $svar;
}

if (!defined('NOCHECK'))
{
	//检查和注册外部提交的变量   (2011.8.10 修改登录时相关过滤)
	function CheckRequest(&$val) {
		if (is_array($val)) {
			foreach ($val as $_k=>$_v) {
				if($_k == 'nvarname') continue;
				CheckRequest($_k);
				CheckRequest($val[$_k]);
			}
		} else
		{
			if( strlen($val)>0 && preg_match('#^(cfg_|GLOBALS|_GET|_POST|_COOKIE)#',$val)  )
			{
				exit('Request var not allow!');
			}
		}
	}

	//var_dump($_REQUEST);exit;
	CheckRequest($_REQUEST);

	foreach(Array('_GET','_POST','_COOKIE') as $_request)
	{
		foreach($$_request as $_k => $_v)
		{
			if($_k == 'nvarname') ${$_k} = $_v;
				else ${$_k} = _RunMagicQuotes($_v);
		}
	}
}
