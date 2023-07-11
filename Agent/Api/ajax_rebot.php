<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
if($_GET['game'] != "all" && $_GET['game'] != ''){
    $sql = " and game = '{$_GET['game']}'";
}else{
    $sql = "";
}
$page = $_GET['page']>=1?$_GET['page']:1;
$pagesize = $_GET['pagesize'];
$sql = "roomid = {$_SESSION['agent_room']}" . $sql;
select_query('fn_robots','id,headimg,name,game' , $sql , ['id desc'] , ($page-1)*$pagesize . ',' . $pagesize);
$list = [];
while($con = db_fetch_array()){
    foreach ($con as $key => $value){
        if(!is_string($key)){
            unset($con[$key]);
        }
    }
    $con['game'] = getGameTxtNameByCode($con['game']);
    $con['headimg'] = '<img src="' . WEB_HOST . $con['headimg'] . '" alt="" width="35" height="35">';
    $con['btn'] = '<a href="javascript:delrobots(' . $con['id'] . ')" class="btn btn-danger btn-sm">删除机器人</a> |
	<a href="javascript:showrobots(' . $con['id'] . ',\''. $con['name'] .'\')" class="btn btn-danger btn-sm">修改</a>
	';
    $list[] = $con;

}
$data = [
    'list' => $list,
    'totalCount' => get_query_val('fn_robots','count(id) as counts' , $sql)
];
echo json_encode($data);