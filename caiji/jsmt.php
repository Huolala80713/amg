<?php
header("Content-type:textml;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
include_once "../Public/config.php";
require "jiesuan.php";
require "../mergeimg.php";
$typeid = '6';

die('游戏关闭');

$url = 'https://api.api68.com/pks/getLotteryPksInfo.do?lotCode=10035';
$jsondata = file_get_contents($url);
$jsondata = json_decode($jsondata,true);
$opencode = $jsondata["result"]["data"]["preDrawCode"];
$opencode = explode(',',$opencode);

foreach($opencode as $k=>$v){
    if($v=='01'){
        $arr[$k] = '1';
    }elseif($v=='02'){
        $arr[$k] = '2';
    }elseif($v=='03'){
        $arr[$k] = '3';
    }elseif($v=='04'){
        $arr[$k] = '4';
    }elseif($v=='05'){
        $arr[$k] = '5';
    }elseif($v=='06'){
        $arr[$k] = '6';
    }elseif($v=='07'){
        $arr[$k] = '7';
    }elseif($v=='08'){
        $arr[$k] = '8';
    }elseif($v=='09'){
        $arr[$k] = '9';
    }elseif($v=='10'){
        $arr[$k] = '10';
    }
}
ksort($arr);
$opencode = implode(',' , $arr);

$qihao = $jsondata["result"]["data"]["preDrawIssue"];
$opentime = $jsondata["result"]["data"]["preDrawTime"];
$next_term = $jsondata["result"]["data"]["drawIssue"];

$nexttime = strtotime($opentime) + (60-5);//开奖时间
$topcode = db_query("select `term` from `fn_open` where `type`=$typeid order by `next_time` desc limit 1");
$topcode = db_fetch_array();
if(!$topcode || $topcode[0] <> $qihao){
    insert_query('fn_open', array('term' => $qihao, 'code' => $opencode, 'time' => date('Y-m-d H:i:s',strtotime($opentime)), 'type' => $typeid, 'next_term' => $next_term, 'next_time' => date('Y-m-d H:i:s',$nexttime)));
    jiesuan();
    $rowsend=[];
    $rowsend['current_sn']=$qihao;
    $rowsend['next_sn']=$next_term;
    $rowsend['this_time']=$opentime;
    $rowsend['next_time']=$nexttime;
    $rowsend['letf_time']=strtotime($rowsend['next_time'])-GetTtime();
    $rowsend['open_num']=$opencode;
    doSaveOpenImg($typeid,$rowsend,$gmidAli[$typeid]);
    $history_list = [];
    select_query('fn_open' , '*' , "term <= '{$qihao}' and `type` = {$typeid}" , ['time desc'],'10');
    while($con = db_fetch_array()){
        $history_list[] = $con;
    }
    doSaveOpenHistoryImg($typeid ,$history_list[0] ,  $history_list ,$gmidAli[$typeid]);
    kaichat($typeid, $next_term,$qihao,$opencode);
    echo "更新 $qihao 成功！<br>";
}else{
    echo "等待 $next_term 刷新<br>";
}


die();





$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10");
shuffle($array);
$opennum = "";
for($i=0; $i<10; $i++){
    $opennum .= $array[$i];
    if($i!=9)
        $opennum .= ",";
}
$opencode = $opennum;
$qihao = intval((time() - strtotime(date('Y-m-d'))) / 60) ;
$qihao = date('ymd').str_pad($qihao + 1,4,"0",STR_PAD_LEFT);
$opentime = time();
$next_term = intval($qihao)+1;
$nexttime = $opentime + (60-5);//开奖时间
$topcode = db_query("select `term` from `fn_open` where `type`=$typeid order by `next_time` desc limit 1");
$topcode = db_fetch_array();
if(!$topcode || $topcode[0] <> $qihao){
    insert_query('fn_open', array('term' => $qihao, 'code' => $opencode, 'time' => date('Y-m-d H:i:s',$opentime), 'type' => $typeid, 'next_term' => $next_term, 'next_time' => date('Y-m-d H:i:s',$nexttime)));
    jiesuan();
    bukai($typeid , $qihao , $nexttime);
    $rowsend=[];
    $rowsend['current_sn']=$qihao;
    $rowsend['next_sn']=$next_term;
    $rowsend['this_time']=$opentime;
    $rowsend['next_time']=$nexttime;
    $rowsend['letf_time']=strtotime($rowsend['next_time'])-GetTtime();
    $rowsend['open_num']=$opencode;
    doSaveOpenImg($typeid,$rowsend,$gmidAli[$typeid]);
    $history_list = [];
    select_query('fn_open' , '*' , "term <= '{$qihao}' and `type` = {$typeid}" , ['time desc'],'10');
    while($con = db_fetch_array()){
        $history_list[] = $con;
    }
    doSaveOpenHistoryImg($typeid ,$history_list[0] ,  $history_list ,$gmidAli[$typeid]);
    kaichat($typeid, $next_term,$qihao,$opencode);
    echo "更新 $qihao 成功！<br>";
}else{
    echo "等待 $next_term 刷新<br>";
}