<?php 
//echo file_get_contents('http://www.1391c.com/xyft/ajax?ajaxhandler=GetXyftAwardData');
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
date_default_timezone_set("Asia/Shanghai");
    function getTimestamp($digits = false) {  
        $digits = $digits > 10 ? $digits : 10;  
        $digits = $digits - 10;
        if ((!$digits) || ($digits == 10))  
        {  
            return time();  
        }  
        else  
        {  
            return number_format(microtime(true),$digits,'','');  
        }  
    }  

$json = file_get_contents('http://api.woaizy.com/chatkj.php');
$json = json_decode($json,1);
$aa = $json['data'][1]['open_result'];
$arr = explode(",",$aa);
$opencode.= (int)$arr[0].",";
$opencode.= (int)$arr[1].",";
$opencode.= (int)$arr[2].",";
$opencode.= (int)$arr[3].",";
$opencode.= (int)$arr[4].",";
$opencode.= (int)$arr[5].",";
$opencode.= (int)$arr[6].",";
$opencode.= (int)$arr[7].",";
$opencode.= (int)$arr[8].",";
$opencode.= (int)$arr[9];
//var_dump($arr);
$IntervalTimeMs=strtotime($json['data'][1]['next_time'])."000";
$IntervalTimeMs = $IntervalTimeMs - getTimestamp(13);
$periodNumber1 = (int)$json['data'][1]['open_phase'];
$periodNumber2 = (int)$json['data'][1]['next_phase'];
echo "
{
	\"time\":".getTimestamp(13).",
	\"current\":{
		\"periodNumber\":" . $periodNumber1 . ",
		\"awardTime\":\"" . $json['data'][1]['open_time'] . "\",
		\"awardNumbers\":\"". $opencode ."\"
	},\"next\":{
		\"periodNumber\":".$periodNumber2.",
		\"awardTime\":\"". $json['data'][1]['next_time'] ."\",
		\"awardTimeInterval\": ". $IntervalTimeMs  .",
		\"delayTimeInterval\":-6
	}
}

";
?>