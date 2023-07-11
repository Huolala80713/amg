<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$page = $_GET['page']>=1?$_GET['page']:1;
$pagesize = $_GET['pagesize'];
$date = $_GET['date'];
$sql = '';
if($date){
    $date = explode(' - ' , $date);
    if(count($date) == 2){
        $sql = 'create_time between ' . strtotime($date[0] . ' 06:00:00') . ' and ' . strtotime(strtotime($date[1] . ' + 1days') . ' 05:59:59');
    }
}
select_query('fn_admin_log' , 'id,create_time,adminuser,ip,message' , $sql , ['id desc'] , ($page-1)*$pagesize . ',' . $pagesize);
$list = [];
while ($con = db_fetch_array()){
    foreach ($con as $key => $value){
        if(!is_string($key)){
            unset($con[$key]);
        }
    }
    $con['create_time'] = date('Y-m-d H:i:s' , $con['create_time'] );
    $list[] = $con;
}
$data = [
    'list' => $list,
    'totalCount' => get_query_val('fn_admin_log','count(id) as counts',$sql)
];
echo json_encode($data);
die();