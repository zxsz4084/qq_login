<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../config/PicConfig.php';

/*********日志规则************/
//初始化日志记录方式
$writeLevel = array(logExtendLib::LOG_LEVEL_DEBUG,  //记录debug
                    logExtendLib::LOG_LEVEL_ERROR,  //记录错误
                    logExtendLib::LOG_LEVEL_FATAL,
                    logExtendLib::LOG_LEVEL_LOG,
                    logExtendLib::LOG_LEVEL_SERIOUS,
                    );  //记录访问日志以内的所有日志，包括访问日志
$file       = utilLib::getScriptName();
//echo $file;
logExtendLib::logInnit($writeLevel, $file);


/**********记录访问日志****************/
if($_GET)
{
	logExtendLib::logAccess($_GET, '请求参数GET：');
}
if($_POST)
{
	logExtendLib::logAccess($_POST, '请求参数POST：');
}


//html5跨域资源共享 (CORS)
//logLib::log2($_SERVER, '/da0/logs/server.log');
//值形式为 http://dev.v.6.cn
if(isset($_SERVER['HTTP_ORIGIN']) && (false!==strpos($_SERVER['HTTP_ORIGIN'],'v.6.cn') || false !== strpos($_SERVER['HTTP_ORIGIN'],'pression.demo')  || false !== strpos($_SERVER['HTTP_ORIGIN'],'www.chaojibiaoqing.com')))
{
	$origin = $_SERVER['HTTP_ORIGIN'];
	
	//支持跨域发送cookies
	header("Access-Control-Allow-Credentials:true");
	header("Access-Control-Allow-Origin:{$origin}");
	header("Access-Control-Max-Age: 86400");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Allow-Headers: origin, access-control-allow-credentials, content-type");
		
}

//图片最大20M
define('MAX_SIZE', 20971520);

?>