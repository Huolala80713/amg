<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$game = $_GET['game'] == "" ? '' : $_GET['game'];
$term = $_GET['term'];
$date = $_GET['date'];
$datetype = $_GET['datetype'];
$page = $_GET['page']>=1?$_GET['page']:1;
$pagesize = $_GET['pagesize'];
$sql_ = ' 1 = 1';
if($term != '') $sql_ = " and term = '{$term}'";
if($game != '') $sql_ = " and type = '" . getGameIdByCode($game) . "'";
$sql = $sql_;
if($date != ''){
    $date = explode(' - ',$date);
    $sql .= " and (`time` between '{$date[0]}' and '{$date[1]}')";
    $sql_ .= " and (`addtime` between '{$date[0]}' and '{$date[1]}')";
}
select_query('fn_open','*', $sql , ['term desc'], ($page-1)*$pagesize . ',' . $pagesize);
$list = [];
$all_m = 0;
$all_yk = 0;
$all_zhu = 0;
while($con = db_fetch_array()){
    $list[] = $con;
}
foreach($list as &$con) {
    $m = get_query_val('fn_order', 'sum(money)', "type = '{$con['type']}' and term = '{$con['term']}' and roomid = {$_SESSION['agent_room']} and jia = 'false' and status != '未结算' and status != '已撤单'");
    $z = get_query_val('fn_order', 'sum(`status`)', "type = '{$con['type']}' and roomid = '{$_SESSION['agent_room']}' and jia = 'false' and status != '未结算' and status != '已撤单' and status > 0 and term = '{$con['term']}'");//中奖金额
    $con['game'] = getGameTxtName($con['type']);
    $con['touzhu'] = $m == ''?"<span class='badge '>0</span>" : "<span style='font-size:15px' class='badge bg-yellow'>{$m}</span>";
    $yk = round($z - $m , 4);
    if($yk > 0){
        $con['yk'] = "<span class='badge bg-green' style='font-size:15px'>{$yk}</span>";
    }elseif($yk < 0){
        $con['yk'] = "<span class='badge bg-red' style='font-size:15px'>{$yk}</span>";
    }else{
        $con['yk'] = "<span class='badge '>无注单</span>";
    }
    $zhu = (int)get_query_val('fn_order','count(*)',"type = '{$con['type']}' and term = '{$con['term']}' and roomid = {$_SESSION['agent_room']} and jia = 'false' and status != '未结算' and status != '已撤单'");
    if($zhu > 0) {
        $con['zhudan'] = "<span class='badge bg-blue' style='font-size:15px'>共 {$zhu} 注</span>";
    }else{
        $con['zhudan'] = "<span class='badge'>无注单</span>";
    }
    $con['btn'] = '<a href="javascript:disreport(\'' .  $con['term']  . '\',\'' . getGameCodeById($con['type']) . '\')" class="btn btn-success btn-sm">详细报表</a>';
}

$all_tz = (double)get_query_val('fn_order', 'sum(money)', $sql_ . " and jia = 'false' and status != '未结算' and status != '已撤单'");
$all_paijiang = (double)get_query_val('fn_order', 'sum(`status`)', $sql_ . " and jia = 'false' and status != '未结算' and status != '已撤单' and status > 0 ");
$all_count = (double)get_query_val('fn_order', 'count(id)', $sql_ . " and jia = 'false' and status != '未结算' and status != '已撤单'");
$data = [
    'list' => $list,
    'all_touzhu' => $all_tz,
    'all_yk' => round($all_paijiang - $all_tz , 4),
    'all_count' => $all_count,
    'totalCount' => get_query_val('fn_open','count(id) as counts' , $sql)
];
echo json_encode($data);