<?php

header("Content-type:textml;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
while (true){
    include_once getcwd() . "/Public/config.php";
    require_once "jiesuan.php";
    require_once getcwd() . "/mergeimg.php";
    caiji();
    mysqli_close($dbconn);
}
function caiji(){
    writeLog("极速赛车开始爬取数据" . date("Y-m-d H:i:s"),'jscc');
    $typeid = '4';
    $url = 'https://api.api68.com/pks/getLotteryPksInfo.do?lotCode=10037';
    $context = stream_context_create(array(
        'http' => array(
            'timeout' => 5 //超时时间，单位为秒
        )
    ));
    $jsondata = file_get_contents($url , 0 , $context);
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
    $nexttime = strtotime($jsondata["result"]["data"]["drawTime"]);//开奖时间
    $topcode = db_query("select `term` from `fn_open` where `type`=$typeid order by `next_time` desc limit 1");
    $topcode = db_fetch_array();
    if($qihao){
        writeLog("数据采集 $qihao 成功！" . date("Y-m-d H:i:s"),'jscc');
    }

    if((!$topcode || $topcode[0] <> $qihao) && $qihao){
        insert_query('fn_open', array('term' => $qihao, 'code' => $opencode, 'time' => date('Y-m-d H:i:s',strtotime($opentime)), 'type' => $typeid, 'next_term' => $next_term, 'next_time' => date('Y-m-d H:i:s',$nexttime)));
//    jiesuan($typeid , $qihao);
//    $rowsend=[];
//    $rowsend['current_sn']=$qihao;
//    $rowsend['next_sn']=$next_term;
//    $rowsend['this_time']=$opentime;
//    $rowsend['next_time']=$nexttime;
//    $rowsend['letf_time']=strtotime($rowsend['next_time'])-GetTtime();
//    $rowsend['open_num']=$opencode;
//    doSaveOpenImg($typeid,$rowsend,$gmidAli[$typeid]);
//    $history_list = [];
//    select_query('fn_open' , '*' , "term <= '{$qihao}' and `type` = {$typeid}" , ['time desc'],'10');
//    while($con = db_fetch_array()){
//        $history_list[] = $con;
//    }
//    doSaveOpenHistoryImg($typeid ,$history_list[0] ,  $history_list ,$gmidAli[$typeid]);
//    kaichat($typeid, $next_term,$qihao,$opencode);
        writeLog("更新 $qihao 成功！" . date("Y-m-d H:i:s"),'jscc');
    }else{
        writeLog("等待 $next_term 刷新！" . date("Y-m-d H:i:s"),'jscc');
    }
    writeLog("极速赛车爬取数据结束" . date("Y-m-d H:i:s"),'jscc');
}