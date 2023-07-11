<?php

header("Content-type:textml;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
include_once "../Public/config.php";
require "jiesuan.php";
require "../mergeimg.php";

// class caiji{

    //澳洲幸运10    id=1
    // function azxy10(){
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
        $qihao = substr($qihao,-5);
        $opentime = $jsondata["result"]["data"]["preDrawTime"];
        $next_term = $jsondata["result"]["data"]["drawIssue"];
        $next_term = substr($next_term,-5);
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
    // }
    
    //幸运飞艇     id=2
    // function xyft(){
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
        $qihao = substr($qihao,-6);
        $opentime = $jsondata["result"]["data"]["preDrawTime"];
        $next_term = $jsondata["result"]["data"]["drawIssue"];
        $next_term = substr($next_term,-6);
        
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
    // }

    //极速飞艇     id=3
    // function jsft(){
        $typeid = '3';
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
        $qihao = date('Y').substr($qihao,-2);
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
    // }
    
    //极速赛车     id=4
    // function jssc(){
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
        $qihao = date('d').substr($qihao,-3);
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
        

    $typeid = '6';
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
        $openindex = substr($qihao,-6);
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
        
        
    // }
$typeid = '5';
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
        $qihao = date('d').substr($qihao,-3);
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
// }


// $re= new caiji();
// $re->azxy10();
// $re->xyft();
// $re->jsft();
// $re->jssc();



