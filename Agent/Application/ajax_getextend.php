<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
if($type == 'get'){
    $time = $_GET['time']?$_GET['time']:'';
    $sql1 = $sql2 = '';
    if($time){
        $time = explode(' - ' , $time);
        $time[0] = date('Y-m-d 06:00:00' , strtotime($time[0]));
        $time[1] = date('Y-m-d 05:59:59' , strtotime($time[1] . ' + 1day'));
        $sql1 = " addtime between '{$time[0]}' and '{$time[1]}' and ";
        $sql2 = " time between '{$time[0]}' and '{$time[1]}' and ";
    }
    $id = $_GET['id'];
    $userid = get_query_val('fn_user', 'userid', array('id' => $id));
    $allmoney = 0;
    $allliu = 0;
    $allyk = 0;
    $alls = 0;
    $fandian = 0;
    select_query("fn_user", '*', "roomid = {$_SESSION['agent_room']} and agent = '{$userid}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $l = (int)get_query_val('fn_order', 'sum(`money`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $pcl = (int)get_query_val('fn_pcorder', 'sum(`money`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $sscl = (int)get_query_val('fn_sscorder', 'sum(`money`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $mtl = (int)get_query_val('fn_mtorder', 'sum(`money`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $jsscl = (int)get_query_val('fn_jsscorder', 'sum(`money`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $jssscl = (int)get_query_val('fn_jssscorder', 'sum(`money`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $z = get_query_val('fn_order', 'sum(`status`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $pcz = get_query_val('fn_pcorder', 'sum(`status`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $sscz = get_query_val('fn_sscorder', 'sum(`status`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $mtz = get_query_val('fn_mtorder', 'sum(`status`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $jsscz = get_query_val('fn_jsscorder', 'sum(`status`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $jssscz = get_query_val('fn_jssscorder', 'sum(`status`)', $sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $y = $z - $l;
        $pcy = $pcz - $pcl;
        $sscy = $sscz - $sscl;
        $mty = $mtz - $mtl;
        $jsscy = $jsscz - $jsscl;
        $jssscy = $jssscz - $jssscl;
        $yk = $y + $pcy + $sscy + $mty + $jsscy + $jssscy;
        $yk = sprintf("%.2f", substr(sprintf("%.3f", $yk), 0, -2));
        $allyk += $yk;
        $liushui = $l + $pcl + $sscl + $mtl + $jsscl + $jssscl;
        $allliu += $liushui;
        $s = get_query_val('fn_upmark', 'sum(`money`)', $sql2 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` = '已处理'");
        $alls += $s;
        $fd = (double)get_query_val('fn_marklog','sum(`money`)',$sql1 . "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `type` = '返点' ");
        $fandian += $fd;
        $xiaji_count = get_query_val('fn_user','count(*)',array('roomid'=>$_SESSION['agent_room'],'agent'=>$con['userid']));
        $xiaji = '<a class="xiaji badge bg-purple" data-user="'.$con['username'].'" data-id="'.$con['id'].'" href="javascript:void(0)" >' . $xiaji_count . '人</a>';
        $arr['data'][] = array(
		$con['id'], 
		$con['userid'], 
		"<img src='{$con['headimg']}' height='30' width='30'> " . $con['username'] ,
		$con['remark'], 
		 $con['isagent'] == 'false'?'否':'是' , 
		 $con['agent'] , 
		 $xiaji , 
		 $con['money'], 
		 "$liushui", 
		 $yk, 
		 $s, 
		 $fd, 
		 date('Y-m-d H:i:s', $con['statustime']));
        $allmoney += $con['money'];

    }
    if(count($cons) == 0){
        $arr['data'][0] = 'null';
    }
    $arr['allmoney'] = sprintf("%.2f", substr(sprintf("%.3f", $allmoney), 0, -2));
    $arr['allyk'] = sprintf("%.2f", substr(sprintf("%.3f", $allyk), 0, -2));
    $arr['alls'] = $alls;
    $arr['allliu'] = $allliu;
    $arr['fandian'] = $fandian;
    echo json_encode($arr);
    exit();
}elseif($type == 'giveone'){
    $id = $_POST['id'];
    $num = $_POST['num'];
    $mode = $_POST['mode'];
    $time = $_POST['time'] == '' ? '总报表账目' : $_POST['time'];
    $num = $num / 100;
    $userid = get_query_val('fn_user', 'userid', array('id' => $id));
    if($_POST['time'] != ""){
        $a = explode(' - ', $_POST['time']);
        $a[0] = date('Y-m-d 06:00:00' , strtotime($a[0]));
        $a[1] = date('Y-m-d 05:59:59' , strtotime($a[1] . ' + 1day'));
        $ordersql = " and (`addtime` between '{$a[0]}' and '$a[1]')";
        $marksql = " and (`time` between '{$a[0]}' and '$a[1]')";
    }else{
        $ordersql = '';
        $marksql = '';
    }
    select_query("fn_user", '*', "roomid = {$_SESSION['agent_room']} and agent = '{$userid}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        switch($mode){
        case 'liushui': $l = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $pcl = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $sscl = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $mtl = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jsscl = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jssscl = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $liushui = $l + $pcl + $sscl + $mtl + $jsscl + $jssscl;
            $money = $liushui * $num;
            用户_上分($userid, $money, $_SESSION['agent_room'], $time);
            echo json_encode(array("success" => true, "msg" => "分红完毕,获得" . $money . '元', 'money' => getmoney($userid)));
            break;
        case "yingli": $l = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $pcl = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $sscl = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $mtl = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jsscl = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jssscl = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $z = get_query_val('fn_order', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $pcz = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $sscz = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $mtz = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $jsscz = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $jssscz = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $yk = $z - $l;
            $yk += $pcz - $pcl;
            $yk += $sscz - $sscl;
            $yk += $mtz - $mtl;
            $yk += $jsscz - $jsscl;
            $yk += $jssscz - $jssscl;
            if($yk > 0){
                $money = $yk * $num;
                用户_上分($userid, $money, $_SESSION['agent_room'], $time);
                echo json_encode(array("success" => true, "msg" => "分红完毕,获得" . $money . '元', 'money' => getmoney($userid)));
            }else{
                echo json_encode(array("success" => true, "msg" => "该团队并无盈利", "money" => getmoney($userid)));
            }
            break;
        case "kuisun": $l = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $pcl = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $sscl = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $mtl = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jsscl = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jssscl = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $z = get_query_val('fn_order', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $pcz = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $sscz = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $mtz = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $jsscz = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $jssscz = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $yk = $z - $l;
            $yk += $pcz - $pcl;
            $yk += $sscz - $sscl;
            $yk += $mtz - $mtl;
            $yk += $jsscz - $jsscl;
            $yk += $jssscz - $jssscl;
            if($yk < 0){
                $money = $yk * $num;
                用户_上分($userid, $money, $_SESSION['agent_room'], $time);
                echo json_encode(array("success" => true, "msg" => "分红完毕,获得" . $money . '元', 'money' => getmoney($userid)));
            }else{
                echo json_encode(array("success" => true, "msg" => "该团队并无亏损"));
            }
            break;
        case "chongzhi": $s = get_query_val('fn_upmark', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` = '已处理'" . $marksql);
            $money = $s * $num;
            用户_上分($userid, $money, $_SESSION['agent_room'], $time);
            echo json_encode(array("success" => true, "msg" => "分红完毕,获得" . $money . '元', 'money' => getmoney($userid)));
            break;
        }
    }
}elseif($type == 'add'){
    $id = $_GET['id'];
    $user = get_query_val('fn_user' , 'username' , array("id" => $id));
    doLog('添加代理【' . $user . '】');
    update_query("fn_user", array("isagent" => "true"), array("id" => $id));
    echo json_encode(array("success" => true));
}elseif($type == 'addxia'){
    $id = $_GET['id'];
    $agent = $_GET['agent'];
    if(get_query_val("fn_user", "agent", array("id" => $id)) != 'null'){
        echo json_encode(array('success' => false, 'msg' => '该玩家已经拥有下线,无法手动为该玩家添加下线..'));
        exit;
    }else{
        $user = get_query_val('fn_user' , 'username' , array("id" => $agent));
        $user_ = get_query_val('fn_user' , 'username' , array("id" => $id));
        doLog('未代理用户【' . $user . '】添加下线【' . $user_ . '】');
        $userid = get_query_val('fn_user', 'userid', array('id' => $agent));
        update_query("fn_user", array("agent" => $userid), array('id' => $id));
        echo json_encode(array("success" => true));
        exit;
    }
}elseif($type == 'removexia'){
    $id = $_GET['id'];
    $user = get_query_vals('fn_user' , 'username,agent' , array("id" => $id));
    $agent = get_query_val('fn_user' , 'username' , array("id" => $user['agent']));
    doLog('商户代理用户【' . $agent . '】下线【' . $user['username'] . '】');
    update_query("fn_user", array("agent" => "null"), array("id" => $id));
    echo json_encode(array("success" => true));
    exit;
}
function 用户_上分($Userid, $Money, $room, $time){
    update_query('fn_user', array('money' => '+=' . $Money), array('userid' => $Userid, 'roomid' => $room));
    insert_query("fn_marklog", array("roomid" => $room, 'userid' => $Userid, 'type' => '上分', 'content' => $time . '推广分红', 'money' => $Money, 'addtime' => 'now()'));
}
function getmoney($userid){
    return get_query_val('fn_user', 'money', array('roomid' => $_SESSION['agent_room'], 'userid' => $userid));
}
?>