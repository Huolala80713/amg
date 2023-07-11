<?php
$load = 5;
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
include_once "../Public/config.php";
require "jiesuan.php";
require "../mergeimg.php";
if($_GET['t'] == 'test'){
    SSC_jiesuan();
    exit;
}

$xcode=[
    ['code'=>'1110','id'=>'4','time'=>75-20],
    ['code'=>'1120','id'=>'2','time'=>300-50],
    ['code'=>'1130','id'=>'1','time'=>300-50],
    ['code'=>'1140','id'=>'3','time'=>75-20],
];
foreach ($xcode as $rvs){
    $url = "http://43.226.149.52/?lotCode=".$rvs['code'];
    $jsondata = getOpenInfoCurl($url);
    $opencode = $jsondata['preDrawCode'];
    $qihao = substr($jsondata['preDrawIssue'],-6);
    $opentime = strtotime($jsondata['preDrawTime']);
    $openindex = substr($qihao,-6);
    $next_term = intval($qihao)+1;
    $nexttime = $opentime+$rvs['time'];
    $typeid = $rvs['id'];
    $topcode = db_query("select `term` from `fn_open` where `type`=$typeid order by `next_time` desc limit 1");
    $topcode = db_fetch_array();
    if(!$topcode || $topcode[0] <> $qihao){
        insert_query('fn_open', array('term' => $qihao, 'code' => $opencode, 'time' => date('Y-m-d H:i:s',$opentime), 'type' => $typeid, 'next_term' => $next_term, 'next_time' => date('Y-m-d H:i:s',$nexttime)));
        jiesuan();
        $rowsend=[];
        $rowsend['current_sn']=$qihao;
        $rowsend['next_sn']=$next_term;
        $rowsend['this_time']=$opentime;
        $rowsend['next_time']=$nexttime;
        $rowsend['letf_time']=strtotime($rowsend['next_time'])-GetTtime();
        $rowsend['open_num']=$opencode;
        doSaveOpenImg($typeid,$rowsend,$gmidAli[$typeid]);

        kaichat($typeid, $next_term,$qihao);
        echo "更新 $code 成功！<br>";
    }else{
        echo "等待 $code 刷新<br>";
    }
}
