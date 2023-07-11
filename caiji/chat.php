<?php
include_once("../Public/config.php");
function 管理员喊话($Content, $roomid, $game)
{
    $headimg = get_query_val('fn_setting', 'setting_robotsimg', array('roomid' => $roomid));
    insert_query("fn_chat", array("username" => "机器人", "headimg" => $headimg, 'content' => $Content, 'addtime' => date('H:i:s'), 'type' => 'S3', 'userid' => 'system', 'game' => $game, 'roomid' => $roomid));
}

$time = GetTtime();
$pkdjs = strtotime(get_query_val('fn_open', 'next_time', "`type` = '1' order by `id` desc limit 1")) - $time;
$xyftdjs = strtotime(get_query_val('fn_open', 'next_time', "`type` = '2' order by `id` desc limit 1")) - $time;
$cqsscdjs = strtotime(get_query_val('fn_open', 'next_time', "`type` = '3' order by `id` desc limit 1")) - $time;
$pcdjs = strtotime(get_query_val('fn_open', 'next_time', "`type` = '4' order by `id` desc limit 1")) - $time;
$jnddjs = strtotime(get_query_val('fn_open', 'next_time', "`type` = '5' order by `id` desc limit 1")) - $time;
$jsmtdjs = strtotime(get_query_val('fn_open', 'next_time', "`type` = '6' order by `id` desc limit 1")) - $time;
$jsscdjs = strtotime(get_query_val('fn_open', 'next_time', "`type` = '7' order by `id` desc limit 1")) - $time;
$jssscdjs = strtotime(get_query_val('fn_open', 'next_time', "`type` = '8' order by `id` desc limit 1")) - $time;
select_query("fn_setting", '*', '');
while ($con = db_fetch_array()) {
    $cons[] = $con;
}
foreach ($cons as $con) {
    $roomid = $con['roomid'];
    $pk10open = get_query_val('fn_lottery1', 'gameopen', array('roomid' => $roomid)) == 'true' ? true : false;
    $xyftopen = get_query_val('fn_lottery2', 'gameopen', array('roomid' => $roomid)) == 'true' ? true : false;
    $cqsscopen = get_query_val('fn_lottery3', 'gameopen', array('roomid' => $roomid)) == 'true' ? true : false;
    $xy28open = get_query_val('fn_lottery4', 'gameopen', array('roomid' => $roomid)) == 'true' ? true : false;
    $jnd28open = get_query_val('fn_lottery5', 'gameopen', array('roomid' => $roomid)) == 'true' ? true : false;
    $jsmtopen = get_query_val('fn_lottery6', 'gameopen', array('roomid' => $roomid)) == 'true' ? true : false;
    $jsscopen = get_query_val('fn_lottery7', 'gameopen', array('roomid' => $roomid)) == 'true' ? true : false;
    $jssscopen = get_query_val('fn_lottery8', 'gameopen', array('roomid' => $roomid)) == 'true' ? true : false;
    if ($pk10open) {
        $pk10time =1;// (int)get_query_val('fn_lottery1', 'fengtime', array('roomid' => $roomid));
    }
    if ($xyftopen) {
        $xyfttime =1;// (int)get_query_val('fn_lottery2', 'fengtime', array('roomid' => $roomid));
    }
    if ($cqsscopen) {
        $cqssctime =1;// (int)get_query_val('fn_lottery3', 'fengtime', array('roomid' => $roomid));
    }
    if ($xy28open) {
        $pctime =1;// (int)get_query_val('fn_lottery4', 'fengtime', array('roomid' => $roomid));
    }
    if ($jnd28open) {
        $jndtime = 1;//(int)get_query_val('fn_lottery5', 'fengtime', array('roomid' => $roomid));
    }
    if ($jsmtopen) {
        $jsmttime =1;// (int)get_query_val('fn_lottery6', 'fengtime', array('roomid' => $roomid));
    }
    if ($jsscopen) {
        $jssctime =1;// (int)get_query_val('fn_lottery7', 'fengtime', array('roomid' => $roomid));
    }
    if ($jssscopen) {
        $jsssctime =1;// (int)get_query_val('fn_lottery8', 'fengtime', array('roomid' => $roomid));
    }
    $msg1 = (int)get_query_val('fn_setting', 'msg1_time', array('roomid' => $roomid));
    $msg1_cont = get_query_val('fn_setting', 'msg1_cont', array('roomid' => $roomid));
    $msg2 = (int)get_query_val('fn_setting', 'msg2_time', array('roomid' => $roomid));
    $msg2_cont = get_query_val('fn_setting', 'msg2_cont', array('roomid' => $roomid));
    $msg3 = (int)get_query_val('fn_setting', 'msg3_time', array('roomid' => $roomid));
    $msg3_cont = get_query_val('fn_setting', 'msg3_cont', array('roomid' => $roomid));
    if ($pk10open) {
        if ($pk10time + 30 == $pkdjs) {
            管理员喊话('------距离封盘还有30秒------<br>请需要下注的用户尽快投注', $roomid, 'pk10');
        }
        if ($pk10time == $pkdjs) {
            管理员喊话('------已封盘,截止投注------<br>第' . get_query_val('fn_open', 'next_term', "`type` = '1' order by `id` desc limit 1") . '期投注已经结束<br>请耐心等待开奖<br>开奖视频结果出来后即可正常下注', $roomid, 'pk10');
            senddata($roomid, 1, 'pk10');
        }
        if ($msg1_cont != "" && $pkdjs == $msg1) {
            管理员喊话($msg1_cont, $roomid, 'pk10');
        }
        if ($msg2_cont != "" && $pkdjs == $msg2) {
            管理员喊话($msg2_cont, $roomid, 'pk10');
        }
        if ($msg3_cont != "" && $pkdjs == $msg3) {
            管理员喊话($msg3_cont, $roomid, 'pk10');
        }
    }
    if ($xyftopen) {
        if ($xyfttime + 30 == $xyftdjs) {
            管理员喊话('------距离封盘还有30秒------<br>请需要下注的用户尽快投注', $roomid, 'xyft');
        }
        if ($xyfttime == $xyftdjs) {
            管理员喊话('------已封盘,截止投注------<br>第' . get_query_val('fn_open', 'next_term', "`type` = '2' order by `id` desc limit 1") . '期投注已经结束<br>请耐心等待开奖<br>开奖视频结果出来后即可正常下注', $roomid, 'xyft');
            senddata($roomid, 2, 'xyft');
        }
        if ($msg1_cont != "" && $xyftdjs == $msg1) {
            管理员喊话($msg1_cont, $roomid, 'xyft');
        }
        if ($msg2_cont != "" && $xyftdjs == $msg2) {
            管理员喊话($msg2_cont, $roomid, 'xyft');
        }
        if ($msg3_cont != "" && $xyftdjs == $msg3) {
            管理员喊话($msg3_cont, $roomid, 'xyft');
        }
    }
    if ($cqsscopen) {
        if ($cqssctime + 20 == $cqsscdjs) {
            管理员喊话('------距离封盘还有20秒------<br>请需要下注的用户尽快投注', $roomid, 'cqssc');
        }
        if ($cqssctime == $cqsscdjs) {
            管理员喊话('------已封盘,截止投注------<br>第' . get_query_val('fn_open', 'next_term', "`type` = '3' order by `id` desc limit 1") . '期投注已经结束<br>请耐心等待开奖<br>开奖视频结果出来后即可正常下注', $roomid, 'cqssc');
            senddata($roomid, 3, 'cqssc');
        }
        if ($msg1_cont != "" && $cqsscdjs == $msg1) {
            管理员喊话($msg1_cont, $roomid, 'cqssc');
        }
        if ($msg2_cont != "" && $cqsscdjs == $msg2) {
            管理员喊话($msg2_cont, $roomid, 'cqssc');
        }
        if ($msg3_cont != "" && $cqsscdjs == $msg3) {
            管理员喊话($msg3_cont, $roomid, 'cqssc');
        }
    }
    if ($xy28open) {
        if ($pctime + 20 == $pcdjs) {
            管理员喊话('------距离封盘还有20秒------<br>请需要下注的用户尽快投注', $roomid, 'xy28');
        }
        if ($pctime == $pcdjs) {
            管理员喊话('------已封盘,截止投注------<br>第' . get_query_val('fn_open', 'next_term', "`type` = '4' order by `id` desc limit 1") . '期投注已经结束<br>请耐心等待开奖<br>开奖视频结果出来后即可正常下注', $roomid, 'xy28');
            senddata($roomid, 4, 'xy28');
        }
        if ($msg1_cont != "" && $pcdjs == $msg1) {
            管理员喊话($msg1_cont, $roomid, 'xy28');
        }
        if ($msg2_cont != "" && $pcdjs == $msg2) {
            管理员喊话($msg2_cont, $roomid, 'xy28');
        }
        if ($msg3_cont != "" && $pcdjs == $msg3) {
            管理员喊话($msg3_cont, $roomid, 'xy28');
        }
    }
    if ($jnd28open) {
        if ($jndtime + 30 == $jnddjs) {
            管理员喊话('------距离封盘还有30秒------<br>请需要下注的用户尽快投注', $roomid, 'jnd28');
        }
        if ($jndtime == $jnddjs) {
            管理员喊话('------已封盘,截止投注------<br>第' . get_query_val('fn_open', 'next_term', "`type` = '5' order by `id` desc limit 1") . '期投注已经结束<br>请耐心等待开奖<br>开奖视频结果出来后即可正常下注', $roomid, 'jnd28');
            senddata($roomid, 5, 'jnd28');
        }
        if ($msg1_cont != "" && $jnddjs == $msg1) {
            管理员喊话($msg1_cont, $roomid, 'jnd28');
        }
        if ($msg2_cont != "" && $jnddjs == $msg2) {
            管理员喊话($msg2_cont, $roomid, 'jnd28');
        }
        if ($msg3_cont != "" && $jnddjs == $msg3) {
            管理员喊话($msg3_cont, $roomid, 'jnd28');
        }
    }
    if ($jsmtopen) {
        if ($jsmttime + 30 == $jsmtdjs) {
            管理员喊话('------距离封盘还有30秒------<br>请需要下注的用户尽快投注', $roomid, 'jsmt');
        }
        if ($jsmttime == $jsmtdjs) {
            管理员喊话('------已封盘,截止投注------<br>第' . get_query_val('fn_open', 'next_term', "`type` = '6' order by `id` desc limit 1") . '期投注已经结束<br>请耐心等待开奖<br>开奖视频结果出来后即可正常下注', $roomid, 'jsmt');
            senddata($roomid, 6, 'jsmt');
        }
        if ($msg1_cont != "" && $jsmtdjs == $msg1) {
            管理员喊话($msg1_cont, $roomid, 'jsmt');
        }
        if ($msg2_cont != "" && $jsmtdjs == $msg2) {
            管理员喊话($msg2_cont, $roomid, 'jsmt');
        }
        if ($msg3_cont != "" && $jsmtdjs == $msg3) {
            管理员喊话($msg3_cont, $roomid, 'jsmt');
        }
    }
    if ($jsscopen) {
        if ($jssctime + 30 == $jsscdjs) {
            管理员喊话('------距离封盘还有30秒------<br>请需要下注的用户尽快投注', $roomid, 'jssc');
        }
        if ($jssctime == $jsscdjs) {
            管理员喊话('------已封盘,截止投注------<br>第' . get_query_val('fn_open', 'next_term', "`type` = '7' order by `id` desc limit 1") . '期投注已经结束<br>请耐心等待开奖<br>开奖视频结果出来后即可正常下注', $roomid, 'jssc');
            senddata($roomid, 7, 'jssc');
        }
        if ($msg1_cont != "" && $jsscdjs == $msg1) {
            管理员喊话($msg1_cont, $roomid, 'jssc');
        }
        if ($msg2_cont != "" && $jsscdjs == $msg2) {
            管理员喊话($msg2_cont, $roomid, 'jssc');
        }
        if ($msg3_cont != "" && $jsscdjs == $msg3) {
            管理员喊话($msg3_cont, $roomid, 'jssc');
        }
    }
    if ($jssscopen) {
        if ($jsssctime + 30 == $jssscdjs) {
            管理员喊话('------距离封盘还有30秒------<br>请需要下注的用户尽快投注', $roomid, 'jsssc');
        }
        if ($jsssctime == $jssscdjs) {
            管理员喊话('------已封盘,截止投注------<br>第' . get_query_val('fn_open', 'next_term', "`type` = '8' order by `id` desc limit 1") . '期投注已经结束<br>请耐心等待开奖<br>开奖视频结果出来后即可正常下注', $roomid, 'jsssc');
            senddata($roomid, 8, 'jsssc');
        }
        if ($msg1_cont != "" && $jssscdjs == $msg1) {
            管理员喊话($msg1_cont, $roomid, 'jsssc');
        }
        if ($msg2_cont != "" && $jssscdjs == $msg2) {
            管理员喊话($msg2_cont, $roomid, 'jsssc');
        }
        if ($msg3_cont != "" && $jssscdjs == $msg3) {
            管理员喊话($msg3_cont, $roomid, 'jsssc');
        }
    }
}

function senddata($roomid, $type, $game)
{

    $term = get_query_val('fn_open', 'next_term', "`type` = {$type} order by `id` desc limit 1");
    select_query('fn_order', '*', "`roomid` = '{$roomid}' and `type` = {$type} and `term`='{$term}'");

    $data = [];
    $array = [];
    while ($con = db_fetch_array()) {
        if ($con['status'] != '已撤单') {
            if (!isset($data[$con['username']]['sum'])) {
                $data[$con['username']]['sum'] = 0;
            }
            $data[$con['username']]['sum'] += $con['money'];
            $con['content'] = ((is_numeric($con['content']) && $con['content']==0)?10:$con['content']);
            $data[$con['username']]['data'][$con['mingci']][] = $con;

            //$array[$con['mingci']][] = $con;
        }
    }
    $txt = "竞猜列表核对:<br>{$term}期有效投注<br>";
    foreach ($data as $key => $v) {
        $txt .= "<br>[{$key}]汇总:{$v['sum']}<br>";
        $d = $v['data'];
        $txt .= getcontent(isset($d['1']) ? $d['1'] : [], '冠军') . "
" . getcontent(isset($d['和']) ? $d['和'] : [], '冠亚') . "
" . getcontent(isset($d['2']) ? $d['2'] : [], '亚军') . "
" . getcontent(isset($d['3']) ? $d['3'] : [], '第三名') . "
" . getcontent(isset($d['4']) ? $d['4'] : [], '第四名') . "
" . getcontent(isset($d['5']) ? $d['5'] : [], '第五名') . "
" . getcontent(isset($d['6']) ? $d['6'] : [], '第六名') . "
" . getcontent(isset($d['7']) ? $d['7'] : [], '第七名') . "
" . getcontent(isset($d['8']) ? $d['8'] : [], '第八名') . "
" . getcontent(isset($d['9']) ? $d['9'] : [], '第九名') . "
" . getcontent(isset($d['0']) ? $d['0'] : [], '第十名');
    }
    管理员喊话($txt, $roomid, $game);

    select_query('fn_order', '*', "`roomid` = '{$roomid}' and `jia`='false' and `type` = {$type} and `term`='{$term}'");


    $array = [];
    while ($con = db_fetch_array()) {
        if ($con['status'] != '已撤单') {

            $array[$con['mingci']][] = $con;
        }
    }


    postapi($term, $game, $array);
}

function getcontent($data, $name)
{
    $array = [];
    foreach ($data as $val) {
        if (!isset($array[$val['content']])) {
            $array[$val['content']] = 0;
        }
        $array[$val['content']] += $val['money'];
    }
    $text = '';

    foreach ($array as $key => $v) {
        $text .= $key . '/' . $v . ' ';
    }
    if (!empty($text)) {
        $text = $name . "[{$text}]<br>";
    }

    return $text;
}

function apidata($datas)
{
    //{"MC":"冠军","CD":"5","JE":"100"}
    /*MC  车道
CD   号码
JE    金额*/
    $data = [];

    $d = ['1' => '冠军', '和' => '冠亚', '2' => '亚军', '3' => '第三名', '4' => '第四名', '5' => '第五名', '6' => '第六名', '7' => '第七名', '8' => '第八名', '9' => '第九名', '0' => '第十名'];
    foreach ($d as $key => $val) {
        if (isset($datas[$key])) {
            foreach ($datas[$key] as $v) {
                $data[] = [
                    'MC' => $val,
                    'CD' => $v['content'],
                    'JE' => $v['money']
                ];
            }
        }
    }
    return $data;
}

function postapi($term, $game, $datas)
{
    $games = [];
    $games['xy28'] = '1110';
    $games['cqssc'] = '1120';
    $games['cqssc'] = '1130';
    $games['pk10'] = '1140';

    if (isset($games[$game])) {
        $data = [];
        $data['lotCode'] = $games[$game];
        $data['preDrawIssue'] = $term;
        $data['data'] = apidata($datas);

        if (!empty($data['data'])) {

//json也可以
            $data_string = json_encode($data);

            // file_put_contents(__DIR__."/log.txt", $data_string.PHP_EOL, FILE_APPEND);
//普通数组也行
//$data_string = $arr;

            //echo $data_string;
//echo '<br>';

//curl验证成功
            $url = "http://103.214.168.117:7000/?password=" . API_PASSWORD;

            //$data_string = json_encode($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
            );

            $result = curl_exec($ch);
        }
    }


}