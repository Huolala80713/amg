<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
switch($_GET['g']){
case 'pk10': $game = '1';
    $feng = get_query_val('fn_lottery1', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "xyft": $game = '2';
    $feng = get_query_val('fn_lottery2', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "cqssc": $game = '3';
    $feng = get_query_val('fn_lottery3', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "xy28": $game = '4';
	$feng = get_query_val('fn_lottery4', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "jnd28": $game = '5';
	$feng = get_query_val('fn_lottery5', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "jsmt": $game = '6';
    break;
}
$term_info = get_query_vals('fn_open', '*', "type = '$game' order by term desc limit 1");

$code = $term_info['code'];
$time = strtotime($term_info['next_time']) - time();
$next_time = $term_info['next_time'];
$next_term = $term_info['next_term'];
$term = $term_info['term'];
$fly = get_query_val('fn_setting', 'setting_flyorder', array('roomid' => $_SESSION['agent_room']));
if($game<4){
$flys = get_query_val('fn_setting', 'flyorder_' . $_GET['g'], array('roomid' => $_SESSION['agent_room']));
}
else{
$flys = "false";
}
if($term_info['iskaijiang'] == 0){
    $last_open = get_query_vals('fn_open', '*', "type = {$game} and next_term = '{$term_info['term']}' order by `next_time` desc limit 1");
//    $thisTerm= explode(',',$last_open['code']);
    //var_dump($thisTerm);exit;
    $code = $last_open['code'];
    $time = '正在开奖';
    $term = $last_open['term'];
    $next_term = $last_open['next_term'];
    $next_time = $last_open['next_time'];
}else{
    $time = strtotime($term_info['next_time']) - time() - $feng;
}
echo json_encode(array("code" => $code, 'next_time' => $next_time, 'next_term' => $next_term, 'term' => $term, 'time' => $time, 'feng' => $feng, 'flyorder' => $fly, 'flys' => $flys, 'roomid' => $_SESSION['agent_room']));
?>