<?php
$load = 5;
header("Content-type:textml;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
include_once "../Public/config.php";
require "jiesuan.php";
require "../mergeimg.php";//图片合成
if($_GET['t'] == 'test'){
     SSC_jiesuan();
     exit;
 }



$xcode=[
     ['code'=>'1110','id'=>'4','time'=>75-20],//极速赛车
     ['code'=>'1120','id'=>'2','time'=>300-50],//官方幸运飞艇
     ['code'=>'1130','id'=>'1','time'=>300-50],//澳洲幸运10  
     ['code'=>'1140','id'=>'3','time'=>60-5],//极速飞艇
];


$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10");
        shuffle($array);
        $opennum = "";
        for($i=0; $i<10; $i++){
           $opennum .= $array[$i];
           if($i!=9)
        	   $opennum .= ",";
        }
        $opencode = $opennum;
        $qihao = intval(time() / 60) ;
        $qihao = substr($qihao,-6);
        $opentime = time();
        // $openindex = substr($qihao,-6);
        $next_term = intval($qihao)+1;
        $nexttime = $opentime + (60-1);//开奖时间
        $typeid = '3';
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
        
        $typeid = '4';
        $array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10");
        shuffle($array);
        $opennum = "";
        for($i=0; $i<10; $i++){
          $opennum .= $array[$i];
          if($i!=9)
        	   $opennum .= ",";
        }
        $opencode = $opennum;
        $qihao = intval(time() / 60) ;
        $qihao = date('Ym').substr($qihao,-3);
        $opentime = time();
        $next_term = intval($qihao)+1;
        $nexttime = $opentime + (60-5);//开奖时间
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
        
        $typeid = '1';
        $url = 'https://api.api68.com/pks/getLotteryPksInfo.do?lotCode=10012';
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
        $opencode = $arr[0].','.$arr[1].','.$arr[2].','.$arr[3].','.$arr[4].','.$arr[5].','.$arr[6].','.$arr[7].','.$arr[8].','.$arr[9];
        
        $qihao = $jsondata["result"]["data"]["preDrawIssue"];
        $opentime = $jsondata["result"]["data"]["preDrawTime"];
        $next_term = $jsondata["result"]["data"]["drawIssue"];
        $nexttime = strtotime($opentime) + (300-30);//开奖时间
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
    
    
    
    $typeid = '2';
        $url = 'https://api.api68.com/pks/getLotteryPksInfo.do?lotCode=10057';
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
        $opencode = $arr[0].','.$arr[1].','.$arr[2].','.$arr[3].','.$arr[4].','.$arr[5].','.$arr[6].','.$arr[7].','.$arr[8].','.$arr[9];

        $qihao = $jsondata["result"]["data"]["preDrawIssue"];
        $opentime = $jsondata["result"]["data"]["preDrawTime"];
        $next_term = $jsondata["result"]["data"]["drawIssue"];
        $nexttime = strtotime($opentime) + (300-30);//开奖时间
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
        
        