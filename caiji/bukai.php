<?php
header("Content-type:textml;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
include_once getcwd() . "/Public/config.php";
require_once "jiesuan.php";
require_once getcwd() . "/mergeimg.php";
select_query("fn_order", 'type,term', "status = '未结算' and addtime <= '" . date('Y-m-d H:i:s' , GetTtime() - 5 * 60) . "'");
$cons = [];
echo "未结算订单补开开始" . PHP_EOL;
while ($con = db_fetch_array()) {
    $cons[] = $con;
}
echo "共有"  . count($cons) .  "条订单待结算" . PHP_EOL;
foreach ($cons as $val){
    jiesuan($val['type'] , $val['term']);
}
echo "未结算订单补开结束" . PHP_EOL;
mysqli_close($dbconn);