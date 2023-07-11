<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$game = $_GET['game'] == "" ? '' : $_GET['game'];
$date = $_GET['date'];
$datetype = $_GET['datetype'];
$user_id = $_GET['userid']?$_GET['userid']:'';
$game_list = getGameList();
foreach ($game_list as $key => $val){
    $info = get_query_vals('fn_lottery' . $key, 'gameopen', array('roomid' => $_SESSION['roomid']));
    if($info['gameopen'] == 'false'){
        unset($game_list[$key]);
    }
}
krsort($game_list);
$data_list = [];
$sql = 'jia = "false" and roomid = "' . $_SESSION['agent_room'] . '" and status !="未结算" and status !="已撤单"';
$fd_sql = 'roomid = "' . $_SESSION['agent_room'] . '" and type = "返点"';
if($date != ''){
    $date = explode(' - ' , $date);
    $sql .= ' and addtime between "' . $date[0] . '" and "' . $date[1] . '"';
    $fd_sql .= ' and addtime between "' . $date[0] . '" and "' . $date[1] . '"';
}
$all_xiadan = $all_touzhu = $all_paijian = $bj_all_xiadan = $bj_all_touzhu = $bj_all_paijian = $bj_all_fandian = $all_fandian = 0;
foreach($game_list as $key => $val){
    $bj_order_count = $bj_order_touzhu = $bj_order_paijian = $bj_fandian = $bj_yingkui = 0;
    if($game && getGameIdByCode($game) != $key){
        continue;
    }
    $sql_ = $sql . ' and type="' . $key . '"';
    $fd_sql_ = $fd_sql . ' and game="' . getGameCodeById($key) . '"';
    if($user_id){
        $xiaji_user_list = getAgentUserList($user_id , 2);
        $sql1 = $sql_ . ' and userid in ("' . implode('","' , $xiaji_user_list) . '")';
        $fd_sql1 = $fd_sql_ . ' and userid in ("' . implode('","' , $xiaji_user_list) . '")';
        $bj_all_xiadan += $bj_order_count = get_query_val('fn_order' , 'count(*)' , $sql_ . ' and userid = "' . $user_id . '"');
        $bj_all_touzhu += $bj_order_touzhu = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and userid = "' . $user_id . '"');
        $bj_all_paijian += $bj_order_paijian = get_query_val('fn_order' , 'sum(status)' , $sql_ . ' and userid = "' . $user_id . '" and status > 0');

        $bj_all_fandian += $bj_fandian = get_query_val('fn_marklog' , 'sum(money)' , $fd_sql_ . ' and userid = "' . $user_id . '"');
        $bj_yingkui = $bj_fandian + $bj_order_paijian - $bj_order_touzhu;
    }else{
        $sql1 = $sql_;
        $fd_sql1 = $fd_sql_;
    }
    $all_xiadan += $order_count = get_query_val('fn_order' , 'count(*)' , $sql1);
    $all_touzhu += $order_touzhu = get_query_val('fn_order' , 'sum(money)' , $sql1);
    $all_paijian += $order_paijian = get_query_val('fn_order' , 'sum(status)' , $sql1 . ' and status > 0');

    $all_fandian += $fandian = get_query_val('fn_marklog' , 'sum(money)' , $fd_sql1);

    $yingkui = $fandian + $order_paijian - $order_touzhu;


    $data_list[] = [
        'gamename'=>$val,
        'order_count'=>$order_count,
        'order_touzhu'=>round($order_touzhu , 4),
        'order_paijian'=>round($order_paijian , 4),
        'fandian'=>round($fandian , 4),
        'yingkui'=>round($yingkui , 4),
        'bj_order_count'=>$bj_order_count,
        'bj_order_touzhu'=>round($bj_order_touzhu , 4),
        'bj_order_paijian'=>round($bj_order_paijian , 4),
        'bj_fandian'=>round($bj_fandian , 4),
        'bj_yingkui'=>round($bj_yingkui , 4),
        'all_yingkui' => round($bj_fandian + $fandian + $bj_order_paijian - $bj_order_touzhu + $order_paijian - $order_touzhu , 4)
    ];
}


$data = [
    'list' => $data_list,
    'totalCount' => count($data_list),
    'all_xiadan' => $all_xiadan,
    'all_touzhu' => $all_touzhu,
    'all_paijian' => $all_paijian,
    'bj_all_xiadan' => $bj_all_xiadan,
    'bj_all_touzhu' => $bj_all_touzhu,
    'bj_all_paijian' => $bj_all_paijian,
    'bj_all_fandian' => $bj_all_fandian,
    'all_fandian' => $all_fandian
];
echo json_encode($data);