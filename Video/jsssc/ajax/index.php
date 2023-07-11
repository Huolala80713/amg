<?php	
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
date_default_timezone_set("Asia/Shanghai");
$url = "http://api.woaizy.com/chatkj.php";
$text = file_get_contents($url);
$json = json_decode($text,true);

$code = $json['data'][8]['open_result'];
$code = explode(',',$code);
$codes = $code[0].$code[1].$code[2].$code[3].$code[4];

$term = $json['data'][8]['open_phase'];
$time = date('H:i:s',strtotime($json['data'][8]['next_time']));
$count = strtotime($json['data'][8]['next_time']) - time();
$sumNum = (int)$code[0] + (int)$code[1] + (int)$code[2] + (int)$code[3] + (int)$code[4];
$ds = $sumNum % 2 != 0 ? '单' : '双';
$dx = $sumNum > 22 ? '大':'小';
if($code[0] > $code[4]){
	$lh = '龙';
}elseif($code[0] < $code[4]){
	$lh = '虎';
}elseif($code[0] == $code[4]){
	$lh = '和';
}

echo json_encode(array('code'=>$codes,'term'=>$term,'nexttime'=>$time,'count'=>$count,'sumNum'=>$sumNum,'hedx'=>$dx,'heds'=>$ds,'lh'=>$lh));
exit;

?>