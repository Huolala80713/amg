<?php
include_once("../Public/config.php");
include_once("../Public/config_lhc.php");

$type = $_GET['type'];
if(!$_SESSION['userid']) exit(json_encode(array("success" => false, "msg" => '请先登录','url'=>'/action.php?do=login')));
if(!$_SESSION['roomid']) exit(json_encode(array("success" => false, "msg" => '请先进房间','url'=>'/action.php?do=roomdoor')));
$userid = $_SESSION['userid'];
$roomid = $_SESSION['roomid'];
$gameTypeID = 9;
switch($type) {
    //获取六合彩相关方法
    case "wanfalist":
        $arr = array();
        $arr['success'] = true;
        $arr['content'] = getWanfaCate();
        $arr['money_list'] = [5, 10, 50, 100, 1000];
        $arr['user'] = get_query_vals('fn_user','money,userid,id',['userid'=>$userid]);
        $arr['game_list'] = getGameList();
        echo json_encode($arr);
        break;
    case "chosewanfa":
        $arr = array();
        $wanfa = $_GET['wanfa'];
        $arr['success'] = true;
        $arr['content'] = getWanfaItem($wanfa, $userid, $roomid);
        $arr['son_list'] = [];
        if ($wanfa == "zhengma_haoma" || $wanfa == "zhengma_shuangmian") {
            $arr['son_list'] = zhengmaCateList();
        }
        if ($wanfa == "zhengma_shengxiao") {
            $arr['son_list'] = shengxiaoCateList();
        }
        $arr['wanfa_type'] = explode("_", $wanfa)[1];
        echo json_encode($arr);
        break;
    case "openHistory":
        $arr = array();
        $arr['success'] = true;
        select_query("fn_open_lhc", '*', "`type` = 9 order by `next_time` desc limit 11");
        while ($con = db_fetch_array()) {
            $cons[] = $con;
        }
        $kj_data = [];
        $kj_info = [];
        if ($cons) {
            foreach ($cons as $k => $v) {
                $kj = $v;
                $kj_number = explode(',', $v['code']);
                array_push($kj_number, $v['code_te']);
                $kj['kj_date'] = substr($kj['time'], 0, 10);
                $kj['code_list'] = kj_number_arr($kj_number);
                $kj_data[] = $kj;
            }
            $kj_info = $kj_data[0];
            $kj_info['next_times'] = strtotime($kj_info['next_time']) - time();
            unset($kj_data[0]);
        }
        $arr['info'] = $kj_info;
        $arr['content'] = $kj_data;
        echo json_encode($arr);
        break;
    case "order_list":
        $arr = array();
        $page = $_GET['page'] ? $_GET['page'] : 1;
        $limit = 15;
        $status = $_GET['status'] ? $_GET['status'] : 'all';
        $date_type = $_GET['date_type'] ? $_GET['date_type'] : 1;
        if(date('H') < 6){
            $date = date('Y-m-d' , strtotime('-1days'));
        }else{
            $date = date('Y-m-d');
        }
        if($date_type == 1){//今天
            $day1 =  $day2 = $date;
        }
        if($date_type == 2){//昨天
            $day1 =  date("Y-m-d",strtotime($date . "- 1day"));
            $day2 =  date("Y-m-d",strtotime($date . "- 1day"));
        }
        if($date_type == 3){//本周
            $day1 =  date('Y-m-d', time()-86400*date('w'));
            $day2 =  $date;
        }
        if($date_type == 4){//上周
            $day1 =  date("Y-m-d",strtotime($date . " -1 weeks - " . (date('w')?(date('w') - 1):7) . " days"));
            $day2 =  date("Y-m-d",strtotime($date . " -1 weeks + " . ( 7 - date('w')) . " days"));
        }
        if($date_type == 5){//本月
            $day1 =  date("Y-m-01" , strtotime($date));
            $day2 =  date("Y-m-d",strtotime(date("Y-m-01" , strtotime($date)) . " + 1month -1day"));
        }
        if($date_type == 6){//上月
            $day1 =  date("Y-m-01",strtotime($date . "- 1 month"));
            $day2 =  date("Y-m-d",strtotime(date("Y-m-01" , strtotime($date)) . "- 1 day"));
        }

//        var_dump($day1);
//        var_dump($day2);
        $arr['success'] = true;
        $arr['content'] = lhcorderlist($userid , $day1 , $day2 , $gameTypeID , $status , $page , $limit);
        echo json_encode($arr);
        break;
    case "order_list_config":
        $arr = array();
        $arr['success'] = true;
        $arr['order_type'] = [
            ['id'=>'all','name'=>'所有'],
            ['id'=>'zj','name'=>'中奖'],
            ['id'=>'wzj','name'=>'未中奖'],
            ['id'=>'dkj','name'=>'待开奖'],
        ];
        $arr['order_date'] = [
            ['id'=>1,'name'=>'今天'],
            ['id'=>2,'name'=>'昨天'],
            ['id'=>3,'name'=>'本周'],
            ['id'=>4,'name'=>'上周'],
            ['id'=>5,'name'=>'本月'],
            ['id'=>6,'name'=>'上月'],
        ];
        echo json_encode($arr);
        break;
    case "bet"://投注
        /*
         * numberList"虎,狗"
         price:100
         wanfaId:"zhengma_shengxiao"
         wanfaSonId:5
         */
        $data = array();
        $data['success'] = true;
        $kj_info = get_query_vals('fn_open_lhc', '*', array('type' => $gameTypeID));//get_query_vals("fn_open_lhc", '*', "`type` = 9");
        $bet_str = file_get_contents('php://input');
        $bet_data = $bet_str ? json_decode($bet_str, true) : [];
        $term = $bet_data['term'];
        $money = $bet_data['price'];
        $number_list = explode(',', $bet_data['numberList']);
        $user_peilv_arr = getUserLhcPeilv($userid, $roomid);
        //var_dump($user_peilv_arr);
        $posi = $bet_data['wanfaSonId'] ? $bet_data['wanfaSonId'] : 7;
        $wanfa = $bet_data['wanfaId'];
        $wanfa_name = getWanfaCate()[$wanfa];//大类
        $wanfa_type = explode('_', $wanfa)[1];
        $son_list = [];
        if ($wanfa == "zhengma_haoma" || $wanfa == "zhengma_shuangmian") {
            $son_list = zhengmaCateList();
        }
        if ($wanfa == "zhengma_shengxiao") {
            $son_list = shengxiaoCateList();
        }
        $wanfa_son_name = isset($son_list[$posi - 1]) ? $son_list[$posi - 1]['name'] : '特码';

        if (get_query_val('fn_lottery' . $gameTypeID, 'gameopen', array('roomid' => $roomid)) == 'false') {
            echo json_encode(array('success' => false, 'msg' => '该游戏已关闭！'));
            die();
        }
        $open_data = get_query_vals('fn_open_lhc', 'next_term,next_time,time,iskaijiang', "type = {$gameTypeID} order by `next_time` desc limit 1");
        $lottery_data = get_query_vals('fn_lottery' . $gameTypeID, '*', array('roomid' => $roomid));
        if (empty($open_data)) {
            $fengpan = true;
        }
        $BetTerm = $open_data['next_term'];
        $time = intval($lottery_data['fengtime']);//(int)get_query_val('fn_lottery' . $gameTypeID, 'fengtime', array('roomid' => $roomid));
        $djs = strtotime($open_data['next_time']) - time() - $time - 2;
        //var_dump($djs);
        if ($djs < 1) {
            $fengpan = true;
        } else {
            $fengpan = false;
        }
        if ($open_data['iskaijiang'] == 0) {
            $fengpan = true;
        }
        if ($fengpan) {
//            echo json_encode(array('success' => false, 'msg' => '等待开奖中，停止投注！'));
//            die();
        }
        //最小金额判断，最大金额判断
        $money_min = $lottery_data[$wanfa_type . '_min'];
        if ($money < $money_min) {
            echo json_encode(array('success' => false, 'msg' => '单注金额不能低于' . $money_min));
            die();
        }
        $money_max = $lottery_data[$wanfa_type . '_max'];
        if ($money > $money_max) {
            echo json_encode(array('success' => false, 'msg' => '单注金额不能高于' . $money_max));
            die();
        }
        $total_money = $money * count($number_list);
        $user_money = (int)get_query_val('fn_user', 'money', array('userid' => $userid));
        if ($user_money < $total_money) {
            $data['success'] = false;
            $data['msg'] = "您的余额不足" . $total_money;
            echo json_encode($data);
            die();
        }
        insert_query('fn_lhc_log',
            [
                'userid' => $userid,
                'wanfa' => $wanfa,
                'contents' => $bet_data['numberList'],
                'money' => $money,
                'posi' => $posi
            ], $log_id);
        $ordertable = "fn_order";
        $user = get_query_vals('fn_user', '*', array('userid' => $userid, 'roomid' => $roomid));
        $insert_list = [];
        foreach ($number_list as $v) {
            $order_arr = [];
            $peilv_key = getPeilvKeyByItemName($wanfa, trim($v));
            $content = $wanfa_name . "#" . $wanfa_son_name . "#" . trim($v);
            // var_dump($wanfa);
            $user_peilv = $user_peilv_arr[$peilv_key];
            $order_arr['peilv'] = $user_peilv;
            $order_arr['peilv_step'] = $user_peilv_arr[$peilv_key . '_step'];
            $order_arr['term'] = $BetTerm;
            $order_arr['userid'] = $userid;
            $order_arr['username'] = $user['username'];
            $order_arr['headimg'] = $user['headimg'];
            $bet_key = getBetKeyByItemName($wanfa, $v);
            $order_arr['mingci'] = $wanfa . "#" . $bet_key . "#" . $posi;
            $order_arr['content'] = $content;
            $order_arr['money'] = $money;
            $order_arr['addtime'] = date("Y-m-d H:i:s");
            $order_arr['roomid'] = $roomid;
            $order_arr['type'] = $gameTypeID;
            $order_arr['jia'] = 'false';
            $order_arr['status'] = "未结算";
            //var_dump($peilv_key.":".$user_peilv_arr[$peilv_key . '_step']);
           // var_dump($order_arr);
            //continue;
            insert_query($ordertable, $order_arr, $order_id);
            //成功注单
            if ($order_id) {
                insert_query("fn_marklog",
                    array(
                        "userid" => $userid,
                        'type' => '投注',
                        'game' => getGameCodeById($gameTypeID),
                        'content' => $content . '/赔率：' . $user_peilv,
                        'money' => $money,
                        'roomid' => $roomid,
                        'addtime' => 'now()'));
//                insert_query("fn_upmark",
//                    array(
//                        "userid" => $userid,
//                        'headimg' => $user['headimg'],
//                        'username' => $user['username'],
//                        'type' => '下分',
//                        'money' => $money,
//                        'content' => $content,
//                        'status' => '已处理',
//                        'time' => 'now()',
//                        'game' => getGameCodeById($gameTypeID),
//                        'roomid' => $roomid,
//                        'jia' => 'false'
//                    )
//                );
                update_query('fn_user', array('money' => '-=' . $money), array('userid' => $userid, 'roomid' => $roomid));
            }


            $insert_list[] = $order_id;
            /**/
        }
        if (!$insert_list) {
            $data['success'] = false;
            $data['msg'] = "投注失败";
        } else {
            $data['msg'] = "投注成功";
        }
        echo json_encode($data);
        break;
}

?>