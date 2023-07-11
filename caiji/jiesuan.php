<?php

function bukai($gameid , $qihao , $next_qihao){
    select_query('fn_order' , '*' ,"`type` = {$gameid} and term != '{$qihao}' and term != '{$next_qihao}' and status = '未结算' group by term order by term asc");
    $list = [];
    while($con = db_fetch_array()){
        $list[] = $con;
    }
    foreach ($list as $val){
        $term = $val['term']-1;
        $last = get_query_vals('fn_open' , '*' , "`type` = {$gameid} and term = {$term}");
        if($last){
            $array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10");
            shuffle($array);
            $opennum = "";
            for($i=0; $i<10; $i++){
                $opennum .= $array[$i];
                if($i!=9)
                    $opennum .= ",";
            }
            $opencode = $opennum;
            insert_query('fn_open', array('term' => $val['term'], 'code' => $opencode, 'time' => $last['next_time'], 'type' => $gameid, 'next_term' => $val['term'] + 1, 'next_time' => date('Y-m-d H:i:s',strtotime($last['next_time']) + 60 - 5)));
            jiesuan();
        }
    }
}
function curlRequest2($url, $data, $header = [], $cookie = '', $forcePost = false, $autoCookieType = 0, $cookieFile = '')
{
    //$url = 'http://localhost/3.php';
    $opt_data = is_array($data) ? http_build_query($data) : $data;
    $headerDefault = [];
    $sendHeader = array_merge($headerDefault, $header);
    $curl = curl_init();  //初始化
    curl_setopt($curl, CURLOPT_URL, $url);  //设置url
    //curl_setopt($curl,CURLOPT_HTTPAUTH,);  //设置http验证方法
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $sendHeader);//设置头信息
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  //设置curl_exec获取的信息的返回方式
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    //curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_TIMEOUT, 10); // 设置超时限制防止死循环
    if (!empty($opt_data)) {
        curl_setopt($curl, CURLOPT_POST, 1);  //设置发送方式为post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $opt_data);  //设置post的数据
    } elseif ($forcePost) {
        curl_setopt($curl, CURLOPT_POST, 1);  //设置发送方式为post请求
    }
    if ($autoCookieType == 1) {//自动保存
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFile);//把返回来的cookie信息保存在$cookie_jar文件中
    } elseif ($autoCookieType == 2) {//自动发送
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFile);
    } elseif ($autoCookieType == 3) {//自动保存和发送
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFile);//把返回来的cookie信息保存在$cookie_jar文件中
    }


    if ($cookie) curl_setopt($curl, CURLOPT_COOKIE, $cookie);  //设置post的数据

    if (defined('CURL_DEBUG') && CURL_DEBUG) curl_setopt($curl, CURLINFO_HEADER_OUT, true);

    $result = curl_exec($curl);

    if (defined('CURL_DEBUG') && CURL_DEBUG) echo curl_getinfo($curl, CURLINFO_HEADER_OUT);

    if ($result === false) {
        throw new Exception("抛出异常:访问网址=>" . $url . '@@@' . curl_error($curl) . '=>' . curl_errno($curl));
        return;
    }
    curl_close($curl);
    return $result;
}

function getOpenInfoCurl($url)
{
    //$url='http://39.109.123.184/?lotCode=1110';
    $header = [];
    $header[] = 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
    $str = curlRequest2($url, '', $header, '');
    $info = json_decode($str, true);
    //print_r($info);
    return $info;
}

function 用户_上分($Userid, $Money, $room, $game, $term, $content , $type = '' , &$zhongjian_list = [])
{
    if($type == 'updatemoney' || $type == ''){
        update_query('fn_user', array('money' => '-=' . $Money), array('userid' => $Userid, 'roomid' => $room));
    }
    if($type == 'log' || $type == ''){
        $gamelist = getGameList();
        $gamelist = array_flip($gamelist);
        if(getGameCodeById($gamelist[$game]) != 'jnd28'){
            $content = explode('/' , $content);
            $content[0] = ((is_numeric($content[0]) && $content[0]==0)?10:$content[0]);
            $content[1] = ((is_numeric($content[1]) && $content[1]==0)?10:$content[1]);
            //$content[1] = (is_numeric($content[1]))?('第' . $content[1] . '车道'):$content[1];
            switch ($content[0]){
                case 1:
                    $content[0] = '冠军';
                    break;
                case 2:
                    $content[0] = '亚军';
                    break;
                case 3:
                    $content[0] = '第三名';
                    break;
                case 4:
                    $content[0] = '第四名';
                    break;
                case 5:
                    $content[0] = '第五名';
                    break;
                case 6:
                    $content[0] = '第六名';
                    break;
                case 7:
                    $content[0] = '第七名';
                    break;
                case 8:
                    $content[0] = '第八名';
                    break;
                case 9:
                    $content[0] = '第九名';
                    break;
                case 10:
                    $content[0] = '第十名';
                    break;
                case '和':
                    $content[0] = '冠亚和';
                    break;
            }
            $content = implode('/' , $content);
        }
        if(isset($zhongjian_list[$Userid][$room])){
            $zhongjian_list[$Userid][$room] += $Money;
        }else{
            $zhongjian_list[$Userid][$room] = $Money;
        }
        insert_query("fn_marklog", array("userid" => $Userid, 'type' => '派奖','game' => getGameCodeById($gamelist[$game]), 'content' => $term . '期' . $game . '中奖派彩' . $content, 'money' => $Money, 'roomid' => $room, 'addtime' => 'now()'));
    }
}

function PC_jiesuan()
{
    select_query("fn_order", '*', array("status" => "未结算"), array('type' => '5'));
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    foreach ($cons as $con) {
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        // if ((int)$term > 2000000) {
        $openType = 5;
        $game = '加拿大28';
        // } else {
        //     $openType = 4;
        //     $game = '幸运28';
        // }

        $zym_9 = (int)get_query_val('fn_order', 'sum(`money`)', array('roomid' => $roomid, 'term' => $term, 'userid' => $user));
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '$openType'");

        if ($opencode == ""){
            continue;
        }
        $codes = explode(',', $opencode);
        // var_dump($term);

        if (count($codes) != 3) {
            echo 'ERROR!';
            exit();
        } else {
            // if ($openType == 4) {
            //     $number1 = (int)$codes[0] + (int)$codes[1] + (int)$codes[2] + (int)$codes[3] + (int)$codes[4] + (int)$codes[5];
            //     $number2 = (int)$codes[6] + (int)$codes[7] + (int)$codes[8] + (int)$codes[9] + (int)$codes[10] + (int)$codes[11];
            //     $number3 = (int)$codes[12] + (int)$codes[13] + (int)$codes[14] + (int)$codes[15] + (int)$codes[16] + (int)$codes[17];
            //     $number1 = substr($number1, -1);
            //     $number2 = substr($number2, -1);
            //     $number3 = substr($number3, -1);
            //     $hz = (int)$number1 + (int)$number2 + (int)$number3;
            // } elseif ($openType == 5) {
            $number1 = $codes[0];
            $number2 = $codes[1];
            $number3 = $codes[2];
            $hz = $number1 + $number2 + $number3;
            // }
            //     var_dump($number1);
            //     var_dump($number2);
            // die;
        }

        if ($number1 == $number2 && $number2 == $number3) {
            $zym_10 = true;//豹子
        }
        if ($number1 == $number2 || $number2 == $number3 || $number1 == $number3) {
            if (!$zym_10) {
                $zym_6 = true;//对子
            }
        }
        if ($number1 + 1 == $number2 && $number2 + 1 == $number3 || $number1 - 1 == $number2 && $number2 - 1 == $number3) {
            $zym_5 = true;//顺子
        }
        if ($zym_8 == '大' || $zym_8 == '小' || $zym_8 == '单' || $zym_8 == '双') {
            $field = 'dxds';
            $peilv = get_query_val('fn_lottery' . $openType, 'dxds', "`roomid` = '$roomid'");
            if ($hz == 13 || $hz == 14) {
                $dxds_zongzhu1 = get_query_val('fn_lottery' . $openType, 'dxds_zongzhu1', array('roomid' => $roomid));
                $dxds_zongzhu2 = get_query_val('fn_lottery' . $openType, 'dxds_zongzhu2', array('roomid' => $roomid));
                $dxds_zongzhu3 = get_query_val('fn_lottery' . $openType, 'dxds_zongzhu3', array('roomid' => $roomid));
                $dxds_1314_1 = get_query_val('fn_lottery' . $openType, 'dxds_1314_1', array('roomid' => $roomid));
                $dxds_1314_2 = get_query_val('fn_lottery' . $openType, 'dxds_1314_2', array('roomid' => $roomid));
                $dxds_1314_3 = get_query_val('fn_lottery' . $openType, 'dxds_1314_3', array('roomid' => $roomid));
                if ($dxds_zongzhu1 != "") {
                    if ($zym_9 > (int)$dxds_zongzhu1) {
                        $peilv = $dxds_1314_1;
                        $field = 'dxds_1314_1';
                    }
                }
                if ($dxds_zongzhu2 != "") {
                    if ($zym_9 > (int)$dxds_zongzhu2) {
                        $peilv = $dxds_1314_2;;
                        $field = 'dxds_1314_2';
                    }
                }
                if ($dxds_zongzhu3 != "") {
                    if ($zym_9 > (int)$dxds_zongzhu3) {
                        $peilv = $dxds_1314_3;
                        $field = 'dxds_1314_3';
                    }
                }
            }
            if ($zym_8 == '大' && $hz > 13) {

                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , $field);
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);

                continue;
            } elseif ($zym_8 == '小' && $hz < 14) {
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , $field);
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($zym_8 == '单' && $hz % 2 != 0) {
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , $field);
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($zym_8 == '双' && $hz % 2 == 0) {
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , $field);
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_8 == '极大' || $zym_8 == '极小') {
            if ($zym_8 == '极大' && $hz > 21) {
                $peilv = get_query_val('fn_lottery' . $openType, 'jida', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , 'jida');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($zym_8 == '极小' && $hz < 6) {
                $peilv = get_query_val('fn_lottery' . $openType, 'jixiao', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , 'jixiao');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_8 == '大单' || $zym_8 == '大双' || $zym_8 == '小单' || $zym_8 == '小双') {
            if ($zym_8 == '大单' && $hz > 13 && $hz % 2 != 0) {
                $peilv = get_query_val('fn_lottery' . $openType, 'dadan', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , 'dadan');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($zym_8 == '小单' && $hz < 14 && $hz % 2 != 0) {
                $field = 'xiaodan';
                $peilv = get_query_val('fn_lottery' . $openType, 'xiaodan', "`roomid` = '$roomid'");
                if ($hz == 13) {
                    $zuhe_zongzhu1 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu1', array('roomid' => $roomid));
                    $zuhe_zongzhu2 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu2', array('roomid' => $roomid));
                    $zuhe_zongzhu3 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu3', array('roomid' => $roomid));
                    $zuhe_1314_1 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_1', array('roomid' => $roomid));
                    $zuhe_1314_2 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_2', array('roomid' => $roomid));
                    $zuhe_1314_3 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_3', array('roomid' => $roomid));
                    if ($zuhe_zongzhu1 != "") {
                        if ($zym_9 > (int)$zuhe_zongzhu1) {
                            $peilv = $zuhe_1314_1;
                            $field = 'zuhe_1314_1';
                        }
                    }
                    if ($zuhe_zongzhu2 != "") {
                        if ($zym_9 > (int)$zuhe_zongzhu2) {
                            $peilv = $zuhe_1314_2;
                            $field = 'zuhe_1314_2';
                        }
                    }
                    if ($zuhe_zongzhu3 != "") {
                        if ($zym_9 > (int)$zuhe_zongzhu3) {
                            $peilv = $zuhe_1314_3;
                            $field = 'zuhe_1314_3';
                        }
                    }
                }
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , $field);
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($zym_8 == '大双' && $hz > 13 && $hz % 2 == 0) {
                $field = 'dashuang';
                $peilv = get_query_val('fn_lottery' . $openType, 'dashuang', "`roomid` = '$roomid'");
                if ($hz == 14) {
                    $zuhe_zongzhu1 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu1', array('roomid' => $roomid));
                    $zuhe_zongzhu2 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu2', array('roomid' => $roomid));
                    $zuhe_zongzhu3 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu3', array('roomid' => $roomid));
                    $zuhe_1314_1 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_1', array('roomid' => $roomid));
                    $zuhe_1314_2 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_2', array('roomid' => $roomid));
                    $zuhe_1314_3 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_3', array('roomid' => $roomid));
                    if ($zuhe_zongzhu1 != "") {
                        if ($zym_9 > (int)$zuhe_zongzhu1) {
                            $peilv = $zuhe_1314_1;
                            $field = 'zuhe_1314_1';
                        }
                    }
                    if ($zuhe_zongzhu2 != "") {
                        if ($zym_9 > (int)$zuhe_zongzhu2) {
                            $peilv = $zuhe_1314_2;
                            $field = 'zuhe_1314_1';
                        }
                    }
                    if ($zuhe_zongzhu3 != "") {
                        if ($zym_9 > (int)$zuhe_zongzhu3) {
                            $peilv = $zuhe_1314_3;
                            $field = 'zuhe_1314_1';
                        }
                    }
                }
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , $field);
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($zym_8 == '小双' && $hz < 14 && $hz % 2 == 0) {
                $peilv = get_query_val('fn_lottery' . $openType, 'xiaoshuang', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , 'xiaoshuang');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_8 == '豹子' || $zym_8 == '对子' || $zym_8 == '顺子') {
            if ($zym_8 == '豹子' && $zym_10 == true) {
                $peilv = get_query_val('fn_lottery' . $openType, 'baozi', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , 'baozi');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($zym_8 == '对子' && $zym_6 == true) {
                $peilv = get_query_val('fn_lottery' . $openType, 'duizi', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , 'duizi');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($zym_8 == '顺子' && $zym_5 == true) {
                $peilv = get_query_val('fn_lottery' . $openType, 'shunzi', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , 'shunzi');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ((int)$zym_8 == $hz) {
            if ($hz == 0 || $hz == 27) {
                $peilv = get_query_val('fn_lottery' . $openType, '`0027`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '0027');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($hz == 1 || $hz == 26) {
                $peilv = get_query_val('fn_lottery' . $openType, '`0126`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '0126');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($hz == 2 || $hz == 25) {
                $peilv = get_query_val('fn_lottery' . $openType, '`0225`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '0225');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($hz == 3 || $hz == 24) {
                $peilv = get_query_val('fn_lottery' . $openType, '`0324`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '0324');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($hz == 4 || $hz == 23) {
                $peilv = get_query_val('fn_lottery' . $openType, '`0423`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '0423');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($hz == 5 || $hz == 22) {
                $peilv = get_query_val('fn_lottery' . $openType, '`0522`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '0522');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($hz == 6 || $hz == 21) {
                $peilv = get_query_val('fn_lottery' . $openType, '`0621`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '0621');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($hz == 7 || $hz == 20) {
                $peilv = get_query_val('fn_lottery' . $openType, '`0720`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '0720');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($hz == 8 || $hz == 9 || $hz == 18 || $hz == 19) {
                $peilv = get_query_val('fn_lottery' . $openType, '`891819`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '891819');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($hz == 10 || $hz == 11 || $hz == 16 || $hz == 17) {
                $peilv = get_query_val('fn_lottery' . $openType, '`10111617`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '10111617');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($hz == 12 || $hz == 15) {
                $peilv = get_query_val('fn_lottery' . $openType, '`1215`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '1215');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } elseif ($hz == 13 || $hz == 14) {
                $peilv = get_query_val('fn_lottery' . $openType, '`1314`', "`roomid` = '$roomid'");
                $peilv_new = getNewPeilv($user , $roomid , $openType , $peilv , '1314');
                $zym_11 = $peilv_new * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term,  $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                peilvfandian($user , $user , $roomid , $openType , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        } else {
            $zym_11 = '-' . $zym_7;
            update_query("fn_order", array("status" => $zym_11), array('id' => $id));
            continue;
        }
    }
}

function SSC_jiesuan()
{
    select_query("fn_sscorder", '*', array("status" => "未结算"));
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    foreach ($cons as $con) {
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_1 = $con['mingci'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        $game = '重庆时时彩';
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '3'");
        if ($opencode == "") continue;
        $codes = explode(',', $opencode);
        if (count($codes) != 5) {
            echo 'ERROR!';
            exit();
        } else {
            $zym_2 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $q3 = array((int)$codes[0], (int)$codes[1], (int)$codes[2]);
            sort($q3);
            $zym_3 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $z3 = array((int)$codes[1], (int)$codes[2], (int)$codes[3]);
            sort($z3);
            $zym_4 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $h3 = array((int)$codes[2], (int)$codes[3], (int)$codes[4]);
            sort($h3);
            if ($codes[0] == $codes[1] && $codes[1] == $codes[2]) {
                $zym_2['豹子'] = true;
            } elseif ($codes[0] == $codes[1] || $codes[0] == $codes[2] || $codes[1] == $codes[2]) {
                $zym_2['对子'] = true;
            } elseif (($q3[0] + 1 == $q3[1] && $q3[1] + 1 == $q3[2]) || ($q3[0] == '0' && $q3[1] == '8' && $q3[2] == '9') || ($q3[0] == '0' && $q3[1] == '1' && $q3[2] == '9')) {
                $zym_2['顺子'] = true;
            } elseif (($q3[0] + 1 == $q3[1] || $q3[1] + 1 == $q3[2]) || ($q3[0] == '0' && $q3[2] == '9')) {
                $zym_2['半顺'] = true;
            } else {
                $zym_2['杂六'] = true;
            }
            if ($codes[1] == $codes[2] && $codes[2] == $codes[3]) {
                $zym_3['豹子'] = true;
            } elseif ($codes[1] == $codes[2] || $codes[1] == $codes[3] || $codes[2] == $codes[3]) {
                $zym_3['对子'] = true;
            } elseif (($z3[0] + 1 == $z3[1] && $z3[1] + 1 == $z3[2]) || ($z3[0] == '0' && $z3[1] == '8' && $z3[2] == '9') || ($z3[0] == '0' && $z3[1] == '1' && $z3[2] == '9')) {
                $zym_3['顺子'] = true;
            } elseif (($z3[0] + 1 == $z3[1] || $z3[1] + 1 == $z3[2]) || ($z3[0] == '0' && $z3[2] == '9')) {
                $zym_3['半顺'] = true;
            } else {
                $zym_3['杂六'] = true;
            }
            if ($codes[2] == $codes[3] && $codes[3] == $codes[4]) {
                $zym_4['豹子'] = true;
            } elseif ($codes[2] == $codes[3] || $codes[2] == $codes[4] || $codes[3] == $codes[4]) {
                $zym_4['对子'] = true;
            } elseif (($h3[0] + 1 == $h3[1] && $h3[1] + 1 == $h3[2]) || ($h3[0] == '0' && $h3[1] == '8' && $h3[2] == '9') || ($h3[0] == '0' && $h3[1] == '1' && $h3[2] == '9')) {
                $zym_4['顺子'] = true;
            } elseif (($h3[0] + 1 == $h3[1] || $h3[1] + 1 == $h3[2]) || ($h3[0] == '0' && $h3[2] == '9')) {
                $zym_4['半顺'] = true;
            } else {
                $zym_4['杂六'] = true;
            }
            var_dump($zym_4);
            $zong = (int)$codes[0] + (int)$codes[1] + (int)$codes[2] + (int)$codes[3] + (int)$codes[4];
        }
        if ($zym_1 == '总') {
            if ($zym_8 == '大' && $zong > 22) {
                $peilv = get_query_val('fn_lottery3', 'zongda', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '小' && $zong < 23) {
                $peilv = get_query_val('fn_lottery3', 'zongxiao', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '单' && $zong % 2 != 0) {
                $peilv = get_query_val('fn_lottery3', 'zongdan', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '双' && $zong % 2 == 0) {
                $peilv = get_query_val('fn_lottery3', 'zongshuang', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '龙' && $codes[0] > $codes[4]) {
                $peilv = get_query_val('fn_lottery3', '`long`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '虎' && $codes[0] < $codes[4]) {
                $peilv = get_query_val('fn_lottery3', 'hu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '和' && $codes[0] == $codes[4]) {
                $peilv = get_query_val('fn_lottery3', 'he', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if ($zym_1 == '前三') {
            if ($zym_8 == '豹子' && $zym_2['豹子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'q_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '顺子' && $zym_2['顺子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'q_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '对子' && $zym_2['对子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'q_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '半顺' && $zym_2['半顺'] == true) {
                $peilv = get_query_val('fn_lottery3', 'q_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '杂六' && $zym_2['杂六'] == true) {
                $peilv = get_query_val('fn_lottery3', 'q_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if ($zym_1 == '中三') {
            if ($zym_8 == '豹子' && $zym_3['豹子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'z_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '顺子' && $zym_3['顺子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'z_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '对子' && $zym_3['对子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'z_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '半顺' && $zym_3['半顺'] == true) {
                $peilv = get_query_val('fn_lottery3', 'z_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '杂六' && $zym_3['杂六'] == true) {
                $peilv = get_query_val('fn_lottery3', 'z_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if ($zym_1 == '后三') {
            if ($zym_8 == '豹子' && $zym_4['豹子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'h_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '顺子' && $zym_4['顺子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'h_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '对子' && $zym_4['对子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'h_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '半顺' && $zym_4['半顺'] == true) {
                $peilv = get_query_val('fn_lottery3', 'h_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '杂六' && $zym_4['杂六'] == true) {
                $peilv = get_query_val('fn_lottery3', 'h_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if ((int)$zym_1 > 0) {
            $count = (int)$zym_1 - 1;
            if ($zym_8 == '大' && (int)$codes[$count] > 4) {
                $peilv = get_query_val('fn_lottery3', 'da', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '小' && (int)$codes[$count] < 5) {
                $peilv = get_query_val('fn_lottery3', 'xiao', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '单' && (int)$codes[$count] % 2 != 0) {
                $peilv = get_query_val('fn_lottery3', 'dan', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '双' && (int)$codes[$count] % 2 == 0) {
                $peilv = get_query_val('fn_lottery3', 'shuang', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == $codes[$count]) {
                $peilv = get_query_val('fn_lottery3', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
    }
}

function JSSSC_jiesuan()
{
    select_query("fn_jssscorder", '*', array("status" => "未结算"));
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    foreach ($cons as $con) {
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_1 = $con['mingci'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        $game = '极速时时彩';
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '8'");
        if ($opencode == "") continue;
        $codes = explode(',', $opencode);
        if (count($codes) != 5) {
            echo 'ERROR!';
            exit();
        } else {
            $zym_2 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $q3 = array((int)$codes[0], (int)$codes[1], (int)$codes[2]);
            sort($q3);
            $zym_3 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $z3 = array((int)$codes[1], (int)$codes[2], (int)$codes[3]);
            sort($z3);
            $zym_4 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $h3 = array((int)$codes[2], (int)$codes[3], (int)$codes[4]);
            sort($h3);
            if ($codes[0] == $codes[1] && $codes[1] == $codes[2]) {
                $zym_2['豹子'] = true;
            } elseif ($codes[0] == $codes[1] || $codes[0] == $codes[2] || $codes[1] == $codes[2]) {
                $zym_2['对子'] = true;
            } elseif (($q3[0] + 1 == $q3[1] && $q3[1] + 1 == $q3[2]) || ($q3[0] == '0' && $q3[1] == '8' && $q3[2] == '9') || ($q3[0] == '0' && $q3[1] == '1' && $q3[2] == '9')) {
                $zym_2['顺子'] = true;
            } elseif (($q3[0] + 1 == $q3[1] || $q3[1] + 1 == $q3[2]) || ($q3[0] == '0' && $q3[2] == '9')) {
                $zym_2['半顺'] = true;
            } else {
                $zym_2['杂六'] = true;
            }
            if ($codes[1] == $codes[2] && $codes[2] == $codes[3]) {
                $zym_3['豹子'] = true;
            } elseif ($codes[1] == $codes[2] || $codes[1] == $codes[3] || $codes[2] == $codes[3]) {
                $zym_3['对子'] = true;
            } elseif (($z3[0] + 1 == $z3[1] && $z3[1] + 1 == $z3[2]) || ($z3[0] == '0' && $z3[1] == '8' && $z3[2] == '9') || ($z3[0] == '0' && $z3[1] == '1' && $z3[2] == '9')) {
                $zym_3['顺子'] = true;
            } elseif (($z3[0] + 1 == $z3[1] || $z3[1] + 1 == $z3[2]) || ($z3[0] == '0' && $z3[2] == '9')) {
                $zym_3['半顺'] = true;
            } else {
                $zym_3['杂六'] = true;
            }
            if ($codes[2] == $codes[3] && $codes[3] == $codes[4]) {
                $zym_4['豹子'] = true;
            } elseif ($codes[2] == $codes[3] || $codes[2] == $codes[4] || $codes[3] == $codes[4]) {
                $zym_4['对子'] = true;
            } elseif (($h3[0] + 1 == $h3[1] && $h3[1] + 1 == $h3[2]) || ($h3[0] == '0' && $h3[1] == '8' && $h3[2] == '9') || ($h3[0] == '0' && $h3[1] == '1' && $h3[2] == '9')) {
                $zym_4['顺子'] = true;
            } elseif (($h3[0] + 1 == $h3[1] || $h3[1] + 1 == $h3[2]) || ($h3[0] == '0' && $h3[2] == '9')) {
                $zym_4['半顺'] = true;
            } else {
                $zym_4['杂六'] = true;
            }
            $zong = (int)$codes[0] + (int)$codes[1] + (int)$codes[2] + (int)$codes[3] + (int)$codes[4];
        }
        if ($zym_1 == '总') {
            if ($zym_8 == '大' && $zong > 22) {
                $peilv = get_query_val('fn_lottery3', 'zongda', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '小' && $zong < 23) {
                $peilv = get_query_val('fn_lottery3', 'zongxiao', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '单' && $zong % 2 != 0) {
                $peilv = get_query_val('fn_lottery3', 'zongdan', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '双' && $zong % 2 == 0) {
                $peilv = get_query_val('fn_lottery3', 'zongshuang', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '龙' && $codes[0] > $codes[4]) {
                $peilv = get_query_val('fn_lottery3', '`long`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '虎' && $codes[0] < $codes[4]) {
                $peilv = get_query_val('fn_lottery3', 'hu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '和' && $codes[0] == $codes[4]) {
                $peilv = get_query_val('fn_lottery3', 'he', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if ($zym_1 == '前三') {
            if ($zym_8 == '豹子' && $zym_2['豹子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'q_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '顺子' && $zym_2['顺子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'q_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '对子' && $zym_2['对子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'q_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '半顺' && $zym_2['半顺'] == true) {
                $peilv = get_query_val('fn_lottery3', 'q_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '杂六' && $zym_2['杂六'] == true) {
                $peilv = get_query_val('fn_lottery3', 'q_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if ($zym_1 == '中三') {
            if ($zym_8 == '豹子' && $zym_3['豹子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'z_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '顺子' && $zym_3['顺子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'z_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '对子' && $zym_3['对子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'z_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '半顺' && $zym_3['半顺'] == true) {
                $peilv = get_query_val('fn_lottery3', 'z_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '杂六' && $zym_3['杂六'] == true) {
                $peilv = get_query_val('fn_lottery3', 'z_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if ($zym_1 == '后三') {
            if ($zym_8 == '豹子' && $zym_4['豹子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'h_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '顺子' && $zym_4['顺子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'h_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '对子' && $zym_4['对子'] == true) {
                $peilv = get_query_val('fn_lottery3', 'h_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '半顺' && $zym_4['半顺'] == true) {
                $peilv = get_query_val('fn_lottery3', 'h_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '杂六' && $zym_4['杂六'] == true) {
                $peilv = get_query_val('fn_lottery3', 'h_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if ((int)$zym_1 > 0) {
            $count = (int)$zym_1 - 1;
            if ($zym_8 == '大' && (int)$codes[$count] > 4) {
                $peilv = get_query_val('fn_lottery3', 'da', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '小' && (int)$codes[$count] < 5) {
                $peilv = get_query_val('fn_lottery3', 'xiao', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '单' && (int)$codes[$count] % 2 != 0) {
                $peilv = get_query_val('fn_lottery3', 'dan', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == '双' && (int)$codes[$count] % 2 == 0) {
                $peilv = get_query_val('fn_lottery3', 'shuang', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } elseif ($zym_8 == $codes[$count]) {
                $peilv = get_query_val('fn_lottery3', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
    }
}

function jiesuan($typeid = '' , $qihao = '')
{
    global $gmidAli;
    select_query("fn_order", '*', array("status" => "未结算",'type'=>$typeid,'term'=>$qihao ));
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    $zhongjian_list = [];
    foreach ($cons as $con) {
        $order_pielv = $con['peilv'];
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_1 = $con['mingci'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        $gameTypeNum = $con['type'];

        $table = 'fn_lottery' . $gameTypeNum;
        $game = getGameTxtName($gameTypeNum);
        $gametype = $gameTypeNum;
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '$gametype'");
        $opencode = str_replace('10', '0', $opencode);
        if ($opencode == "") continue;
        $code = explode(',', $opencode);
        if ($zym_1 == '1') {
            if ($zym_8 == '大') {
//                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if ((int)$code[0] > 5 || $code[0] == '0') {
                    //$peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'da');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 , 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    //peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
//                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[0] < 6 && $code[0] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiao');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
//                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 != 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
//                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'shuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
//                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 != 0 && (int)$code[0] > 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'dadan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
//                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 == 0 && (int)$code[0] > 5 || (int)$code[0] == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'dashuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
//                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 == 0 && (int)$code[0] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaoshuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
//                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 != 0 && (int)$code[0] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaodan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
//                $peilv = get_query_val($table, '`long`', "`roomid` = '$roomid'");
                if ((int)$code[0] > (int)$code[9] && $code[9] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'long');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($code[0] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'long');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
//                $peilv = get_query_val($table, 'hu', "`roomid` = '$roomid'");
                if ((int)$code[0] < (int)$code[9] && $code[0] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'hu');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($code[9] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'hu');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[0]) {
//                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
//                $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'tema');
                $zym_11 = $order_pielv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '2') {
            if ($zym_8 == '大') {
//                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if ((int)$code[1] > 5 || $code[1] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'da');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
//                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[1] < 6 && $code[1] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'xiao');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
//                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 != 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'dan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
//                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'shuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
//                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 != 0 && (int)$code[1] > 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'dadan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 == 0 && (int)$code[1] > 5 || (int)$code[1] == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'dashuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 , 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
//                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 == 0 && (int)$code[1] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'xiaoshuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
//                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 != 0 && (int)$code[1] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'xiaodan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
//                $peilv = get_query_val($table, '`long`', "`roomid` = '$roomid'");
                if ((int)$code[1] > (int)$code[8] && $code[8] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'long');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($code[1] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'long');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
//                $peilv = get_query_val($table, 'hu', "`roomid` = '$roomid'");
                if ((int)$code[1] < (int)$code[8] && $code[1] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'hu');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($code[8] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'hu');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[1]) {
//                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
//                $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'tema');
                $zym_11 = $order_pielv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '3') {
            if ($zym_8 == '大') {
//                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if ((int)$code[2] > 5 || $code[2] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'da');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
//                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[2] < 6 && $code[2] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv, 'xiao');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
//                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 != 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
//                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'shuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
//                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 != 0 && (int)$code[2] > 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dadan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
//                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 == 0 && (int)$code[2] > 5 || (int)$code[2] == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dashuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
//                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 == 0 && (int)$code[2] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaoshuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
//                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 != 0 && (int)$code[2] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaodan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
//                $peilv = get_query_val($table, '`long`', "`roomid` = '$roomid'");
                if ((int)$code[2] > (int)$code[7] && $code[7] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'long');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($code[2] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'long');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
//                $peilv = get_query_val($table, 'hu', "`roomid` = '$roomid'");
                if ((int)$code[2] < (int)$code[7] && $code[2] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'hu');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($code[7] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'hu');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7, 'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[2]) {
//                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
//                $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'tema');
                $zym_11 = $order_pielv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '4') {
            if ($zym_8 == '大') {
//                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if ((int)$code[3] > 5 || $code[3] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'da');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
//                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[3] < 6 && $code[3] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiao');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
//                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 != 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
//                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'shuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
//                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 != 0 && (int)$code[3] > 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dadan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
//                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 == 0 && (int)$code[3] > 5 || (int)$code[3] == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dashuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
//                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 == 0 && (int)$code[3] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaoshuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
//                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 != 0 && (int)$code[3] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaodan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
//                $peilv = get_query_val($table, '`long`', "`roomid` = '$roomid'");
                if ((int)$code[3] > (int)$code[6] && $code[6] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'long');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($code[3] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'long');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
//                $peilv = get_query_val($table, 'hu', "`roomid` = '$roomid'");
                if ((int)$code[3] < (int)$code[6] && $code[3] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'hu');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($code[6] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'hu');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[3]) {
//                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
//                $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'tema');
                $zym_11 = $order_pielv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '5') {
            if ($zym_8 == '大') {
//                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if ((int)$code[4] > 5 || $code[4] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'da');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
//                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[4] < 6 && $code[4] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiao');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
//                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 != 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
//                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'shuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
//                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 != 0 && (int)$code[4] > 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dadan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
//                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 == 0 && (int)$code[4] > 5 || (int)$code[4] == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dashuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
//                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 == 0 && (int)$code[4] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaoshuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
//                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 != 0 && (int)$code[4] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaodan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
//                $peilv = get_query_val($table, '`long`', "`roomid` = '$roomid'");
                if ((int)$code[4] > (int)$code[5] && $code[5] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'long');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($code[4] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'long');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
//                $peilv = get_query_val($table, 'hu', "`roomid` = '$roomid'");
                if ((int)$code[4] < (int)$code[5] && $code[4] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'hu');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($code[5] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'hu');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[4]) {
//                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
//                $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'tema');
                $zym_11 = $order_pielv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '6') {
            if ($zym_8 == '大') {
//                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if ((int)$code[5] > 5 || $code[5] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'da');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
//                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[5] < 6 && $code[5] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiao');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
//                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 != 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
//                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'shuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
//                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 != 0 && (int)$code[5] > 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dadan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
//                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 == 0 && (int)$code[5] > 5 || (int)$code[5] == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dashuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
//                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 == 0 && (int)$code[5] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaoshuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
//                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 != 0 && (int)$code[5] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaodan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[5]) {
//                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
//                $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'tema');
                $zym_11 = $order_pielv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '7') {
            if ($zym_8 == '大') {
//                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if ((int)$code[6] > 5 || $code[6] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'da');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
//                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[6] < 6 && $code[6] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiao');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
//                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 != 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
//                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'shuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
//                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 != 0 && (int)$code[6] > 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dadan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
//                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 == 0 && (int)$code[6] > 5 || (int)$code[6] == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dashuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
//                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 == 0 && (int)$code[6] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaoshuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
//                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 != 0 && (int)$code[6] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaodan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[6]) {
//                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
//                $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'tema');
                $zym_11 = $order_pielv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '8') {
            if ($zym_8 == '大') {
//                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if ((int)$code[7] > 5 || $code[7] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'da');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
//                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[7] < 6 && $code[7] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiao');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
//                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 != 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
//                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'shuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
//                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 != 0 && (int)$code[7] > 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dadan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
//                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 == 0 && (int)$code[7] > 5 || (int)$code[7] == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dashuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
//                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 == 0 && (int)$code[7] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaoshuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
//                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 != 0 && (int)$code[7] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaodan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[7]) {
//                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
//                $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'tema');
                $zym_11 = $order_pielv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '9') {
            if ($zym_8 == '大') {
//                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if ((int)$code[8] > 5 || $code[8] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'da');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
//                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[8] < 6 && $code[8] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiao');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
//                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 != 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
//                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'shuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
//                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 != 0 && (int)$code[8] > 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dadan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
//                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 == 0 && (int)$code[8] > 5 || (int)$code[8] == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dashuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
//                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 == 0 && (int)$code[8] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaoshuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
//                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 != 0 && (int)$code[8] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaodan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[8]) {
//                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
//                $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'tema');
                $zym_11 = $order_pielv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '0') {
            if ($zym_8 == '大') {
//                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if ((int)$code[9] > 5 || $code[9] == '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'da');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
//                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[9] < 6 && $code[9] != '0') {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiao');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
//                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 != 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
//                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'shuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
//                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 != 0 && (int)$code[9] > 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dadan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
//                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 == 0 && (int)$code[9] > 5 || (int)$code[9] == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'dashuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
//                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 == 0 && (int)$code[9] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaoshuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
//                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 != 0 && (int)$code[9] < 5) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'xiaodan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[9]) {
//                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
//                $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'tema');
                $zym_11 = $order_pielv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '和') {
            if ($code[0] == "0" || $code[1] == "0") {
                $hz = (int)$code[0] + (int)$code[1] + 10;
            } else {
                $hz = (int)$code[0] + (int)$code[1];
            }
            if ($zym_8 == '大') {
//                $peilv = get_query_val($table, 'heda', "`roomid` = '$roomid'");
                if ($hz > 11) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'heda');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
//                $peilv = get_query_val($table, 'hexiao', "`roomid` = '$roomid'");
                if ($hz < 12) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'hexiao');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
//                $peilv = get_query_val($table, 'hedan', "`roomid` = '$roomid'");
                if ($hz % 2 != 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'hedan');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
//                $peilv = get_query_val($table, 'heshuang', "`roomid` = '$roomid'");
                if ($hz % 2 == 0) {
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'heshuang');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ((int)$zym_8 == $hz) {
                if ($hz == 3 || $hz == 4 || $hz == 18 || $hz == 19) {
//                    $peilv = get_query_val($table, 'he341819', "`roomid` = '$roomid'");
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'he341819');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($hz == 5 || $hz == 6 || $hz == 16 || $hz == 17) {
//                    $peilv = get_query_val($table, 'he561617', "`roomid` = '$roomid'");
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'he561617');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($hz == 7 || $hz == 8 || $hz == 14 || $hz == 15) {
//                    $peilv = get_query_val($table, 'he781415', "`roomid` = '$roomid'");
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'he781415');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($hz == 9 || $hz == 10 || $hz == 12 || $hz == 13) {
//                    $peilv = get_query_val($table, 'he9101213', "`roomid` = '$roomid'");
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'he9101213');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                } elseif ($hz == 11) {
//                    $peilv = get_query_val($table, 'he11', "`roomid` = '$roomid'");
//                    $peilv_new = getNewPeilv($user , $roomid , $gametype , $peilv , 'he11');
                    $zym_11 = $order_pielv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7 ,'log' , $zhongjian_list);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
//                    peilvfandian($user , $user , $roomid , $gametype , ($peilv - $peilv_new) * (int)$zym_7);
                    continue;
                }
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
    }
    foreach ($zhongjian_list as $user_id => $value){
        foreach ($value as $roomid => $money){
            echo "用户{$user_id}中奖金额" . $money . "已到账<br>" . PHP_EOL;
            update_query('fn_user', array('money' => '+=' . $money), array('userid' => $user_id, 'roomid' => $roomid));
        }
    }
}

function MT_jiesuan()
{
    select_query("fn_mtorder", '*', array("status" => "未结算"));
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    foreach ($cons as $con) {
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_1 = $con['mingci'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '6'");
        $opencode = str_replace('10', '0', $opencode);
        if ($opencode == "") continue;
        $code = explode(',', $opencode);
        if ($zym_1 == '1') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if ((int)$code[0] > 5 || $code[0] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[0] < 6 && $code[0] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 != 0 && (int)$code[0] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 == 0 && (int)$code[0] > 5 || (int)$code[0] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 == 0 && (int)$code[0] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 != 0 && (int)$code[0] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
                $peilv = get_query_val('fn_lottery6', '`long`', "`roomid` = '$roomid'");
                if ((int)$code[0] > (int)$code[9] && $code[9] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[0] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
                $peilv = get_query_val('fn_lottery6', 'hu', "`roomid` = '$roomid'");
                if ((int)$code[0] < (int)$code[9] && $code[0] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[9] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[0]) {
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '2') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if ((int)$code[1] > 5 || $code[1] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[1] < 6 && $code[1] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 != 0 && (int)$code[1] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 == 0 && (int)$code[1] > 5 || (int)$code[1] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 == 0 && (int)$code[1] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 != 0 && (int)$code[1] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
                $peilv = get_query_val('fn_lottery6', '`long`', "`roomid` = '$roomid'");
                if ((int)$code[1] > (int)$code[8] && $code[8] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[1] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
                $peilv = get_query_val('fn_lottery6', 'hu', "`roomid` = '$roomid'");
                if ((int)$code[1] < (int)$code[8] && $code[1] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[8] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[1]) {
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '3') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if ((int)$code[2] > 5 || $code[2] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[2] < 6 && $code[2] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 != 0 && (int)$code[2] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 == 0 && (int)$code[2] > 5 || (int)$code[2] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 == 0 && (int)$code[2] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 != 0 && (int)$code[2] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
                $peilv = get_query_val('fn_lottery6', '`long`', "`roomid` = '$roomid'");
                if ((int)$code[2] > (int)$code[7] && $code[7] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[2] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
                $peilv = get_query_val('fn_lottery6', 'hu', "`roomid` = '$roomid'");
                if ((int)$code[2] < (int)$code[7] && $code[2] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[7] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[2]) {
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '4') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if ((int)$code[3] > 5 || $code[3] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[3] < 6 && $code[3] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 != 0 && (int)$code[3] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 == 0 && (int)$code[3] > 5 || (int)$code[3] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 == 0 && (int)$code[3] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 != 0 && (int)$code[3] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
                $peilv = get_query_val('fn_lottery6', '`long`', "`roomid` = '$roomid'");
                if ((int)$code[3] > (int)$code[6] && $code[6] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[3] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
                $peilv = get_query_val('fn_lottery6', 'hu', "`roomid` = '$roomid'");
                if ((int)$code[3] < (int)$code[6] && $code[3] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[6] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[3]) {
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '5') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if ((int)$code[4] > 5 || $code[4] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[4] < 6 && $code[4] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 != 0 && (int)$code[4] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 == 0 && (int)$code[4] > 5 || (int)$code[4] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 == 0 && (int)$code[4] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 != 0 && (int)$code[4] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
                $peilv = get_query_val('fn_lottery6', '`long`', "`roomid` = '$roomid'");
                if ((int)$code[4] > (int)$code[5] && $code[5] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[4] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
                $peilv = get_query_val('fn_lottery6', 'hu', "`roomid` = '$roomid'");
                if ((int)$code[4] < (int)$code[5] && $code[4] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[5] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[4]) {
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '6') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if ((int)$code[5] > 5 || $code[5] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[5] < 6 && $code[5] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 != 0 && (int)$code[5] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 == 0 && (int)$code[5] > 5 || (int)$code[5] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 == 0 && (int)$code[5] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 != 0 && (int)$code[5] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[5]) {
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '7') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if ((int)$code[6] > 5 || $code[6] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[6] < 6 && $code[6] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 != 0 && (int)$code[6] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 == 0 && (int)$code[6] > 5 || (int)$code[6] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 == 0 && (int)$code[6] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 != 0 && (int)$code[6] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[6]) {
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '8') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if ((int)$code[7] > 5 || $code[7] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[7] < 6 && $code[7] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 != 0 && (int)$code[7] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 == 0 && (int)$code[7] > 5 || (int)$code[7] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 == 0 && (int)$code[7] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 != 0 && (int)$code[7] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[7]) {
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '9') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if ((int)$code[8] > 5 || $code[8] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[8] < 6 && $code[8] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 != 0 && (int)$code[8] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 == 0 && (int)$code[8] > 5 || (int)$code[8] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 == 0 && (int)$code[8] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 != 0 && (int)$code[8] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[8]) {
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '0') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if ((int)$code[9] > 5 || $code[9] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[9] < 6 && $code[9] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 != 0 && (int)$code[9] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 == 0 && (int)$code[9] > 5 || (int)$code[9] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 == 0 && (int)$code[9] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 != 0 && (int)$code[9] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[9]) {
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '和') {
            if ($code[0] == "0" || $code[1] == "0") {
                $hz = (int)$code[0] + (int)$code[1] + 10;
            } else {
                $hz = (int)$code[0] + (int)$code[1];
            }
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery6', 'heda', "`roomid` = '$roomid'");
                if ($hz > 11) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery6', 'hexiao', "`roomid` = '$roomid'");
                if ($hz < 12) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery6', 'hedan', "`roomid` = '$roomid'");
                if ($hz % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery6', 'heshuang', "`roomid` = '$roomid'");
                if ($hz % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ((int)$zym_8 == $hz) {
                if ($hz == 3 || $hz == 4 || $hz == 18 || $hz == 19) {
                    $peilv = get_query_val('fn_lottery6', 'he341819', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($hz == 5 || $hz == 6 || $hz == 16 || $hz == 17) {
                    $peilv = get_query_val('fn_lottery6', 'he561617', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($hz == 7 || $hz == 8 || $hz == 14 || $hz == 15) {
                    $peilv = get_query_val('fn_lottery6', 'he781415', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($hz == 9 || $hz == 10 || $hz == 12 || $hz == 13) {
                    $peilv = get_query_val('fn_lottery6', 'he9101213', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($hz == 11) {
                    $peilv = get_query_val('fn_lottery6', 'he11', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
    }
}

function JSSC_jiesuan()
{
    select_query("fn_jsscorder", '*', array("status" => "未结算"));
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    foreach ($cons as $con) {
        echo $con['id'];
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_1 = $con['mingci'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '7'");
        $opencode = str_replace('10', '0', $opencode);
        if ($opencode == "") continue;
        $code = explode(',', $opencode);
        if ($zym_1 == '1') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if ((int)$code[0] > 5 || $code[0] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[0] < 6 && $code[0] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 != 0 && (int)$code[0] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 == 0 && (int)$code[0] > 5 || (int)$code[0] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 == 0 && (int)$code[0] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[0] % 2 != 0 && (int)$code[0] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
                $peilv = get_query_val('fn_lottery7', '`long`', "`roomid` = '$roomid'");
                if ((int)$code[0] > (int)$code[9] && $code[9] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[0] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
                $peilv = get_query_val('fn_lottery7', 'hu', "`roomid` = '$roomid'");
                if ((int)$code[0] < (int)$code[9] && $code[0] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[9] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[0]) {
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '2') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if ((int)$code[1] > 5 || $code[1] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[1] < 6 && $code[1] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 != 0 && (int)$code[1] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 == 0 && (int)$code[1] > 5 || (int)$code[1] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 == 0 && (int)$code[1] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[1] % 2 != 0 && (int)$code[1] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
                $peilv = get_query_val('fn_lottery7', '`long`', "`roomid` = '$roomid'");
                if ((int)$code[1] > (int)$code[8] && $code[8] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[1] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
                $peilv = get_query_val('fn_lottery7', 'hu', "`roomid` = '$roomid'");
                if ((int)$code[1] < (int)$code[8] && $code[1] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[8] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[1]) {
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '3') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if ((int)$code[2] > 5 || $code[2] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[2] < 6 && $code[2] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 != 0 && (int)$code[2] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 == 0 && (int)$code[2] > 5 || (int)$code[2] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 == 0 && (int)$code[2] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[2] % 2 != 0 && (int)$code[2] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
                $peilv = get_query_val('fn_lottery7', '`long`', "`roomid` = '$roomid'");
                if ((int)$code[2] > (int)$code[7] && $code[7] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[2] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
                $peilv = get_query_val('fn_lottery7', 'hu', "`roomid` = '$roomid'");
                if ((int)$code[2] < (int)$code[7] && $code[2] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[7] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[2]) {
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '4') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if ((int)$code[3] > 5 || $code[3] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[3] < 6 && $code[3] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 != 0 && (int)$code[3] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 == 0 && (int)$code[3] > 5 || (int)$code[3] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 == 0 && (int)$code[3] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[3] % 2 != 0 && (int)$code[3] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
                $peilv = get_query_val('fn_lottery7', '`long`', "`roomid` = '$roomid'");
                if ((int)$code[3] > (int)$code[6] && $code[6] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[3] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
                $peilv = get_query_val('fn_lottery7', 'hu', "`roomid` = '$roomid'");
                if ((int)$code[3] < (int)$code[6] && $code[3] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[6] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[3]) {
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '5') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if ((int)$code[4] > 5 || $code[4] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[4] < 6 && $code[4] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 != 0 && (int)$code[4] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 == 0 && (int)$code[4] > 5 || (int)$code[4] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 == 0 && (int)$code[4] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[4] % 2 != 0 && (int)$code[4] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '龙') {
                $peilv = get_query_val('fn_lottery7', '``', "`roomid` = '$roomid'");
                if ((int)$code[4] > (int)$code[5] && $code[5] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[4] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '虎') {
                $peilv = get_query_val('fn_lottery7', 'hu', "`roomid` = '$roomid'");
                if ((int)$code[4] < (int)$code[5] && $code[4] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($code[5] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[4]) {
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '6') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if ((int)$code[5] > 5 || $code[5] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[5] < 6 && $code[5] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 != 0 && (int)$code[5] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 == 0 && (int)$code[5] > 5 || (int)$code[5] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 == 0 && (int)$code[5] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[5] % 2 != 0 && (int)$code[5] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[5]) {
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '7') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if ((int)$code[6] > 5 || $code[6] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[6] < 6 && $code[6] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 != 0 && (int)$code[6] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 == 0 && (int)$code[6] > 5 || (int)$code[6] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 == 0 && (int)$code[6] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[6] % 2 != 0 && (int)$code[6] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[6]) {
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '8') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if ((int)$code[7] > 5 || $code[7] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[7] < 6 && $code[7] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 != 0 && (int)$code[7] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 == 0 && (int)$code[7] > 5 || (int)$code[7] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 == 0 && (int)$code[7] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[7] % 2 != 0 && (int)$code[7] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[7]) {
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '9') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if ((int)$code[8] > 5 || $code[8] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[8] < 6 && $code[8] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 != 0 && (int)$code[8] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 == 0 && (int)$code[8] > 5 || (int)$code[8] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 == 0 && (int)$code[8] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[8] % 2 != 0 && (int)$code[8] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[8]) {
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '0') {
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if ((int)$code[9] > 5 || $code[9] == '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if ((int)$code[9] < 6 && $code[9] != '0') {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大单') {
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 != 0 && (int)$code[9] > 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '大双') {
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 == 0 && (int)$code[9] > 5 || (int)$code[9] == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小双') {
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 == 0 && (int)$code[9] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小单') {
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if ((int)$code[9] % 2 != 0 && (int)$code[9] < 5) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == $code[9]) {
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if ($zym_1 == '和') {
            if ($code[0] == "0" || $code[1] == "0") {
                $hz = (int)$code[0] + (int)$code[1] + 10;
            } else {
                $hz = (int)$code[0] + (int)$code[1];
            }
            if ($zym_8 == '大') {
                $peilv = get_query_val('fn_lottery7', 'heda', "`roomid` = '$roomid'");
                if ($hz > 11) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '小') {
                $peilv = get_query_val('fn_lottery7', 'hexiao', "`roomid` = '$roomid'");
                if ($hz < 12) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '单') {
                $peilv = get_query_val('fn_lottery7', 'hedan', "`roomid` = '$roomid'");
                if ($hz % 2 != 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ($zym_8 == '双') {
                $peilv = get_query_val('fn_lottery7', 'heshuang', "`roomid` = '$roomid'");
                if ($hz % 2 == 0) {
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } else {
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } elseif ((int)$zym_8 == $hz) {
                if ($hz == 3 || $hz == 4 || $hz == 18 || $hz == 19) {
                    $peilv = get_query_val('fn_lottery7', 'he341819', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($hz == 5 || $hz == 6 || $hz == 16 || $hz == 17) {
                    $peilv = get_query_val('fn_lottery7', 'he561617', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($hz == 7 || $hz == 8 || $hz == 14 || $hz == 15) {
                    $peilv = get_query_val('fn_lottery7', 'he781415', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($hz == 9 || $hz == 10 || $hz == 12 || $hz == 13) {
                    $peilv = get_query_val('fn_lottery7', 'he9101213', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                } elseif ($hz == 11) {
                    $peilv = get_query_val('fn_lottery7', 'he11', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            } else {
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
    }
}

function kaichat($gameID, $term, $currentsn = '',$opencode)
{
    global $gmidAli;
    select_query('fn_lottery' . $gameID, '*', array('gameopen' => 'true'));
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    if($gameID == '5'){//加拿大28
        foreach ($cons as $con) {
            $gameType = $gmidAli[$gameID];
            senddata($con['roomid'], $gameID, $gameType, $currentsn);
            管理员喊话("☆☆☆☆☆☆☆☆☆☆</br> 第 $currentsn 期</br> 开奖结果 <span style='color:#ff0000'>$opencode</span> </br>☆☆☆☆☆☆☆☆☆☆", $con['roomid'], $gameType);
            管理员喊话("<img class='openhistory' src='/openhistory/list/{$gameID}/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);
            管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], $gameType);
            echo $gameType . "喊话-" . $con['roomid'] . '..<br>';
        }
    }else{
        if($gameID == '4'){//极速赛车
            foreach ($cons as $con) {
                $gameType = $gmidAli[$gameID];
                senddata($con['roomid'], $gameID, $gameType, $currentsn);
                //$gameType 定义 xy28
                管理员喊话("第 $currentsn 开奖结果!<img class='openhistory' src='/openhistory/4/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);
                管理员喊话("<img class='openhistory' src='/openhistory/list/{$gameID}/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);
                管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], $gameType);
                echo $gameType . "喊话-" . $con['roomid'] . '..<br>';
            }
        }else{
            if($gameID == '3'){ //极速飞艇
                foreach ($cons as $con) {
                    $gameType = $gmidAli[$gameID];
                    senddata($con['roomid'], $gameID, $gameType, $currentsn);
                    管理员喊话("第 $currentsn 开奖结果!<img class='openhistory' src='/openhistory/3/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);
                    管理员喊话("<img class='openhistory' src='/openhistory/list/{$gameID}/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);
                    管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], $gameType);
                    echo $gameType . "喊话-" . $con['roomid'] . '..<br>';
                }
            }else{
                if($gameID == '1'){ //澳洲10
                    foreach ($cons as $con) {
                        $gameType = $gmidAli[$gameID];
                        senddata($con['roomid'], $gameID, $gameType, $currentsn);
                        管理员喊话("第 $currentsn 开奖结果!<img class='openhistory' src='/openhistory/1/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);
                        管理员喊话("<img class='openhistory' src='/openhistory/list/{$gameID}/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);
                        管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], $gameType);
                        echo $gameType . "喊话-" . $con['roomid'] . '..<br>';
                    }
                }else{
                    if($gameID == '6'){ //急速飞艇700k
                        foreach ($cons as $con) {
                            $gameType = $gmidAli[$gameID];
                            senddata($con['roomid'], $gameID, $gameType, $currentsn);
                            管理员喊话("第 $currentsn 开奖结果!<img class='openhistory' src='/openhistory/6/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);
                            管理员喊话("<img class='openhistory' src='/openhistory/list/{$gameID}/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);
                            管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], $gameType);
                            echo $gameType . "喊话-" . $con['roomid'] . '..<br>';
                        }
                    }else{
                        if($gameID == '2'){ //官方幸运飞艇
                            foreach ($cons as $con) {
                                $gameType = $gmidAli[$gameID];
                                senddata($con['roomid'], $gameID, $gameType, $currentsn);
                                管理员喊话("第 $currentsn 开奖结果!<img class='openhistory' src='/openhistory/2/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);
                                管理员喊话("<img class='openhistory' src='/openhistory/list/{$gameID}/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);
                                管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], $gameType);
                                echo $gameType . "喊话-" . $con['roomid'] . '..<br>';
                            }
                        }else{
                            foreach ($cons as $con) { //默认生成加拿大28
                                $gameType = $gmidAli[$gameID];
                                senddata($con['roomid'], $gameID, $gameType, $currentsn);
                                管理员喊话("第 $currentsn 开奖结果!<img class='openhistory' src='/openhistory/{$gameType}_{$currentsn}.jpg?_t=" . time() . "'/>", $con['roomid'], $gameType);

                                管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], $gameType);
                                echo $gameType . "喊话-" . $con['roomid'] . '..<br>';
                            }
                        }
                    }
                }
            }
        }
    }
}
function 管理员喊话($Content, $roomid, $game, $term = '')
{
    $headimg = get_query_val('fn_setting', 'setting_robotsimg', array('roomid' => $roomid));
    insert_query("fn_chat", array("username" => "机器人", "headimg" => $headimg, 'content' => $Content, 'game' => $game, 'addtime' => date('H:i:s'), 'type' => 'S3', 'userid' => 'system', 'roomid' => $roomid));
}


function senddata($roomid, $type, $game, $term)
{

    //$term=get_query_val('fn_open', 'next_term', "`type` = {$type} order by `term` desc limit 1");
    select_query('fn_order', '*', "`roomid` = '{$roomid}' and `type` = {$type} and `term`='{$term}'");

    $data = [];
    while ($con = db_fetch_array()) {

        if (is_numeric($con['status'])) {
            if (!isset($data[$con['username']])) {
                $data[$con['username']]['earn'] = 0;
                $data[$con['username']]['money'] = 0;
            }
            //$data[$con['username']]['sum'] += $con['status'];
            $data[$con['username']]['earn'] += $con['status'];
            $data[$con['username']]['money'] += $con['status'] > 0 ? floatval($con['money']) : 0;
            $data[$con['username']]['data'][$con['mingci']][] = $con;
        }

    }
    $txt = "{$term}期已开奖<br>竞猜结果列表核对<br>";
    $txt .= "==================<br>";
    foreach ($data as $key => $v) {
        $sum = $v['earn'] - $v['money'];
        $d = $v['data'];
        $content = getcontent(isset($d['1']) ? $d['1'] : [], '冠军') .
            getcontent(isset($d['和']) ? $d['和'] : [], '冠亚') .
            getcontent(isset($d['2']) ? $d['2'] : [], '亚军') .
            getcontent(isset($d['3']) ? $d['3'] : [], '第三名') .
            getcontent(isset($d['4']) ? $d['4'] : [], '第四名') .
            getcontent(isset($d['5']) ? $d['5'] : [], '第五名') .
            getcontent(isset($d['6']) ? $d['6'] : [], '第六名') .
            getcontent(isset($d['7']) ? $d['7'] : [], '第七名') .
            getcontent(isset($d['8']) ? $d['8'] : [], '第八名') .
            getcontent(isset($d['9']) ? $d['9'] : [], '第九名') .
            getcontent(isset($d['0']) ? $d['0'] : [], '第十名');
        if($content){
            $txt .= "<br>[{$key}]<br>" . $content;//积分:{$sum}
            //$txt .=$v['money'];//改
        }
    }
    管理员喊话($txt, $roomid, $game);
    
}

function getcontent($data, $name)
{
    $array = [];
    foreach ($data as $val) {
        $val['content'] = (is_numeric($val['content']) && $val['content'] == 0) ? 10 : $val['content'];
        if (!isset($array[$val['content']])) {
            $array[$val['content']] = 0;
        }
        //$array[$val['content']] += $val['money'];
        if ($val['status'] > 0) {
            $array[$val['content']] += $val['money'];
//            $array[$val['content']] += $val['status'];
        }
    }
    $text = '';
    foreach ($array as $key => $v) {
        if (!empty($v)) {
            $text .= $key . '/' . $v . ' ';
        }
    }
    if (!empty($text)) {
        $text = $name . "[{$text}]<br>";
    }
    return $text;
}
