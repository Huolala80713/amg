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
    case "old_wanfalist":
        $arr = array();
        $arr['success'] = true;
        $arr['content'] = getWanfaCate();
        $arr['money_list'] = [5, 10, 50, 100, 1000];
        $arr['user'] = get_query_vals('fn_user','money,userid,id',['userid'=>$userid]);
        $arr['game_list'] = getGameList();
        echo json_encode($arr);
        break;
    case "wanfalist"://新版
        $arr = array();
        $game_id = 9;
        $arr['success'] = true;
        $arr['content'] = getFirstCateList($game_id);
        $arr['money_list'] = [5, 10, 50, 100, 1000];
        $arr['user'] = get_query_vals('fn_user','money,userid,id',['userid'=>$userid]);
        $arr['game_list'] = getGameList();
        echo json_encode($arr);
        break;
    //获取六合彩相关方法
    case "userinfo":
        $arr = array();
        $arr['success'] = true;
        $arr['content'] = get_query_vals('fn_user','money,userid,id',['userid'=>$userid]);
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
        if ($wanfa == "lianma_haoma") {
            $arr['son_list'] = lianmaCateList();
        }
        if ($wanfa == "zhengma_shengxiao") {
            $arr['son_list'] = shengxiaoCateList();
        }
        $arr['wanfa_type'] = explode("_", $wanfa)[1];
        echo json_encode($arr);
        break;
    case "newchosewanfa":
        $arr = array();
        $wanfa_id = $_GET['wanfa_id'];
        $wanfa_info = get_query_vals('fn_lhc_wanfa','id,name,wanfa_type',['id'=>$wanfa_id]);
        $arr['success'] = true;
        $arr['content'] = getWanfaItemById($wanfa_id, $userid, $roomid);
        $son_list = getWanfaSonListById($wanfa_id, $userid, $roomid);
        $new_son_list = [];
        foreach($son_list as $k=>$v){
            $new_arr = $v;
            $new_arr['son_list'] = getWanfaSonListById($v['id'], $userid, $roomid);
            $new_arr['full'] = 0;
            if(count($new_arr['son_list']) > 0){
                $new_arr['full'] = 1;
            }
            $new_son_list[] = $new_arr;
        }
        $arr['son_list'] = $new_son_list;
        $arr['wanfa_type'] = $wanfa_info['wanfa_type'];
        $arr['wanfa_info'] = $wanfa_info;
        echo json_encode($arr);
        break;
    case "openHistory":
        $arr = array();
        $arr['success'] = true;
        select_query("fn_open_lhc", '*', "`type` = {$gameTypeID} order by `next_time` desc limit 11");
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
    case "info":
        $arr = array();
        $arr['success'] = true;
        //select_query("fn_open_lhc", '*', "`type` = 9 order by `next_time` desc limit 1");
        $kj_info = get_query_vals('fn_open_lhc', '*', "`type` = {$gameTypeID} order by `next_time` desc");
        $kj_number = explode(',', $kj_info['code']);
        array_push($kj_number, $kj_info['code_te']);
        $kj_info['kj_date'] = substr($kj_info['time'], 0, 10);
        $kj_info['code_list'] = kj_number_arr($kj_number);
        $peizhi = get_query_vals('fn_lottery'.$gameTypeID, '*', "roomid={$roomid}");
        //$kj_info['next_times'] = strtotime($kj_info['next_times']) - $peizhi['begin_bet_times'];
        $begin_kaipan = intval($peizhi['begin_bet_times']) - (strtotime($kj_info['next_time']) - time());
        $kj_info['begin_bet_times'] = $begin_kaipan > 0 ? $begin_kaipan : 0;//开奖倒计时
        $kaipan_time_str =  "";
        $kj_info['is_begin_bet'] = 0;
        if($begin_kaipan < 0){
            $kaipan_time_str = "未开盘";
        }
        if($begin_kaipan > 0){
            $djs = intval($peizhi['begin_bet_times']) - $begin_kaipan - intval($peizhi['fengtime']);
            $kaipan_time_str = "";//intval($djs / (3600)).":".intval(($djs % (3600)) / 60) .":".intval($djs %  60);
            $djs_hours = intval($djs / (3600));
            if($djs_hours){
                $kaipan_time_str .= str_pad($djs_hours,2,'0',STR_PAD_LEFT).":";
            }
            $djs_mins = intval(($djs % (3600)) / 60);
            if($djs_mins || $djs_hours){
                $kaipan_time_str .= str_pad($djs_mins,2,'0',STR_PAD_LEFT).":";
            }
            $djs_ses = intval($djs %  60);
            $kaipan_time_str .= str_pad($djs_ses,2,'0',STR_PAD_LEFT);

            if($djs < 0){
                $kaipan_time_str = "已封盘";
            }else{
                $kj_info['is_begin_bet'] = 1;
            }
        }
//        var_dump(intval($peizhi['begin_bet_times']));
//        var_dump(strtotime($kj_info['next_time']) - time());
//        var_dump($begin_kaipan);


        if(time() > strtotime($kj_info['next_time'])){
            $kaipan_time_str = "已开奖";
        }
        //$kj_info['is_begin_bet'] = 1;
        $kj_info['open_status'] = $kaipan_time_str;
        $kj_info['fengtime'] = $peizhi['fengtime'];
        $arr['info'] = $kj_info;
        echo json_encode($arr);
        break;
    case "order_list":
        $arr = array();
        $page = $_GET['page'] ? $_GET['page'] : 1;
        $limit = 300;
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
        $wanfaSonId = $bet_data['wanfaSonId'] ? $bet_data['wanfaSonId'] : '';
        $posi = 7;
        $wanfa = $bet_data['wanfaId'];
        //20230727
        $bet_wanfa_id = $wanfaSonId ? $wanfaSonId : $wanfa;
        $bet_wanfa_info = get_query_vals('fn_lhc_wanfa','*',['id'=>$bet_wanfa_id]);
        $son_wanfa = [];
        $zj_nun = 1;//需要中奖的号码数量
        if($wanfaSonId){
            $number_list = lianma($number_list,$bet_wanfa_info['bet_number']);
            $zj_nun = $bet_wanfa_info['zj_num'];
            $posi = $bet_wanfa_info['bet_posi'];
        }else{
            $number_list = lianma($number_list,1);
        };
        $wanfa_info = get_query_vals('fn_lhc_wanfa','*',['id'=>$wanfa]);
        $wanfa_name = $wanfa_info['name'];//大类
        $wanfa_type = $bet_wanfa_info['wanfa_type'];

        $wanfa_son_info = get_query_vals('fn_lhc_wanfa','*',['id'=>$wanfaSonId]);
        $son_list = getWanfaSonlistByType($wanfa);

        //$wanfa_son_name = isset($son_list[$posi - 1]) ? $son_list[$posi - 1]['name'] : '特码';
        $wanfa_son_name = isset($wanfa_son_info['name']) ? $wanfa_son_info['name'] : '特码';

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
       // $fengpan = false;
        if ($fengpan) {
            echo json_encode(array('success' => false, 'msg' => '等待开奖中，停止投注！'));
            exit;
        }
        //判断投注是否开始
        $begin_times = $lottery_data['begin_bet_times'];
        $is_begin = strtotime($open_data['next_time']) - time() > $begin_times;

        $hours = ceil(((21.5 * 3600) - $begin_times) / 3600);
        $mins = intval(intval(((21.5 * 3600) - $begin_times) % 3600)/60);
        $mins = $mins > 0 ? 60 -  $mins : 0;
        $kaipan_date = substr($open_data['next_time'],0,10) . " ".str_pad($hours,2,'0',STR_PAD_LEFT).":".str_pad($mins,2,'0',STR_PAD_LEFT);
        if($is_begin){
            echo json_encode(array('success' => false, 'msg' => '游戏还未开盘！开盘时间为：' . $kaipan_date));
            exit;
        }
        //最小金额判断，最大金额判断
        $money_min = $bet_wanfa_info['bet_price_min'];
        if ($money < $money_min) {
            echo json_encode(array('success' => false, 'msg' => '单注金额不能低于' . $money_min));
            exit;
        }
        $money_max = $bet_wanfa_info['bet_price_max'];
        if ($money > $money_max) {
            echo json_encode(array('success' => false, 'msg' => '单注金额不能高于' . $money_max));
            exit;
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
            $bet_key = 0;//投注号码的key值，如果为多号码一组，则为0
            if(count($v) == 1){
                $bet_key = getBetNumberKeyByType($bet_wanfa_info['wanfa_type'], $v[0]);
            }
            $content = $wanfa_name . "#" . $wanfa_son_name . "#" . trim(implode(",",$v));
            // var_dump($wanfa);
            //var_dump($content);
            //$user_peilv = isset($user_peilv_arr[$peilv_key]) ? $user_peilv_arr[$peilv_key] : 1;
            //var_dump($bet_key);
            $user_peilv = getUserPeilvByWanfaId($bet_wanfa_id,$userid,$roomid,$bet_key);
            $order_arr['peilv'] = $user_peilv;
            $order_arr['peilv_step'] = $bet_wanfa_info['peilv_step'];
            $order_arr['term'] = $BetTerm;
            $order_arr['userid'] = $userid;
            $order_arr['username'] = $user['username'];
            $order_arr['headimg'] = $user['headimg'];
            $wanfa_key = $bet_wanfa_info['wanfa_key'];
            //$bet_key = getBetKeyByItemName($wanfa, $v);
            $order_arr['mingci'] = $wanfa_key . "#" . $bet_key . "#" . $posi."#".$zj_nun;

            //var_dump($order_arr['mingci']);
            $order_arr['content'] = $content;
            $order_arr['money'] = $money;
            $order_arr['addtime'] = date("Y-m-d H:i:s");
            $order_arr['roomid'] = $roomid;
            $order_arr['type'] = $gameTypeID;
            $order_arr['jia'] = 'false';
            $order_arr['status'] = "未结算";
            $order_arr['bet_wanfa_id'] = $bet_wanfa_id;
            //var_dump($peilv_key.":".$user_peilv_arr[$peilv_key . '_step']);
//            var_dump($order_arr);
//            continue;
            if(!$user_peilv){
                continue;
            }
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
    case "kj":
        $order_id = $_GET['order_id'];
        $order_info  = get_query_vals('fn_order', '*', array('id' => $order_id));
        $kj_info = get_query_vals('fn_open_lhc', '*', array('term' => $order_info['term']));
        $res = lianmaBetIsRight($order_info,$kj_info);
        var_dump($res);
        break;
    case "bet_info":
        $data = [];
        $data['success'] = true;
        $data['msg'] = "投注信息";
        $wanfa_id = $_GET['wanfa_id'];
        $numbers = $_GET['numbers'];
        $wanfa_son_id = $_GET['wanfa_son_id'];
        $bet_wanfa_id = $wanfa_son_id ? $wanfa_son_id : $wanfa_id;
        $wanfa_info = get_query_vals('fn_lhc_wanfa','*',['id'=>$bet_wanfa_id]);
        $numbers_arr = explode(',',$numbers);
        $numbers_arr = array_filter($numbers_arr);
        //var_dump($numbers_arr);
        //$numbers_arr = ['03','04','05'];
        //var_dump($wanfa_info);
        if(count($numbers_arr) > $wanfa_info['number_max_num']){
            $data['msg'] = "选好号码最大数量为：".$wanfa_info['number_max_num'];
            $data['success'] = false;
            echo json_encode($data);
            exit;
        }
        $numbers_list = lianma($numbers_arr,$wanfa_info['bet_number']);
        //var_dump($numbers_list);
        $data['bet_count'] = count($numbers_list);
//        if(!count($numbers_list)){
//            $data['success'] = false;
//            $data['msg'] = "组合不足";
//        }
        echo json_encode($data);
        break;



}

?>