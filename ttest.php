<?php

/*
 * 测试文件
 * @author chenlong@6rooms.com
 * @date 2013-12-16
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/../config/PicConfig.php';


$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : 0;
$url = isset ( $_GET ['url'] ) ? $_GET ['url'] : '';

$clientId = 'fd7a23579cc0af5ca8dc591f0be91acf';
$tokenKey = '87394320c530624ee2dc5b11baedbd24';
$secretKey = '7bd092e6ee22cf1d18bec409ae7dd0ea';

//var_dump($url);
//$url2 = 'http://vi6.6rooms.com/live/2014/05/15/14/1003v1400136684643120018.jpg';
//var_dump($url===$url2);

$params = array("urls"=>$url);

//$params = array("urls"=>array($url));
ksort($params);
$postAry = array(
		'client' => $clientId,
		'data' => json_encode(array(
				'token' => md5(json_encode($params).$tokenKey),
				'task' => $params
		)
				)
);
/*
//print_r($postAry);
//echo rc4Lib::decrypt($secretKey, $postAry['data']);
//print_r(json_decode(rc4Lib::decrypt($secretKey, $postAry['data'])));
$vars = $postAry;
$url = 'http://inner.v.6.cn/api/addTaskQueue.php?client='.$postAry['client'].'&data='.urlencode($postAry['data']);
$ch = curl_init();
$param = $vars;
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_TIMEOUT,20);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_URL,$url);
//curl_setopt($ch,CURLOPT_POST, 1);
//curl_setopt($ch,CURLOPT_POSTFIELDS,$param);
$result = curl_exec($ch);
curl_close($ch);
//$result = curlLib::curl_post("http://inner.v.6.cn", $postAry);
var_dump($result);
*/

//$url = 'http://inner.v.6.cn/api/addTaskQueue.php?client='.$postAry['client'].'&data='.urlencode($postAry['data']);
//$result = file_get_contents($url);

$requestData = $postAry;
$requestUrl = 'http://inner.v.6.cn/api/addTaskQueue.php';
$result = urlRequestLib::send_request_info($requestUrl, $requestData, 2);

var_dump($result);



exit;

$url = "http://blog.pengduncun.com/php/test.php";
$xx = urlRequestLib::send_request_info($url, array('a'=>1),2,1);
var_dump($xx);

exit("forbidden");
echo "<pre>";

$mcKey = "PicUploadLogsMod::".$id;
echo $mcKey;
$xx = mcLib::getInstance('mcEvent')->getMcRow($mcKey);
var_dump($xx);
$yy = mcLib::getInstance('mcEvent')->getMcRowOutTime($mcKey);
var_dump($yy);

$obj = new PicUploadLogsMod();
$idAry = array($id);
$mm = $obj->getStatusByIdAry($idAry);
print_r($mm);

/*
$mcString = 'mcMain';
$mc = mcLib::getInstance($mcString);
$mcKey = 'chenlongtest::xx';

//exit('111111');
//var_dump($mc);
if($mc->setMcRow($mcKey,rand(1,99999999)))
{
	$xx = $mc->getMcRow($mcKey);
	var_dump($xx);
}
else
{
	echo "false";
}

echo "sendmail";
$flag = utilLib::sendMail("testmail", "test........", "zxsz4084@163.com");
var_dump($flag);
*/
