<?php
header("Content-type:textml;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
while (true){
    include_once getcwd() . "/Public/config.php";
    require_once "jiesuan.php";
    require_once getcwd() . "/mergeimg.php";
    fandian();
    mysqli_close($dbconn);
}
function fandian(){
    select_query("fn_order", '*', "status != '已撤单' and `status` != '未结算' and isfan = 0 and jia = 'false'");
    $order_list = [];
    while ($con = db_fetch_array()) {
        $order_list[] = $con;
    }
    if(empty($order_list)){
        sleep(1);
    }
    writeLog( "开始执行回水任务" . time(),'fandian');
    echo "开始执行回水任务<br>" . PHP_EOL;
    $fandian_list = [];
    foreach ($order_list as $order){
        if(!is_numeric($order['status'])){
            continue;
        }
        if($order['type'] == 9){//20230711
            lhctouzhufandian($order['userid'] , $order['money'] , $order['roomid'] , $order['type'] , $order['userid'] , $order['peilv_step']  , $fandian_list);
        }else{
            touzhufandian($order['userid'] , $order['money'] , $order['roomid'] , $order['type'] , $order['userid'] , 'log' , $fandian_list);
        }

        update_query('fn_order' , ['isfan'=>1],['id'=>$order['id']]);
    }
    foreach ($fandian_list as $user_id => $value){
        foreach ($value as $roomid => $money){
            writeLog( "用户{$user_id}获得回水" . $money . '||'  . time(),'fandian');
            update_query('fn_user', array('money' => '+=' . $money), array('userid' => $user_id, 'roomid' => $roomid));
        }
    }
    writeLog( "回水任务执行完成" . time(),'fandian');
}
