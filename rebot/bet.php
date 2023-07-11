<?php
include(dirname(dirname((preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
include(dirname(dirname((preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Application/ajax_chat.php");
$gameid = $_GET['gameid'];
$room_id = $_GET['roomid'];
$game_code = getGameCodeById($gameid);
echo PHP_EOL  . '-----------执行机器人任务开始----------<br>' . PHP_EOL;
if($game_code && $room_id){
    $_SESSION['roomid'] = $room_id;
    $_SESSION['game'] = $game_code;
    if (get_query_val('fn_lottery' . $gameid, 'gameopen', array('roomid' => $room_id)) != 'false'){
        $stystime = GetTtime();
        $opendata = get_query_vals('fn_open', 'next_term,next_time,time,iskaijiang', "type = {$gameid} order by `id` desc limit 1");
        if(empty($opendata)){
            return ;
        }
        $BetTerm = $opendata['next_term'];
        $time = (int)get_query_val('fn_lottery' . getGameIdByCode($game_code) , 'fengtime', array('roomid' => $room_id));
        $left_time = strtotime($opendata['next_time']) - $stystime - $time - 5;
        $open_time = $stystime - strtotime($opendata['time']);

//        if ($left_time < 1) {
//            echo "游戏已停止下注" . PHP_EOL;
//        }elseif ($open_time <= 10) {
//            //超过十秒后执行
//            echo "超过十秒后执行" . PHP_EOL;
//        } elseif ($opendata['iskaijiang'] == 0) {
//            //超过十秒后执行
//            echo "正在开奖中" . PHP_EOL;
//        }  elseif ($gameid == 2 && date('H:i:s') >= '04:03:45' && date('H:i:s') <= '13:08:45') {
//            echo "幸运飞艇不在投注时间" . PHP_EOL;
//        }
//        else {
            select_query('fn_robots', '*', "roomid = {$room_id} and game = '{$game_code}' order by rand() desc limit 3");
            $robots_list = [];
            while ($con = db_fetch_array()) {
                $robots_list[] = $con;
            }
            foreach ($robots_list as $robots){
                $headimg = $robots['headimg'];
                $name = $robots['name'];
                if($game_code == 'jnd28'){
                    echo '加拿大28游戏未设置机器人规则' . PHP_EOL;
                }else{
                    $wanfa_list = [
                        '特码','冠亚和','龙虎','大小单双'
                    ];
                    $wanfa = $wanfa_list[array_rand($wanfa_list)];
                    switch ($wanfa){
                        case '特码':
                            $mingci = rand(0,9);
                            $content = rand(0,9);
                            break;
                        case '冠亚和':
                            $mingci = '和';
                            $content = array(3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,'大','小','单','双');
                            $content = $content[array_rand($content)];
                            break;
                        case '龙虎':
                            $mingci = array(1,2,3,4,5);
                            $content = array('龙','虎');
                            $mingci = $mingci[array_rand($mingci)];
                            $content = $content[array_rand($content)];
                            break;
                        case '大小单双':
                            $mingci = rand(0,9);
                            $content = array('大','小','单','双');
                            $content = $content[array_rand($content)];
                            break;
                    }
                    $money = rand(5,500);
                    if($money >= 10){
                        $money = round($money / 10) * 10;
                    }
                    $plan = "$mingci/$content/$money";
                    insert_query("fn_chat", array("username" => $name, 'content' => $plan, 'addtime' => date('H:i:s'), 'game' => $game_code, 'headimg' => $headimg, 'type' => 'U3', 'userid' => 'robot', 'roomid' => $room_id));
                    $res = addRobotBet('robot' , $name, $headimg, $plan, $BetTerm, false , $game_code);
                    //var_dump($res);
                    if($res){
                        $message = '';
                        $money_count = '';
                        $message_list = [];
                        $touzhu_content = explode(' ' , $plan);
                        foreach ($touzhu_content as $key => $con) {
                            $con = explode('/' , $con);
                            $key = ((is_numeric($con[0]) && $con[0] == 0) ? 10 : $con[0]);
                            $value = ((is_numeric($con[1]) && $con[1] == 0) ? 10 : $con[1]);
                            $message_list[$key][$value] += $con[2];
                            $money_count += $con[2];
                            $peilv = getPeilv($con['userid'] , $con['roomid'] , getGameIdByCode($game_code) , $con[0] , $con[1]);
                            //update_query('fn_order' , ['peilv'=>$peilv],['id'=>$con['id']]);
                            //删除订单数据 20230710
                            //var_dump($con);
                            //delete_query('fn_order',['userid'=>"robot"]);
                        }
                        foreach ($message_list as $key => $value) {
                            switch ($key) {
                                case 1:
                                    $message .= '冠军';
                                    break;
                                case 2:
                                    $message .= '亚军';
                                    break;
                                case 3:
                                    $message .= '第三名';
                                    break;
                                case 4:
                                    $message .= '第四名';
                                    break;
                                case 5:
                                    $message .= '第五名';
                                    break;
                                case 6:
                                    $message .= '第六名';
                                    break;
                                case 7:
                                    $message .= '第七名';
                                    break;
                                case 8:
                                    $message .= '第八名';
                                    break;
                                case 9:
                                    $message .= '第九名';
                                    break;
                                case 10:
                                    $message .= '第十名';
                                    break;
                                case '和':
                                    $message .= '冠亚和';
                                    break;
                            }
                            $contents = [];
                            foreach ($value as $k => $val){
                                $contents[] = $k . '/' . $val;
                            }
                            $message .= '[' . implode(' ', $contents) . ']<br />';
                        }
                        if($message){
                            $message_content = "@{$name},亲，{$BetTerm}期竞猜成功<br>";
                            $message_content .= "本次竞猜总分：" . $money_count . "<br>";
                            $message_content .= "竞猜内容：<br>";
                            $message_content .= $message . date('H:i:s');
                            管理员喊话($message_content);
                        }
                        //删除一投注记录 20230710
                        foreach($res as $v){
                            $id = intval($v);
                            delete_query('fn_order',['id'=>$id]);
                        }
                    }
                    echo '机器人投注完成，方案：' . $plan . PHP_EOL;
                }
            }
//        }
    }else{
        echo "游戏未开启" . PHP_EOL;
    }
}else{
    echo '参数错误' . PHP_EOL;
}
echo '<br>-----------执行机器人任务结束----------<br>' . PHP_EOL;