<?php

header("Content-type:textml;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
//while (true){
    include_once getcwd() . "/../Public/config.php";
    include_once getcwd() . "/../Public/config_lhc.php";
//
    caiji();
    lhc_jiesuan(9);
//   // mysqli_close($dbconn);
//}
var_dump("采集");
//六合彩结算 20230711
/*
 * $param $typeid 游戏种类
 */
function lhc_jiesuan($typeid,$term=''){
    $where = [];
    $where['status'] = "未结算";
    $where['type'] = $typeid;
    if($term){
        $where['term'] = $term;
    }else{
        $where['term'] = get_query_val('fn_open_lhc', 'term',['type'=>$typeid] , ['term desc'],'1');
    }
    $cons = [];
    select_query("fn_order", '*', $where,'addtime asc',1);
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    $kj_info = get_query_vals('fn_open_lhc', '*', array('term' => $where['term'],'type'=>$typeid));
    foreach ($cons as $con) {
        //var_dump($con['id']);
        $user_bet_res = lianmaBetIsRight($con,$kj_info);
        //var_dump($user_bet_res);
        if($user_bet_res){
            $user_money = 0;
            if($user_bet_res == 1){
                //正确
                $user_money = round(($con['money'] * $con['peilv']),4);
                insert_query("fn_marklog",
                    array(
                        "userid" => $con['userid'],
                        'type' => '派奖',
                        'game' => 'xglhc',
                        'content' => $term . '期香港六合彩中奖派彩' . $con['content'],
                        'money' => $user_money,
                        'roomid' => $con['roomid'],
                        'addtime' => 'now()'
                    ),$log_id
                );
                if($log_id){
                    update_query('fn_user', array('money' => '+=' . $user_money), array('userid' => $con['userid'], 'roomid' => $con['roomid']));//添加金额
                }
                $user_fuhao = "+";
            }elseif($user_bet_res == 2){//猜错了
                $user_money = -$con['money'];
            }
            //上级返点 yixiao#long#1,2,3,4,5,6,7
            $wanfa_contents = explode('#',$con['mingci']);
            $number_key = $wanfa_contents[1];
            lhctouzhufandian($con['userid'] , $con['money'] , $con['roomid'] , $typeid , $con['userid'] , $con['peilv_step'],$con['bet_wanfa_id'],$number_key);
            update_query('fn_order', array('status' => $user_money,'isfan'=>1), array('id' => $con['id']));//添加金额
        }
    }

}
//next_time_caiji();
//最新开奖时间采集
function next_time_caiji(){
    //$contents = file_get_contents("https://bet.hkjc.com/marksix/single.aspx?lang=ch");
    $url = "https://bet.hkjc.com/marksix/single.aspx?lang=ch";
    //$url = 'http://www.domain.com/test.php?id=123';
    $data = array ('foo' => 'bar');
    $data = http_build_query($data); $opts = array (
        'http' => array ( 'method' => 'POST', 'header'=> "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen($data) . "\r\n", 'content' => $data
        )
    );
    $ctx = stream_context_create($opts);
    $html = @file_get_contents($url,'',$ctx);
    //采集下期期号
    $next_no = preg_match_all("/next_draw = \"(.*)\"/i",$html,$next_no_arr);
    $next_no = str_replace("/",'',$next_no_arr[1][0]);

    $next_date = preg_match_all("/next_draw_date = '(.*)'/i",$html,$next_date_arr);
    $next_date = $next_date_arr[1][0];
    $next_date_arr = explode("/",$next_date);
    $new_date = $next_date_arr[2].'-'.$next_date_arr[1].'-'.$next_date_arr[0] .' 21:30:30';
    if($next_no && $new_date){
        update_query('fn_open_lhc', array('next_time' => $new_date), array('next_term' => $next_no, 'type' => 9));//添加金额
    }


}
function caiji(){
    writeLog("香港开始爬取数据" . time(),'jscc');
    $typeid = '9';
    $url = 'https://bet.hkjc.com/contentserver/jcbw/cmc/last30draw.json';
    $context = stream_context_create(array(
        'http' => array(
            'timeout' => 5 //超时时间，单位为秒
        )
    ));
    $jsondata_str = file_get_contents($url);
    preg_match_all("/\{.*?\}/is",$jsondata_str,$matcher);
    $kj_json_arr = $matcher[0];
    foreach($kj_json_arr as $k=>$v){
        $next_kj = isset($kj_json_arr[$k-1]) ? json_decode($kj_json_arr[$k-1],true) : [];
        $kj = json_decode($v,true);
        $arr = [];
        $arr['term'] = str_replace('/','',$kj['id']);
        $code = str_replace('+',',',$kj['no']);
        $code_arr = explode(',',$code);
        $new_code = [];
        foreach($code_arr as $ck=>$co){
            $new_code[$ck] = str_pad($co,2,'0',STR_PAD_LEFT);
        }
        $arr['code'] = implode(',',$new_code);
        $arr['code_te'] = str_pad($kj['sno'],2,'0',STR_PAD_LEFT);
        $kj_date = explode('/',$kj['date']);
        $arr['time'] = $kj_date[2].'-'.$kj_date[1].'-'.$kj_date[0]." 21:30:00";
        $arr['type'] = $typeid;
        $arr['next_term'] = str_replace('/','',$next_kj['id']) ? str_replace('/','',$next_kj['id']) : intval($arr['term'])+1;
        $kj_date = explode('/',$next_kj['date']);
        $arr['next_time'] = count($kj_date) > 1  ? $kj_date[2].'-'.$kj_date[1].'-'.$kj_date[0]." 21:30:00" : date("Y-m-d 21:30",(time() + 24*60*60));
        $arr['iskaijiang'] = 1;
        $has_open = get_query_val('fn_open_lhc' , 'term' , ['term'=>$arr['term'],'type'=>$typeid]);
        if(!$has_open){
            echo "六合彩第".$arr['term']."期采集成功";
            insert_query('fn_open_lhc',$arr,$kj_id);
            if($kj_id){
                //开奖结算
                lhc_jiesuan($typeid,$arr['term']);
                writeLog("更新 ".$arr['term']." 成功！" . time(),'xglhc');
            }else{
                writeLog("等待 ".$arr['term']." 刷新！" . time(),'xglhc');
            }
            writeLog("xi爬取数据结束" . time(),'xglhc');
            }
    }
}