<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$datetype = $_GET['datetype'];
$time = $_GET['time'];
$time = explode(' - ',$time);
$page = $_GET['page']>=1?$_GET['page']:1;
$pagesize = $_GET['pagesize'];
$sql = "roomid = {$_SESSION['agent_room']} and isagent = 'true'";
$field = 'id,userid,headimg,username,money,agent,statustime,remark';
select_query('fn_user',$field,$sql,['id asc'],($page-1)*$pagesize . ',' . $pagesize);
$list = [];
while($con = db_fetch_array()){
    $list[] = $con;
}
foreach ($list as &$value){
    $sql_ = 'jia = "false" and roomid = "' . $_SESSION['agent_room'] . '" and status !="未结算" and status !="已撤单"';
    if(count($time) == 2){
        $ordersql = $sql_ . " and (`addtime` between '{$time[0]}' and '{$time[1]}')";
        $marksql = "(`addtime` between '{$time[0]}' and '{$time[1]}')";
        $upmarksql = "(`time` between '{$time[0]}' and '{$time[1]}')";
    }else{
        $ordersql = $sql_;
        $marksql = '';
        $upmarksql = '';
    }
    $xiaji_user_list = getAgentUserList($value['userid'], 2);
    $value['user_count'] = count($xiaji_user_list);
    $value['ls'] = (double)get_query_val('fn_order', 'sum(money)', $ordersql . ' and userid in ("' . implode('","', $xiaji_user_list) . '")');
    $value['pj'] = (double)get_query_val('fn_order', 'sum(status)', $ordersql . ' and status > 0 and userid in ("' . implode('","', $xiaji_user_list) . '")');
    $value['fd'] = $fandian = (double)get_query_val('fn_marklog', 'sum(money)', $marksql . ' and userid in ("' . implode('","', $xiaji_user_list) . '")');
    $value['ls'] = round($value['ls'] , 4);
    $value['pj'] = round($value['pj'] , 4);
    $value['fd'] = round($value['fd'] , 4);
    $value['yk'] = round($value['pj'] + $value['fd'] - $value['ls'] , 4);
    $value['sf'] = (double)get_query_val('fn_upmark','sum(`money`)',$upmarksql . "and roomid = {$_SESSION['agent_room']} and userid in ('" . implode('","', $xiaji_user_list) .  "') and `status` = '已处理'");
    $value['sf'] = round($value['sf'] , 4);
    $value['amount_money'] = (double)get_query_val('fn_user','sum(`money`)',"roomid = {$_SESSION['agent_room']} and jia = 'false'  and userid in ('" . implode('","', $xiaji_user_list) .  "')");
    $value['amount_money'] = round($value['amount_money'] , 4);
    $value['btn'] = '<a href="javascript:addxia(\'' .  $value['id']  . '\',\'' . $value['username'] . '\');" class="btn btn-info btn-sm">添加下线</a>';
    $value['username_text'] = '<img src="' . $value['headimg'] . '" width="35" height="35">&nbsp;' . $value['username'];
    $value['agent'] = $value['agent']?:'<span style="color:red;">一级代理</span>';
    $value['statustime'] = date('Y-m-d H:i:s' , $value['statustime']);
    if($value['user_count']) {
        $value['user_count'] = '<a href="javascript:disagent(\'' . $value['id'] . '\',\'' . $value['username'] . '\')" class="badge bg-purple">' . $value['user_count'] . '</a>';
    }else{
        $value['user_count'] = '<a href="javascript:disagent(\''.$value['id'].'\',\''.$value['username'].'\')" class="badge bg-default">' . $value['user_count'] . '</a>';
    }
}
$data = [
    'list' => $list,
    'totalCount' => get_query_val("fn_user", 'count(id)', $sql)
];
echo json_encode($data);