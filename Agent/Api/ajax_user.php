<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$sql = '';
$page = $_GET['page']>=1?$_GET['page']:1;
$pagesize = $_GET['pagesize'];
$user = $_GET['user'];
$type = $_GET['type'] == ''?'':'jia';
$sql = "roomid = '{$_SESSION['agent_room']}'";
if($type == 'jia'){
    $sql .= " and jia = 'true'";
}else{
    $sql .= " and jia = 'false'";
}
if($user){
    $sql .= " and (`id` like '%{$user}%' or userid  like '%{$user}%' or username  like '%{$user}%' or uid  like '%{$user}%' or remark  like '%{$user}%')";
}
select_query('fn_user','*',$sql,['id desc'],($page-1)*$pagesize . ',' . $pagesize);
$list = [];
while($con = db_fetch_array()){
    $list[] = $con;
}
foreach($list as &$con){
    $con['fandian'] = (double)get_query_val('fn_marklog','sum(`money`)',"roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `type` = '返点' ");
    $con['fandian'] = round($con['fandian'] , 4);
    $con['username_text'] = '<img src="' . WEB_HOST . $con['headimg'] . '" width="35" height="35">&nbsp;' . $con['username'] . ($con['jia'] == 'true'?'<span class="badge bg-purple">假人</span>':'');
    if($con['agent'] == 'null'){
        $con['agent'] = '无';
    }else{
        $con['agent'] = get_query_val('fn_user','userid',"roomid = {$_SESSION['agent_room']} and userid = '{$con['agent']}'");
    }
    $btn = '<a href="#" onclick="modifypass('.$con['id'].',\'' . $con['username'] . '\')" class="text">修改密码</a> || ';
    if($con['is_black'] != 1){
        $btn .= '<a href="#" onclick="lahei('.$con['id'].',\'' . $con['username'] . '\')" class="text">拉黑用户</a> || ';
    }else{
        $btn .= '<a href="#" onclick="lahei('.$con['id'].',\'' . $con['username'] . '\')" class="text">拉白用户</a> || ';
    }

    $btn .= '<a href="#" onclick="remark('.$con['id'].',\'' . $con['username'] . '\')" class="text">备注信息</a> <br>';
    $btn .= '<a href="#" onclick="disreport('.$con['id'].',\'' . $con['userid'] . '\',1)" class="text">游戏报表</a> || ';
    $btn .= '<a href="#" onclick="disreport('.$con['id'].',\'' . $con['userid'] . '\',2)" class="text">账变报表</a> || ';


    if(!get_query_val('fn_ban','username',"roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}'")){
        $btn .= '<a href="#" onclick="banuser('.$con['id'].',\'' . $con['username'] . '\')" class="text">禁言玩家</a><br>';
    }else{
        $btn .= '<a href="#" onclick="delbanuser('.$con['id'].',\'' . $con['username'] . '\')" class="text">取消禁言</a><br>';
    }
    $btn .= '<a href="#" onclick="changejia('.$con['id'].',\'' . $con['username'] . '\')" class="text">' . ($con['jia'] == 'true'?'取消假人':'设置假人') . '</a> || ';
    $btn .= '<a href="#" onclick="disupmark('.$con['id'].',\'' . $con['username'] . '\')" class="text">分数操作</a> || ';
    $btn .= '<a href="#" onclick="dischat('.$con['id'].',\'' . $con['username'] . '\')" class="text">私信TA</a>';

    $con['btn'] = $btn;
    if (time() - (int)$con['statustime'] > 5000) {
        $con['online'] = '离线';
    } else {
        $con['online'] = '在线';
    }
    $con['isagent'] = ($con['isagent'] == 'false') ? '否' : '是';
	//在线时间
	$con['create_time'] = $con['create_time'] ? date("Y-m-d H:i") : '';
}
$data = [
    'list' => $list,
    'money_count' => (double)get_query_val('fn_user','sum(money) as counts' , $sql),
    'totalCount' => get_query_val('fn_user','count(id) as counts' , $sql)
];
echo json_encode($data);