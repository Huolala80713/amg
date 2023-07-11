<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$time = $_GET['time'];
$datetype = $_GET['datetype'];
$type = $_GET['type'];
$user = $_GET['user'];
$page = $_GET['page']>=1?$_GET['page']:1;
$pagesize = $_GET['pagesize'];
$sql = "roomid = {$_SESSION['agent_room']} and userid !='robot'";
if($time != ''){
    $time = explode(' - ',$time);
    $sql .= "  and  (`addtime` between '{$time[0]}' and '{$time[1]}')";
}
if($type == 'all') $type = '';
if($type == 'up') $type = '上分';
if($type == 'down') $type = '下分';
if($type == 'paijiang') $type = '派奖';
if($type == 'touzhu') $type = '投注';
if($type == 'chedan') $type = '撤单退还';
if($type == 'fandian') $type = '返点';
if($type == 'huodong') $type = '活动';
if($type == 'huishui') $type = '回水';

$people = 0;
$sf = 0;
$xf = 0;
if($user != "") $sql .= " and `userid` like '{$user}'";
if($type != "") $sql .= " and `type` = '{$type}'";
select_query('fn_marklog','*',$sql  , ['id desc'], ($page-1)*$pagesize . ',' . $pagesize);
$list = [];
while($con = db_fetch_array()){
    $list[] = $con;
}
foreach($list as $key => &$con) {
    $user = get_query_vals('fn_user', 'username,headimg', array('userid' => $con['userid']));
    $con['username'] = $user['username'];
    $con['headimg'] = '<img src="' . WEB_HOST . $user['headimg'] . '" width="30" height="30">';
}
$data = [
    'list' => $list,
    'totalCount' => get_query_val('fn_marklog','count(id) as counts' , $sql)
];
echo json_encode($data);