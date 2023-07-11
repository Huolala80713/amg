<?php
header("Content-type:textml;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);

while (true){
    include_once getcwd() . "/Public/config.php";
    require_once "jiesuan.php";
    require_once getcwd() . "/mergeimg.php";
    kaijian();
    mysqli_close($dbconn);
}
function kaijian(){
    $time = date('Y-m-d H:i:s' , GetTtime());
    select_query("fn_open", '*', "iskaijiang = 0 and `time` <= '{$time}'");//iskaijiang = 0 and
    $open_list = [];
    while ($con = db_fetch_array()) {
        $open_list[] = $con;
    }
    if(empty($open_list)){
        sleep(1);
    }
    foreach ($open_list as $open){
        $open_code = array_filter(array_unique(explode(',' , $open['code'])) , function ($value){
            if($value !== ''){
                return $value;
            }
        });
        if(empty($open_code)){
            continue;
        }
        writeLog(getGameTxtName($open['type']) . "第{$open['term']}期正在开奖" . time(),'kaijiang/' . getGameCodeById($open['type']));
        jiesuan($open['type'] , $open['term']);
        $rowsend=[];
        $rowsend['current_sn']=$open['term'];
        $rowsend['next_sn']=$open['next_term'];
        $rowsend['this_time']=$open['time'];
        $rowsend['next_time']=$open['next_time'];
        $rowsend['letf_time']=strtotime($open['next_time'])-GetTtime();
        $rowsend['open_num']=$open['code'];
        doSaveOpenImg($open['type'],$rowsend,getGameCodeById($open['type']));
        $history_list = [];
        select_query('fn_open' , '*' , "term <= '{$open['term']}' and `type` = {$open['type']}" , ['time desc'],'10');
        while($con = db_fetch_array()){
            $history_list[] = $con;
        }
        doSaveOpenHistoryImg($open['type'] ,$history_list[0] ,  $history_list ,getGameCodeById($open['type']));
        kaichat($open['type'], $open['next_term'],$open['term'],$open['code']);
        update_query('fn_open' , ['iskaijiang'=>1],['id'=>$open['id']]);
        writeLog(getGameTxtName($open['type']) . "第{$open['term']}期开奖完成" . time(),'kaijiang/' . getGameCodeById($open['type']));
    }
}
