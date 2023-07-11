<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$game = $_GET['game'] == "" ? '' : $_GET['game'];
$date = $_GET['date'];
$datetype = $_GET['datetype'];
$tongji = $_GET['tongji'];
$page = $_GET['page']>=1?$_GET['page']:1;
$pagesize = $_GET['pagesize'];
$user_id = $_GET['userid']?$_GET['userid']:'';
$map = "jia = 'false'  and roomid = '{$_SESSION['agent_room']}'";
if($user_id){
    $map .= " and agent = '{$user_id}'";
}else{
    $map .= " and agent is null";
}
$user_list = [];
if($tongji){
    select_query("fn_user", 'isagent,userid,id', $map,['id desc']);
}else{
    select_query("fn_user", 'isagent,userid,id', $map,['id desc'],($page-1)*$pagesize . ',' . $pagesize);
}
while ($con = db_fetch_array()) {
    $user_list[] = $con;
}
$game_list = getGameList();
$data_list = [];
$all_xiadan = $all_touzhu = $all_paijian = $bj_all_xiadan = $bj_all_touzhu = $bj_all_paijian = $bj_all_fandian = $all_fandian = 0;
$all_user_count = 0;
$sql = 'jia = "false" and roomid = "' . $_SESSION['agent_room'] . '" and status !="未结算" and status !="已撤单"';
$fd_sql = 'roomid = "' . $_SESSION['agent_room'] . '" and type = "返点"';
if($date != ''){
    $date = explode(' - ' , $date);
    $sql .= ' and addtime between "' . $date[0] . '" and "' . $date[1] . '"';
    $fd_sql .= ' and addtime between "' . $date[0] . '" and "' . $date[1] . '"';
}
if($game){
    $sql = $sql . ' and type="' . getGameIdByCode($game) . '"';
    $fd_sql = $fd_sql . ' and game="' .  $game . '"';
}
foreach($user_list as $key => &$value) {
    $value['isagent'] = ($value['isagent'] == 'true')?'代理':'玩家';
    $xiaji_user_list = getAgentUserList($value['userid'], 2);

    $all_user_count += count($xiaji_user_list);
    $all_xiadan += $touzhu_count = (int)get_query_val('fn_order', 'count(*)', $sql . ' and userid in ("' . implode('","', $xiaji_user_list) . '")');
    $all_touzhu += $touzhu_money = (double)get_query_val('fn_order', 'sum(money)', $sql . ' and userid in ("' . implode('","', $xiaji_user_list) . '")');
    $all_paijian += $touzhu_paijian = (double)get_query_val('fn_order', 'sum(status)', $sql . ' and status > 0 and userid in ("' . implode('","', $xiaji_user_list) . '")');
    $all_fandian += $fandian = (double)get_query_val('fn_marklog', 'sum(money)', $fd_sql . 'and userid in ("' . implode('","', $xiaji_user_list) . '")');


    $bj_all_xiadan += $bj_order_count = (int)get_query_val('fn_order', 'count(*)', $sql . ' and userid = "' . $value['userid'] . '"');
    $bj_all_touzhu += $bj_order_touzhu = (double)get_query_val('fn_order', 'sum(money)', $sql . ' and userid = "' . $value['userid'] . '"');
    $bj_all_paijian += $bj_order_paijian = (double)get_query_val('fn_order', 'sum(status)', $sql . ' and userid = "' . $value['userid'] . '" and status > 0');
    $bj_all_fandian += $bj_fandian = (double)get_query_val('fn_marklog', 'sum(money)', $fd_sql . ' and userid = "' . $value['userid'] . '"');


    $value['usercount'] = count($xiaji_user_list);
    $value['touzhu_count'] = $touzhu_count;
    $value['touzhu_money'] = round($touzhu_money ,4);
    $value['touzhu_paijian'] = round($touzhu_paijian , 4);
    $value['fandian'] = round($fandian , 4);
    $value['yingkui'] = round($fandian + $touzhu_paijian - $touzhu_money , 4);
    $value['bj_order_count'] = $bj_order_count;
    $value['bj_order_touzhu'] = round($bj_order_touzhu , 4);
    $value['bj_order_paijian'] = round($bj_order_paijian , 4);
    $value['bj_fandian'] = round($bj_fandian , 4);
    $value['bj_yingkui'] = round($bj_fandian + $bj_order_paijian - $bj_order_touzhu , 4);
    $value['heji_yingkui'] = round($value['bj_yingkui'] + $value['yingkui'] , 4);
    $value['userid'] = '<a href="JavaScript:void(0)" onclick="searchs(\'' . $value['userid'] . '\')" style="color: #ff0000;cursor:pointer;">' . $value['userid'] .'</a>';
}
$data = [
    'list' => $user_list,
    'totalCount' => get_query_val("fn_user", 'count(id)', $map),
    'all_xiadan' => $all_xiadan,
    'all_touzhu' => round($all_touzhu , 4),
    'all_paijian' => round($all_paijian , 4),
    'all_fandian' => round($all_fandian , 4),
    'all_yingkui' => round($all_fandian + $all_paijian - $all_touzhu , 4),
    'bj_all_xiadan' => $bj_all_xiadan,
    'bj_all_touzhu' => round($bj_all_touzhu , 4),
    'bj_all_paijian' => round($bj_all_paijian , 4),
    'bj_all_fandian' => round($bj_all_fandian , 4),
    'bj_all_yingkui' => round($bj_all_fandian + $bj_all_paijian - $bj_all_touzhu , 4),
    'all_yingkui_heji' => round($bj_all_fandian + $bj_all_paijian - $bj_all_touzhu  + $all_fandian + $all_paijian - $all_touzhu ,  4),
    'all_user_count' => $all_user_count
];
echo json_encode($data);