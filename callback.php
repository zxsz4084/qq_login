<?php
//phpinfo();

require_once $_SERVER['DOCUMENT_ROOT'].'/common.php';

header("Content-type:text/html;charset=utf-8");

//应用的APPID
$app_id = "101177686";
//应用的APPKEY
$app_secret = "fd269042177c4915a64daf7d2bdb2bc0";
//成功授权后的回调地址
$my_url = "http://qq.pengduncun.com/callback.php";


//Step1：获取Authorization Code
session_start();


if(isset($_SESSION['me']['openid']))
{
	echo '已登录：<pre>';

	//测试获取信息
	$url = "https://graph.qq.com/user/get_user_info?oauth_consumer_key={$app_id}&access_token={$_SESSION['response_param']['access_token']}&openid={$_SESSION['me']['openid']}&format=json";
	$ret = http_request($url);
	$userinfo = json_decode($ret,true);
	echo '<pre>';
	print_r($userinfo);
	$_SESSION['userinfo'] = $userinfo;
	
	
	//测试刷新  callback( {"error":100006,"error_description":"param refresh token is wrong or lost "} ); 
	$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=refresh_token&"
			. "client_id=" . $app_id . "&client_secret=" . $app_secret . "&refresh_token =" . $_SESSION['response_param']['refresh_token'];
	echo $token_url;
	echo "<br>";
	
	$response  = http_request($token_url);
	var_dump($response);
	if (strpos($response, "callback") !== false)
	{
		$lpos = strpos($response, "(");
		$rpos = strrpos($response, ")");
		$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
		$msg = json_decode($response,true);
		if (isset($msg['error']))
		{
			echo "<h3>error:</h3>" . $msg['error'];
			echo "<h3>msg  :</h3>" . $msg['error_description'];
			exit;
		}
	}
	
	
	$xx = json_decode($response,true);
	var_dump($xx);
	
	$_SESSION['newtoken'] = $xx;
	
	print_r($_SESSION);
	
	exit;
}


$code = isset($_REQUEST["code"]) ? $_REQUEST["code"] : '';
if(empty($code))
{
	//state参数用于防止CSRF攻击，成功授权后回调时会原样带回
	$_SESSION['state'] = md5(uniqid(rand(), TRUE));
	//拼接URL
	$dialog_url = "https://graph.qq.com/oauth2.0/authorize?scope=all&response_type=code&client_id="
			. $app_id . "&redirect_uri=" . urlencode($my_url) . "&state=". $_SESSION['state'];
	//echo $dialog_url;exit;
	echo("<script> top.location.href='" . $dialog_url . "'</script>");
	exit;
}

logLib::log2($_REQUEST, '/da0/logs/callback.log');

//Step2：通过Authorization Code获取Access Token
if($_REQUEST['state'] == $_SESSION['state'])
{
	//拼接URL
	$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
			. "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url).'111'
			. "&client_secret=" . $app_secret . "&code=" . $code;
	
	//echo $token_url;exit;
	$response = http_request($token_url);
	if (strpos($response, "callback") !== false)
	{
		$lpos = strpos($response, "(");
		$rpos = strrpos($response, ")");
		$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
		$msg = json_decode($response,true);
		if (isset($msg['error']))
		{
			echo "<h3>error:</h3>" . $msg['error'];
			echo "<h3>msg  :</h3>" . $msg['error_description'];
			exit;
		}
	}

	//Step3：使用Access Token来获取用户的OpenID
	$params = array();
	parse_str($response, $params);
	
	$_SESSION['response'] = $response;
	$_SESSION['response_param'] = $params;
	
	$graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" .$params['access_token'];
	$str  = http_request($graph_url);
	if (strpos($str, "callback") !== false)
	{
		$lpos = strpos($str, "(");
		$rpos = strrpos($str, ")");
		$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
	}
	$user = json_decode($str,true);
	if (isset($user['error']))
	{
		echo "<h3>error:</h3>" . $user['error'];
		echo "<h3>msg  :</h3>" . $user['error_description'];
		exit;
	}
	echo("Hello " . $user['openid']);
	
	$_SESSION['me'] = $user;

	//print_r($_SESSION);
	//exit;
	echo("<script> top.location.href='/callback.php'</script>");
	
}
else
{
	echo("The state does not match. You may be a victim of CSRF.");
}

function http_request($url,$timeout=30,$header=array()){
	if (!function_exists('curl_init')) {
		throw new Exception('server not install curl');
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	if (!empty($header)) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	}
	$data = curl_exec($ch);
	list($header, $data) = explode("\r\n\r\n", $data);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ($http_code == 301 || $http_code == 302) {
		$matches = array();
		preg_match('/Location:(.*?)\n/', $header, $matches);
		$url = trim(array_pop($matches));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$data = curl_exec($ch);
	}

	if ($data == false) {
		curl_close($ch);
	}
	@curl_close($ch);
	return $data;
}

?>