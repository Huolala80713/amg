<?php
include(dirname(dirname((preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$game_list = getGameList();
unset($game_list[9]);
echo PHP_EOL  . '-----------删除机器人订单开始----------<br>' . PHP_EOL;
foreach($game_list as $gameid=>$val){
    //获取最新的开奖期号
    $opendata = get_query_vals('fn_open', 'term,next_term,next_time,time,iskaijiang', "type = {$gameid} order by `id` desc limit 1");
    if(empty($opendata)){
        return ;
    }
    delete_query("fn_order", "`term` < {$opendata['term']} and `userid` = 'robot' and `jia` = 'true'");
}
echo '<br>----------删除机器人订单结束----------<br>' . PHP_EOL;