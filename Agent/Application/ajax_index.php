<?php include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
if($_GET['t'] == 'logout'){
    $_SESSION['agent_user'] = '';
    $_SESSION['agent_pass'] = '';
    $_SESSION['agent_room'] = '';
    echo json_encode(array("success" => true));
    exit;
}
if(date('H') < 6){
    $date = date('Y-m-d' , strtotime('-1days'));
}else{
    $date = date('Y-m-d');
}
$day1 = date('Y-m-d 06:00:00' , strtotime($date));
$day2 = date('Y-m-d 05:59:59' , strtotime($date . ' + 1days'));
$arr = array();
$m = (int)get_query_val('fn_order', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and (`status` > 0 or `status` < 0)");
$m += (int)get_query_val('fn_pcorder', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and (`status` > 0 or `status` < 0)");
$m += (int)get_query_val('fn_sscorder', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and (`status` > 0 or `status` < 0)");
$m += (int)get_query_val('fn_jsscorder', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and (`status` > 0 or `status` < 0)");
$m += (int)get_query_val('fn_jssscorder', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and (`status` > 0 or `status` < 0)");
$m += (int)get_query_val('fn_mtorder', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and (`status` > 0 or `status` < 0)");
$z = (int)get_query_val('fn_order', 'sum(`status`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and status >= 0");
$z += (int)get_query_val('fn_pcorder', 'sum(`status`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and status >= 0");
$z += (int)get_query_val('fn_sscorder', 'sum(`status`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and status >= 0");
$z += (int)get_query_val('fn_jsscorder', 'sum(`status`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and status >= 0");
$z += (int)get_query_val('fn_jssscorder', 'sum(`status`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and status >= 0");
$z += (int)get_query_val('fn_mtorder', 'sum(`status`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and `addtime` between '{$day1}' and '{$day2}' and status >= 0");
$arr['zyk'] = $z - $m;
$arr['allsf'] = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and time between '{$day1}' and '{$day2}' and status = '已处理' and type = '上分' and `jia` = 'false'");
$arr['allxf'] = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and time between '{$day1}' and '{$day2}' and status = '已处理' and type = '下分' and `jia` = 'false'");
$arr['allpeople'] = (int)get_query_val('fn_user', 'count(*)', "`roomid` = '{$_SESSION['agent_room']}'");// and `jia` = 'false' and `money` > '0'
$arr['allprice'] = (int)get_query_val('fn_order', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and (`status` > 0 or `status` < 0) and `addtime` between '{$day1}' and '{$day2}'");
$arr['allprice'] += (int)get_query_val('fn_pcorder', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and (`status` > 0 or `status` < 0) and `addtime` between '{$day1}' and '{$day2}'");
$arr['allprice'] += (int)get_query_val('fn_sscorder', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and (`status` > 0 or `status` < 0) and `addtime` between '{$day1}' and '{$day2}'");
$arr['allprice'] += (int)get_query_val('fn_mtorder', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and (`status` > 0 or `status` < 0) and `addtime` between '{$day1}' and '{$day2}'");
$arr['allprice'] += (int)get_query_val('fn_jssscorder', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and (`status` > 0 or `status` < 0) and `addtime` between '{$day1}' and '{$day2}'");
$arr['allprice'] += (int)get_query_val('fn_jsscorder', 'sum(`money`)', "`roomid` = '{$_SESSION['agent_room']}' and `jia` = 'false' and (`status` > 0 or `status` < 0) and `addtime` between '{$day1}' and '{$day2}'");
echo json_encode($arr);
?>