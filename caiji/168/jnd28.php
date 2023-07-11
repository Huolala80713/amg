<?php

header("Content-type:textml;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
include_once "../../Public/config.php";
require "../jiesuan.php";
require "../yuce.php";
require "../../mergeimg.php";

$typeid = '5';
$api_result = curlFn('https://m.1689410.com/api/LuckTwenty/getPcLucky28.do?issue=&lotCode=10074');
$result=json_decode($api_result,true);
print_r($api_result);exit();
$json = $result['data'][0];
$opencode = $json['opencode'];
$qihao = $json['expect'];
$opentime = strtotime($json['opentime']);
$next_term = $qihao+1;
$nexttime = $opentime + 210;
$topcode = db_query("select `term` from `fn_open` where `type`=$typeid order by `next_time` desc limit 1");
$topcode = db_fetch_array();
// var_dump(randzuhe()[0]);die;
if(!$topcode || $topcode[0] <> $qihao){
    insert_query('fn_open', array('term' => $qihao, 'code' => $opencode, 'time' => date('Y-m-d H:i:s',$opentime), 'type' => $typeid, 'next_term' => $next_term, 'next_time' => date('Y-m-d H:i:s',$nexttime)));
    // jiesuan();
    PC_jiesuan();
    $rowsend=[];
    $rowsend['current_sn']=$qihao;
    $rowsend['next_sn']=$next_term;
    $rowsend['this_time']=$opentime;
    $rowsend['next_time']=$nexttime;
    $rowsend['letf_time']=strtotime($rowsend['next_time'])-GetTtime();
    $rowsend['open_num']=$opencode;
    // doSaveOpenImg($typeid,$rowsend,$gmidAli[$typeid]);

    kaichat($typeid, $next_term,$qihao,$opencode);
    //预测入库
    echo "更新 $code 成功！<br>";
}else{
    echo "等待 $code 刷新<br>";
}