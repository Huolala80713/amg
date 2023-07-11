<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$time = $_GET['time'];
$user = $_GET['user'];
$page = $_GET['page']>=1?$_GET['page']:1;
$pagesize = $_GET['pagesize'];
$time = explode(' - ', $time);
$sql = "roomid = '{$_SESSION['agent_room']}' and jia = 'false'";
if(count($time) == 2){
    $sql_ = " and (time between '{$time[0]}' and '{$time[1]}')";
    $sql__ = " and (addtime between '{$time[0]}' and '{$time[1]}')";
}
if($user){
    $sql .= "userid = '{$user}'";
}
$arr = array();
select_query("fn_user", '*', $sql , ['id desc'] , ($page-1)*$pagesize . ',' . $pagesize);
$list = [];
while($con = db_fetch_array()){
    $list[] = $con;
}
//
$ALL_sf = 0;
$ALL_xf = 0;
$ALL_yk = 0;
$ALL_allm = 0;
$fandian = 0;
foreach($list as &$con){
    $sf = (double)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '上分' and status = '已处理'" . $sql_);
    $xf = (double)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '下分' and status = '已处理'" . $sql_);
    $allm = (double)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and status != '未结算' and status != '已撤单'" . $sql__);
    $allz = (double)get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and status != '未结算' and status != '已撤单' and status > 0 and userid = '{$con['userid']}'" . $sql__);
    $fd = (double)get_query_val('fn_marklog','sum(`money`)',"roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `type` = '返点'" . $sql__);
    $yk = round($allz - $allm , 4);
    $fd = round($fd , 4);
    $con['yk'] = $yk;
    $con['fd'] = $fd;
    $con['sf'] = $sf;
    $con['xf'] = $xf;
    $con['allm'] = $allm;
    $btn = '<a style="margin-right:10px;" href="#" onclick="disreport('.$con['id'].',\'' . $con['userid'] . '\',1)" class="btn btn-success">游戏报表</a>';
    $btn .= '<a href="#" onclick="disreport('.$con['id'].',\'' . $con['userid'] . '\',2)" class="btn btn-info">账变报表</a>';
    $con['btn'] = $btn;
}
$data = [
    'list' => $list,
    'totalCount' => get_query_val('fn_user','count(id) as counts' , $sql)
];
echo json_encode($data);