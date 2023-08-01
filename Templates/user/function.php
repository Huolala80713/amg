<?php
/**
 * @desc 统一验证是否登录
 */
checkLogin();
function getPageList($datacount , $pagecount = '' , $url = ''){
    require "../lib/Page.php";
    $Page = new Page($datacount , $pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    $Page->setConfig('header','<span class="rows">共 %TOTAL_ROW% 条记录</span>');
    $Page->setConfig('prev','<<');
    $Page->setConfig('next','>>');
    $Page->setConfig('first','首页');
    $Page->setConfig('last','末页');
    $Page->setConfig('home_page',true);
//        $Page->setConfig('theme','%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
    $Page->setConfig('theme','%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%');
    $Page->lastSuffix = FALSE;
    $Page->rollPage = 4;
    $Page->url = $url;
    $Page->pageclass = 'pagelist';
    return $Page->show();
}
function user_upmarklog_list($userid , $day1 , $day2 , $log_type , $page = 1 , $limit = 10){
    $day1 = $day1 . ' 06:00:00';
    $day2 = date('Y-m-d 05:59:59' , strtotime($day2 . '+ 1day'));
    $log_type_name = '';
    switch($log_type){
        case 'recharge':
            $log_type_name = '上分';
            $table = 'fn_upmark';
            $sql = "roomid = '{$_SESSION['roomid']}' and type = '上分' and userid = '{$userid}' and time >= '" . $day1 . "' and time <= '" . $day2 . "'";
            $order = ['time desc','id desc'];
            break;
        case 'finance':
            $log_type_name = '下分';
            $table = 'fn_upmark';
            $sql = "roomid = '{$_SESSION['roomid']}' and type = '下分'  and userid = '{$userid}' and time >= '" . $day1 . "' and time <= '" . $day2 . "'";
            $order = ['time desc','id desc'];
            break;

        default:
            $table = 'fn_upmark';
            $sql = "roomid = '{$_SESSION['roomid']}' and money > 0 and userid = '{$userid}' and time >= '" . $day1 . "' and time <= '" . $day2 . "'";
            $order = ['time desc','id desc'];
            break;
    }
    select_query($table, '*', $sql , $order , (($page - 1) * $limit) . ',' . $limit);
    while ($list = db_fetch_array()) {
        $lists[] = $list;
    }
    $count = get_query_val($table , 'count(id)' , $sql);
    $log_list = [];
    foreach ($lists as $key => $item) {
        $log_list[$key] = [
            'username' => $item['username'],
            'log_type' => $log_type_name,
            'content' => isset($item['content'])?$item['content']:$item['type'],
            'money' => $item['money'],
            'id' => $item['id']
        ];
        $log_list[$key]['status_text'] = $item['status'];
        $log_list[$key]['addtime'] = date('Y-m-d H:i' , strtotime($item['time']));
    }
    return ['list'=>$log_list,'count'=>$count];
}
function user_log_list($userid , $day1 , $day2 , $log_type , $game = '' , $page = 1 , $limit = 10){
    $day1 = $day1 . ' 06:00:00';
    $day2 = date('Y-m-d 05:59:59' , strtotime($day2 . '+ 1day'));
    $log_type_name = '';
    $table = 'fn_marklog';
    $sql = "roomid = '{$_SESSION['roomid']}'  and addtime >= '" . $day1 . "' and addtime <= '" . $day2 . "'";
    $order = ['addtime desc','id desc'];
    switch($log_type){
        case 'recharge':
            $log_type_name = '上分';
            $sql .= " and type = '{$log_type_name}'";
            break;
        case 'finance':
            $log_type_name = '下分';
            $sql .= " and type = '{$log_type_name}'";
            break;
        case 'huishui':
            $log_type_name = '回水';
            $sql .= " and type = '{$log_type_name}'";
            break;
        case 'touzhu':
            $log_type_name = '投注';
            $sql .= " and type = '{$log_type_name}'";
            break;
        case 'paijiang':
            $log_type_name = '派奖';
            $sql .= " and type = '{$log_type_name}'";
            break;
        case 'chedan':
            $log_type_name = '撤单退还';
            $sql .= " and type = '{$log_type_name}'";
            break;
        case 'fandian':
            $log_type_name = '返点';
            $sql .= " and type = '{$log_type_name}'";
            break;
        case 'huodong':
            $log_type_name = '活动';
            $sql .= " and type = '{$log_type_name}'";
            break;
        default:
            break;
    }
    if($game){
        $sql .= ' and game="' . getGameCodeById($game) . '"';
    }
    if(is_array($userid)){
        $sql .= " and userid in ('" . implode("','" , $userid) . "') ";
    }else{
        $sql .= " and userid = '{$userid}' ";
    }
    select_query($table, '*', $sql , $order , (($page - 1) * $limit) . ',' . $limit);
    $log_list = [];
    while ($item = db_fetch_array()) {
        $log_list[] = [
            'userid' => $item['userid'],
            'username' => $item['userid'],
            'game' => $item['game'],
            'log_type' => ($item['type'] == '返点' || $item['type'] == '投注')?$item['content']:$item['type'],
            'content' => isset($item['content'])?$item['content']:$item['type'],
            'money' => $item['money'],
            'id' => $item['id'],
            'status_text' => $item['status'],
            'addtime' => date('Y-m-d H:i' , strtotime($item['addtime']))
        ];
    }
    $count = get_query_val($table , 'count(id)' , $sql);
    return ['list'=>$log_list,'count'=>$count];
}
function recharge($username, $userid, $money)
{
    $id = '';
    $jia = get_query_val('fn_user', 'jia', array('userid' => $userid));
    $isfirst = 0;
    if(!get_query_val('fn_upmark', 'count(id)', array('roomid' => $_SESSION['roomid'], 'userid' => $userid))){
        $isfirst = 1;
    }
    insert_query("fn_upmark", array("userid" => $userid, "isfirst" => $isfirst, 'headimg' => $_SESSION['headimg'], 'username' => $username, 'type' => '上分', 'money' => $money, 'status' => '未处理', 'time' => 'now()', 'game' => $_COOKIE['game'], 'roomid' => $_SESSION['roomid'], 'jia' => $jia) , $id);
    return $id;
}
function withdrawal($username, $userid, $money){
    $m = (int)get_query_val('fn_user', 'money', array('roomid' => $_SESSION['roomid'], 'userid' => $userid));
    if (($m - (int)$money) < 0) {
        return -1;
    }
    $id = '';
    $jia = get_query_val('fn_user', 'jia', array('userid' => $userid));
    insert_query("fn_upmark", array("userid" => $userid, 'headimg' => $_SESSION['headimg'], 'username' => $username, 'type' => '下分', 'money' => $money, 'status' => '未处理', 'time' => 'now()', 'game' => $_COOKIE['game'], 'roomid' => $_SESSION['roomid'], 'jia' => $jia));
    if (get_query_val("fn_setting", "setting_downmark", array("roomid" => $_SESSION['roomid'])) == 'true') {
        update_query('fn_user', array('money' => '-=' . $money), array('userid' => $userid, 'roomid' => $_SESSION['roomid']));
        insert_query("fn_marklog", array("roomid" => $_SESSION['roomid'], 'userid' => $userid, 'type' => '下分', 'content' => '系统自动同意下分' . $money, 'money' => $money, 'addtime' => 'now()'));
        $headimg = get_query_val('fn_setting', 'setting_sysimg', array('roomid' => $_SESSION['roomid']));
        $id = insert_query("fn_chat", array("userid" => "system", "username" => "管理员", "game" => $_COOKIE['game'], 'headimg' => $headimg, 'content' => "@{$username}, 您的下分请求已接收,请稍后查账!", 'addtime' => date('H:i:s'), 'type' => 'S1', 'roomid' => $_SESSION['roomid']) , $id);
    }
    return $id;
}
function getinfo($userid){
    $time = array();
    $time[0] = date('Y-m-d 06:00:00' , strtotime($time[0]));
    $time[1] = date('Y-m-d 05:59:59' , strtotime($time[1] . ' + 1day'));
    $sf = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and type = '上分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");
    $xf = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and type = '下分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");
    $allm = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0) and status !='未结算' and status != '已撤单'");
    $allz = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['roomid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and status !='未结算' and status != '已撤单' and userid = '{$userid}'");
    $yk = $allz - $allm;
    $yk = round($yk, 2);
    return array("yk" => $yk, 'liu' => $allm);
}
function getextend($userid, $time){
    $liushui = 0;
    $yk = 0;
    $money = 0;
    $sf = 0;
    $time = explode(' - ', $time);
    if(count($time) == 2){
        $time[0] = date('Y-m-d 06:00:00' , strtotime($time[0]));
        $time[1] = date('Y-m-d 05:59:59' , strtotime($time[1] . ' + 1day'));
        $ordersql = " and (`addtime` between '{$time[0]}' and '{$time[1]}')";
        $marksql = " and (`time` between '{$time[0]}' and '{$time[1]}')";
    }else{
        $ordersql = '';
        $marksql = '';
    }
    select_query("fn_user", '*', "roomid = {$_SESSION['roomid']} and agent = '{$userid}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $l = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
        $z = get_query_val('fn_order', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
        $y = $z - $l;
        $s = get_query_val('fn_upmark', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` = '已处理'" . $marksql);
        $liushui += ($l);
        $yk += ($y);
        $money += $con['money'];
        $sf += $s;
    }
    $arr = array('liu' => $liushui, 'yk' => sprintf("%.2f", substr(sprintf("%.3f", $yk), 0, -2)), 'money' => $money, 'pay' => $sf);
    return $arr;
}
function shouchong($userid , $day1 , $day2 , $page = 1 , $limit = 10){

    $day1 = date('Y-m-d 06:00:00' , strtotime($day1));
    $day2 = date('Y-m-d 05:59:59' , strtotime($day2 . ' + 1day'));

    $ordertable = 'fn_upmark';
    $ordersql = "roomid = {$_SESSION['roomid']} and `status` = '已处理'  and `isfirst` = 1 and (`time` between '{$day1}' and '{$day2}') and userid in ('" . implode("','" , $userid) . "') ";
    $sql = 'select money , userid , username,`time` from ' . $ordertable . ' where ' . $ordersql . ' limit ' . (($page - 1) * $limit) . ',' . $limit;
    full_query($sql);
    $list = [];
    while ($row = db_fetch_array()) {
        $list[] = $row;
    }
    $sql = 'select id from ' . $ordertable . ' where ' . $ordersql;
    $result = full_query($sql);
    return ['list'=>$list,'count'=>$result->num_rows];
}
function xiajibaobiao($user_id, $day1 , $day2 , $page = 1 , $limit = 10){
    $user_ids = getAgentUserList($user_id , 1);

    $day1 = date('Y-m-d 06:00:00' , strtotime($day1));
    $day2_ = date('Y-m-d 05:59:59' , strtotime($day2 . ' + 1day'));

    $sql = "roomid = {$_SESSION['roomid']} and userid in ('" . implode("','" , $user_ids) . "')";
    select_query('fn_user', '*', $sql , 'aztime desc' , (($page - 1) * $limit) . ',' . $limit);
    $list = [];
    $ordersql = " and (`addtime` between '{$day1}' and '{$day2_}')";
    while ($user = db_fetch_array()) {
        $list[] = $user;
    }
    foreach ($list as $key => &$user){
        $child_user_list = getAgentUserList($user['userid'] , 2);
        $child_user_list[] = $user['userid'];
        $user['child_user_count'] = count($child_user_list);
        $l = (double)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid in ('" . implode("','" , $child_user_list) . "') and status !='未结算' and status != '已撤单' and (`status` > 0 or `status` < 0)" . $ordersql);
        $z = (double)get_query_val('fn_order', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid in ('" . implode("','" , $child_user_list) . "') and status !='未结算' and status != '已撤单' and `status` > 0" . $ordersql);
        $user['touzhu'] = $l;
        $user['paijiang'] = $z;
        $user['yk'] = $z - $l;
    }
    $count = get_query_val('fn_user' , 'count(id)' , $sql);
    return ['list'=>$list,'count'=>$count];
}
function zhuchelist($userid , $day1 , $day2 , $page = 1 , $limit = 10){

    $day1 = date('Y-m-d 06:00:00' , strtotime($day1));
    $day2_ = date('Y-m-d 05:59:59' , strtotime($day2 . ' + 1day'));

    $sql = "roomid = {$_SESSION['roomid']} and userid in ('" . implode("','" , $userid) . "') and aztime between '{$day1}' and '{$day2_}'";
    select_query('fn_user', '*', $sql , 'aztime desc' , (($page - 1) * $limit) . ',' . $limit);
    $list = [];
    while ($user = db_fetch_array()) {
        $list[] = $user;
    }
    $count = get_query_val('fn_user' , 'count(id)' , $sql);
    return ['list'=>$list,'count'=>$count];
}
function tuanduilist($userid , $page = 1 , $limit = 10){
    $sql = "roomid = {$_SESSION['roomid']} and userid in ('" . implode("','" , $userid) . "')";
    select_query('fn_user', '*', $sql , 'aztime desc' , (($page - 1) * $limit) . ',' . $limit);
    $list = [];
    while ($user = db_fetch_array()) {
        $list[] = $user;
    }
    $count = get_query_val('fn_user' , 'count(id)' , $sql);
    return ['list'=>$list,'count'=>$count];
}
function touzhurenshu($userid , $day1 , $day2 , $page = 1 , $limit = 10){

    $day1 = date('Y-m-d 06:00:00' , strtotime($day1));
    $day2 = date('Y-m-d 05:59:59' , strtotime($day2 . ' + 1day'));

    $ordertable = 'fn_order';
    $ordersql = "roomid = {$_SESSION['roomid']} and (`status` > 0 or `status` < 0)  and (`addtime` between '{$day1}' and '{$day2}') and userid in ('" . implode("','" , $userid) . "') ";
    $sql = 'select sum(money) as money , userid , username from ' . $ordertable . ' where ' . $ordersql . ' group by userid limit ' . (($page - 1) * $limit) . ',' . $limit;
    full_query($sql);
    $list = [];
    while ($order = db_fetch_array()) {
        $list[] = $order;
    }
    $sql = 'select userid from ' . $ordertable . ' where ' . $ordersql . ' group by userid';
    $result = full_query($sql);
    return ['list'=>$list,'count'=>$result->num_rows];
}
function orderlist($userid , $day1 , $day2 , $gamename , $jiesuan = '' , $page = 1 , $limit = 10){

    $day1 = date('Y-m-d 06:00:00' , strtotime($day1));
    $day2 = date('Y-m-d 05:59:59' , strtotime($day2 . ' + 1day'));

    $ordersql = " and (`addtime` between '{$day1}' and '{$day2}')";
    if($gamename){
        $ordersql .= " and type = '" . $gamename . "'";
    }
    $ordertable = 'fn_order';
    $order = ['addtime desc'];
    if($jiesuan == 'zj'){
        $sql = "roomid = {$_SESSION['roomid']} and `status` > 0 and status != '未结算'  and status != '已撤单' " . $ordersql ;
    }elseif($jiesuan == 'wzj'){
        $sql = "roomid = {$_SESSION['roomid']} and `status` < 0 and status != '未结算'  and status != '已撤单' " . $ordersql ;
    }elseif($jiesuan == 'dkj' || $jiesuan === false){
        $sql = "roomid = {$_SESSION['roomid']} and `status` = '未结算' " . $ordersql ;
    }elseif($jiesuan && $jiesuan != 'all'){
        $sql = "roomid = {$_SESSION['roomid']} and (`status` > 0 or `status` < 0) and status != '未结算'  and status != '已撤单' " . $ordersql ;
    }else{
        $sql = "roomid = {$_SESSION['roomid']} " . $ordersql ;
    }
    if(is_array($userid)){
        $sql .= " and userid in ('" . implode("','" , $userid) . "') ";
    }else{
        $sql .= " and userid = '{$userid}' ";
    }

    select_query($ordertable, '*', $sql , $order , (($page - 1) * $limit) . ',' . $limit);
    $order_list = $cons = [];
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    foreach ($cons as $order) {
        $peilv = $order['peilv'];
//        $peilv = getPeilv($order['userid'] , $order['roomid'] , $order['type'] , $order['mingci'] , $order['content']);
        if(getGameCodeById($order['type']) == 'jnd28'){
            $mingci = '';
//            $content = $order['content'];
        }else{
            $mingci = ((is_numeric($order['mingci']) && $order['mingci']==0)?10:$order['mingci']);
            $content = ((is_numeric($order['content']) && $order['content']==0)?10:$order['content']);
//            $content = $content;
            switch ($mingci){
                case 1:
                    $mingci = '冠军';
                    break;
                case 2:
                    $mingci = '亚军';
                    break;
                case 3:
                    $mingci = '第三名';
                    break;
                case 4:
                    $mingci = '第四名';
                    break;
                case 5:
                    $mingci = '第五名';
                    break;
                case 6:
                    $mingci = '第六名';
                    break;
                case 7:
                    $mingci = '第七名';
                    break;
                case 8:
                    $mingci = '第八名';
                    break;
                case 9:
                    $mingci = '第九名';
                    break;
                case 10:
                    $mingci = '第十名';
                    break;
                case '和':
                    $mingci = '冠亚和';
                    break;
            }
//            $content = $mingci.' / '.$content;
            $content = ($content < 10 && is_numeric($content)) ? '0' . $content : $content;
        }
        $order_list[] = [
            'gamename' => getGameTxtName($order['type']),
            'add_time' => date('m-d' , strtotime($order['addtime'])) . '<br>' . date('H:i:s' , strtotime($order['addtime'])),
            'term' => $order['term'],
            'userid' => $order['userid'],
            'mingci' => $mingci,
            'content' => $content,
            'peilv' => $peilv,
            'type' => $order['type'],
            'money' => $order['money'],
            'status' => $order['status'],
        ];
    }
    $all_touzhu = get_query_val($ordertable , 'sum(money)' , $sql);
    $all_paijian = get_query_val($ordertable , 'sum(status)' , $sql . " and status != '未结算'  and status != '已撤单'");
    $count = get_query_val($ordertable , 'count(id)' , $sql);
    return ['list'=>$order_list,'count'=>$count,'all_touzhu'=>$all_touzhu,'all_paijian'=>$all_paijian];
}
function getorder($userid, $time){
    $time2 = date('Y-m-d');
    switch($time){
        case 1:
            $time = date('Y-m-d');
            break;
        case 7:
            $time = date('Y-m-d', strtotime('-1 day'));
            break;
        case 30:
            $time = date('Y-m-d', strtotime('-30 day'));
            break;
    }
    $time = date('Y-m-d 06:00:00' , strtotime($time));
    $time2 = date('Y-m-d 05:59:59' , strtotime($time2 . '+1day'));
    $id = $userid;
    $code = "";
    $allmoney = 0;
    $allstatus = 0;

    select_query('fn_order', '*', "roomid = '{$_SESSION['roomid']}' and userid = '{$id}' and (`addtime` between '{$time}' and '{$time2}')");

    while($con = db_fetch_array()){
        $game = getGameTxtName($con['type']);
        $cons[] = $con;
        if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
        if($con['status'] > 0)$allstatus += $con['status'];
        $data['data'][] = array('#' . $con['id'], $con['username'], $game, $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);

    }
    if (count($cons) == 0){
        $data['data'][] = null;
    }
    $allstatus = $allstatus - $allmoney;
    $data['allmoney'] = sprintf("%.2f", substr(sprintf("%.3f", $allmoney), 0, -2));
    $data['allstatus'] = sprintf("%.2f", substr(sprintf("%.3f", $allstatus), 0, -2));
    return $data;
}
function getxia($userid){
    $allmoney = 0;
    $allliu = 0;
    $allyk = 0;
    $alls = 0;
    select_query("fn_user", '*', "roomid = {$_SESSION['roomid']} and agent = '{$userid}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $l = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0) and status !='未结算' and status !='已撤单'");
        $z = get_query_val('fn_order', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0 and status !='未结算' and status !='已撤单'");
        $y = $z - $l;
        $yk = $y;
        $yk = sprintf("%.2f", substr(sprintf("%.3f", $yk), 0, -2));
        $allyk += $yk;
        $liushui = $l;
        $allliu += $liushui;
        $s = get_query_val('fn_upmark', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` = '已处理'");
        $alls += $s;
        $arr['data'][] = array($con['id'], "<img src='{$con['headimg']}' style='width:30px;height:30px'> ", $con['username'], $con['money'], "$liushui", $yk, $s == "" ? '0.00' : $s, date('Y-m-d H:i:s', $con['statustime']));
        $allmoney += $con['money'];
    }
    $arr['allmoney'] = sprintf("%.2f", substr(sprintf("%.3f", $allmoney), 0, -2));
    $arr['allyk'] = sprintf("%.2f", substr(sprintf("%.3f", $allyk), 0, -2));
    $arr['alls'] = $alls;
    $arr['allliu'] = $allliu;
    return $arr;
}


function getAgentRegUserList($userids , $day1 , $day2){
    $day1 = date('Y-m-d 06:00:00' , strtotime($day1));
    $day2_ = date('Y-m-d 05:59:59' , strtotime($day2 . '+1day'));

    $sql = "roomid = {$_SESSION['roomid']} and userid in ('" . implode("','" , $userids) . "') and aztime between '{$day1}' and '{$day2_}'";
    return (int)get_query_val("fn_user", 'count(userid)',  $sql);
}
function getAgentUserStatistics($userid , $day1 , $day2){
    $tuandui_user_list = getAgentUserList($userid , 2);
    if($userid != $_SESSION['userid']){
        $agent_user_list = getAgentUserList($_SESSION['userid'] , 2);
        if(!in_array($userid , $agent_user_list)){
            return [];
        }
    }
    $benji_user_list = getAgentUserList($userid);
    $game_list = getGameList();
    $day1 = date('Y-m-d 06:00:00' , strtotime($day1));
    $day2_ = date('Y-m-d 05:59:59' , strtotime($day2 . '+1day'));
    $ordersql = "roomid = {$_SESSION['roomid']} and userid in ('" . implode("','" , $tuandui_user_list) . "') and status !='未结算' and status !='已撤单'";
    $ordersql2 = "roomid = {$_SESSION['roomid']} and userid in ('" . implode("','" , $benji_user_list) . "')";
    $gamelist = [];
    foreach ($game_list as $key =>&$game){
        $ordersql1 = ' and type = ' . $key;
        $detail['touzhu'] = (double)get_query_val('fn_order' , 'sum(money)' , $ordersql . $ordersql1 . " and (`addtime` between '{$day1}' and '{$day2_}')");
        $detail['zhongjian'] = (double)get_query_val('fn_order' , 'sum(status)' , $ordersql . $ordersql1 . " and (`addtime` between '{$day1}' and '{$day2_}') and status > 0");
        $detail['fandian'] = (double)get_query_val('fn_marklog', 'sum(`money`)', $ordersql2 . " and type = '返点' and (addtime between '{$day1}' and '{$day2_}') and game='" . getGameCodeById($key) . "'");
        $detail['tuandui_fandian'] = (double)get_query_val('fn_marklog', 'sum(`money`)', $ordersql . " and type = '返点' and (addtime between '{$day1}' and '{$day2_}') and game='" . getGameCodeById($key) . "'");
        $detail['yk'] = (double)($detail['zhongjian'] * 1 - $detail['touzhu'] * 1);
        $gamelist[$key] = $detail;
    }
    $time = array();
    $time[0] = $day1;
    $time[1] = $day2_;



    $touzhu = (double)get_query_val('fn_order', 'sum(`money`)', $ordersql . " and (`addtime` between '{$time[0]}' and '{$time[1]}')");
    $zhongjian = (double)get_query_val('fn_order', 'sum(`status`)', $ordersql . " and status > 0 and (`addtime` between '{$time[0]}' and '{$time[1]}')");
    $fandian_bj = (double)get_query_val('fn_marklog', 'sum(`money`)', $ordersql2 . " and type = '返点' and (addtime between '{$time[0]}' and '{$time[1]}')" );
    $fandian_td = (double)get_query_val('fn_marklog', 'sum(`money`)', $ordersql . " and type = '返点' and (addtime between '{$time[0]}' and '{$time[1]}')" );

    $huodong = (double)get_query_val('fn_marklog', 'sum(`money`)', $ordersql . " and type = '活动' and (addtime between '{$time[0]}' and '{$time[1]}')");
    $sf = (double)get_query_val('fn_upmark', 'sum(`money`)', $ordersql . " and type = '上分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");
    $xf = (double)get_query_val('fn_upmark', 'sum(`money`)', $ordersql . " and type = '下分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");


    $sql = "select userid from fn_order where " . $ordersql . " and (`addtime` between '{$time[0]}' and '{$time[1]}')" . " group  by userid";
    $result = full_query($sql);
    $touzhu_rs = $result->num_rows;

    $sql = "select userid from fn_upmark where " . $ordersql . " and type = '上分' and status = '已处理' and isfirst = 1 and (time between '{$time[0]}' and '{$time[1]}')" . " group  by userid";

    $result = full_query($sql);
    $shouchong_rs = $result->num_rows;

    $yue_bj = get_query_val('fn_user', 'sum(`money`)', $ordersql2);
    $yue_td = get_query_val('fn_user', 'sum(`money`)', $ordersql);

//    $zhucu = getAgentUserList($userid , 2 , $day1 , $day2);

    $info = array(
        'tz' => $touzhu,
        'zhongjian' => $zhongjian,
        'yk' => $zhongjian - $touzhu + $huodong + $fandian_td,
        'fd_bj' => $fandian_bj,
        'fd_td' => $fandian_td,
        'huodong' => $huodong,
        'sf' => $sf?$sf:0,
        'xf' => $xf?$xf:0,
        'touzhu_rs' => $touzhu_rs,
        'shouchong_rs' => $shouchong_rs,
        'yue_bj' => $yue_bj,
        'yue_td' => $yue_td,
        'tuandui' => count($tuandui_user_list),
        'zhuce' => getAgentRegUserList($tuandui_user_list , $day1 , $day2)
    );
    return ['game'=>$gamelist,'agent'=>$info];
}
function getUserStatistics($userid , $day1 , $day2){
    $game_list = getGameList();
    $day1 = date('Y-m-d 06:00:00' , strtotime($day1));
    $day2_ = date('Y-m-d 05:59:59' , strtotime($day2 . '+1day'));
    $ordersql = "roomid = {$_SESSION['roomid']} and userid = '{$userid}' and (`addtime` between '{$day1}' and '{$day2_}') and status !='未结算' and status !='已撤单'";
    $gamelist = [];
    foreach ($game_list as $key =>$game){
        $ordersql1 = ' and type = ' . $key;
        $detail['touzhu'] = sprintf("%.2f",(double)get_query_val('fn_order' , 'sum(money)' , $ordersql . $ordersql1));
        $detail['zhongjian'] = sprintf("%.2f",(double)get_query_val('fn_order' , 'sum(status)' , $ordersql . $ordersql1 . ' and status > 0'));
        $detail['fandian'] = sprintf("%.2f",(double)get_query_val('fn_marklog', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and type = '返点' and (addtime between '{$day1}' and '{$day2_}') and game='" . getGameCodeById($key) . "'"));
        $detail['yk'] = sprintf("%.2f",(double)($detail['zhongjian'] * 1 - $detail['touzhu'] * 1 ));
        $gamelist[$key] = $detail;
    }
    $time = array();
    $time[0] = $day1;
    $time[1] = $day2_;
    $fandian = (double)get_query_val('fn_marklog', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and type = '返点' and (addtime between '{$time[0]}' and '{$time[1]}')" );
    $huodong = (double)get_query_val('fn_marklog', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and type = '活动' and (addtime between '{$time[0]}' and '{$time[1]}')");
    $sf = (double)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and type = '上分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");
    $xf = (double)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and type = '下分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");
    $allm = (double)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and status !='未结算' and status !='已撤单'");
    $allz = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['roomid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$userid}' and status !='未结算' and status !='已撤单'");
    $yk = $allz - $allm + $huodong + $fandian;
    $yk = round($yk , 2);
    $info = array("yk" => $yk, 'liu' => $allm?$allm:0, 'zj' => $allz?$allz:0, 'sf' => $sf?$sf:0, 'xf' => $xf?$xf:0, 'fandian' => $fandian, 'huodong' => $huodong);
    return ['game'=>$gamelist,'user'=>$info];
}
function invitecodelist($user_id , $type , $page = 1 , $limit = 10){
    $ordertable = 'fn_invite_code';
    $order = ['add_time desc'];
    $ordersql = "userid = '{$user_id}' and roomid = {$_SESSION['roomid']} ";
    if($type == 'dl'){
        $ordersql .= " and type = 1" ;
    }else{
        $ordersql .= " and type = 0" ;
    }
    select_query($ordertable, '*', $ordersql , $order , (($page - 1) * $limit) . ',' . $limit);
    $list = [];
    while ($item = db_fetch_array()) {
        $reg_user = explode(',' , $item['reg_user']);
        $reg_user = array_unique(array_filter($reg_user));
        $fandian = json_decode($item['fandian'],true);
        $list[] = [
            'add_time' => $item['add_time'],
//            'add_time' => date('y-m-d' , strtotime($item['add_time'])) . '<br>' . date('H-i:s' , strtotime($item['add_time'])),
            'reg_count' => count($reg_user),
            'invite_code' => $item['invite_code'],
            'id' => $item['id'],
            'fandian'=>$fandian,
            'fandian_len'=>count($fandian)

        ];
    }
    $count = get_query_val($ordertable , 'count(id)' , $ordersql);
    return ['list'=>$list,'count'=>$count];
}
?>