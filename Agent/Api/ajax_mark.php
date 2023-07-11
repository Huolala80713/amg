<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$sql = '';
$page = $_GET['page']>=1?$_GET['page']:1;
$pagesize = $_GET['pagesize'];
$time = $_GET['time'];
$user = $_GET['user'];
$status = $_GET['status'];
$datetype = $_GET['datetype'];
$type = $_GET['type'] == 'down'?'下分':'上分';
$sql = "roomid = '{$_SESSION['agent_room']}' and type = '{$type}'";
if($time != ''){
    $time = explode(' - ',$time);
    $sql .= "  and  (`time` between '{$time[0]}' and '{$time[1]}')";
}
if($status != 'all' && $status != ''){
    $sql .= " and status = '{$status}'";
}
if($user){
    $sql .= " and userid='{$user}'";
}
select_query('fn_upmark','*',$sql,['id desc'],($page-1)*$pagesize . ',' . $pagesize);
$list = [];
while($con = db_fetch_array()){
    $list[] = $con;
}
foreach($list as &$con){
    $user = get_query_vals('fn_user','uid,remark',"roomid = {$con['roomid']} and userid = '{$con['userid']}'");
    $con['uid'] = $user['uid'];
    $con['remark'] = $user['remark'];
    if($con['status'] == '未处理'){
        $con['btn'] = '<a style="margin-right:10px;" href="javascript:tongyi(' . $con['id'] . ')" class="btn btn-success btn-sm">同意请求</a><a href="javascript:jujue(' . $con['id'] . ')" class="btn btn-danger btn-sm">拒绝请求</a>';
    }elseif($con['status'] == '已拒绝'){
        $con['btn'] = '<a class="label label-danger">已拒绝</a>';
    }else{
        $con['btn'] = '<a class="label label-success">已同意</a>';
    }
}
$data = [
    'list' => $list,
    'money_amount' => (double)get_query_val('fn_upmark','sum(money)' , $sql),
    'totalCount' => get_query_val('fn_upmark','count(id) as counts' , $sql)
];
echo json_encode($data);