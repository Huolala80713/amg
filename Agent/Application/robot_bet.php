<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$time = get_query_vals('fn_setting', '*', array('roomid' => $_SESSION['agent_room']));
$refreshset = rand((int)$time['setting_robot_min'], (int)$time['setting_robot_max']);
if ($time['setting_robot_min'] == "") {
    $refreshset = rand(3, 8);
}
$stystime = GetTtime();
$BetGame = $_GET['g'];
runrobot($BetGame, $name, $headimg, $content);
function str_replace_once($needle, $replace, $haystack)
{
    $pos = strpos($haystack, $needle);
    if ($pos === false) {
        return $haystack;
    }
    return substr_replace($haystack, $replace, $pos, strlen($needle));
}

function 管理员喊话($Content, $game)
{
    $headimg = get_query_val('fn_setting', 'setting_robotsimg', array('roomid' => $_SESSION['agent_room']));
    insert_query("fn_chat", array("userid" => "system", "username" => "机器人", "game" => $game, 'headimg' => $headimg, 'content' => $Content, 'addtime' => date('H:i:s'), 'type' => 'S3', 'roomid' => $_SESSION['agent_room']));
}


function runrobot($BetGame, &$name, &$headimg, &$plan)
{
    $stystime = GetTtime();
    if ($BetGame == 'pk10') {
        if (get_query_val('fn_lottery1', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false') $BetGame = 'feng';
    } elseif ($BetGame == 'xyft') {
        if (get_query_val('fn_lottery2', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false') $BetGame = 'feng';
    } elseif ($BetGame == 'cqssc') {
        if (get_query_val('fn_lottery3', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false') $BetGame = 'feng';
    } elseif ($BetGame == 'xy28') {
        if (get_query_val('fn_lottery4', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false') $BetGame = 'feng';
    } elseif ($BetGame == 'jnd28') {
        if (get_query_val('fn_lottery5', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false') $BetGame = 'feng';
    } elseif ($BetGame == 'jsmt') {
        if (get_query_val('fn_lottery6', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false') $BetGame = 'feng';
    } elseif ($BetGame == 'jssc') {
        if (get_query_val('fn_lottery7', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false') $BetGame = 'feng';
    } elseif ($BetGame == 'jsssc') {
        if (get_query_val('fn_lottery8', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false') $BetGame = 'feng';
    }
    if ($BetGame == 'pk10') {
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 1 order by `id` desc limit 1");
        $time = (int)get_query_val('fn_lottery1', 'fengtime', array('roomid' => $_SESSION['agent_room']));
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 1 order by `id` desc limit 1')) - $stystime;
        if ($djs < 1) {
            $fengpan = true;
        } else {
            $fengpan = false;
        }
    } elseif ($BetGame == 'xyft') {
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 2 order by `id` desc limit 1");
        $time = (int)get_query_val('fn_lottery2', 'fengtime', array('roomid' => $_SESSION['agent_room']));
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 2 order by `id` desc limit 1')) - $stystime;
        if ($djs < 1) {
            $fengpan = true;
        } else {
            $fengpan = false;
        }
    } elseif ($BetGame == 'cqssc') {
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 3 order by `id` desc limit 1");
        $time = (int)get_query_val('fn_lottery3', 'fengtime', array('roomid' => $_SESSION['agent_room']));
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 3 order by `id` desc limit 1')) - $stystime;
        if ($djs < 1) {
            $fengpan = true;
        } else {
            $fengpan = false;
        }
    } elseif ($BetGame == 'xy28') {
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 4 order by `id` desc limit 1");
        $time = (int)get_query_val('fn_lottery4', 'fengtime');
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 4 order by `id` desc limit 1')) - $stystime;
        if ($djs < 1) {
            $fengpan = true;
        } else {
            $fengpan = false;
        }
    } elseif ($BetGame == 'jnd28') {
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 5 order by `id` desc limit 1");
        $time = (int)get_query_val('fn_lottery5', 'fengtime');
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 5 order by `id` desc limit 1')) - $stystime;
        if ($djs < 1) {
            $fengpan = true;
        } else {
            $fengpan = false;
        }
    } elseif ($BetGame == 'jsmt') {
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 6 order by `id` desc limit 1");
        $time = (int)get_query_val('fn_lottery6', 'fengtime');
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 6 order by `id` desc limit 1')) - $stystime;
        if ($djs < 1) {
            $fengpan = true;
        } else {
            $fengpan = false;
        }
    } elseif ($BetGame == 'jssc') {
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 7 order by `id` desc limit 1");
        $time = (int)get_query_val('fn_lottery7', 'fengtime');
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 7 order by `id` desc limit 1')) - $stystime;
        if ($djs < 1) {
            $fengpan = true;
        } else {
            $fengpan = false;
        }
    } elseif ($BetGame == 'jsssc') {
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 8 order by `id` desc limit 1");
        $time = (int)get_query_val('fn_lottery8', 'fengtime');
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 8 order by `id` desc limit 1')) - $stystime;
        if ($djs < 1) {
            $fengpan = true;
        } else {
            $fengpan = false;
        }
    } elseif ($BetGame == 'feng') {
        $fengpan = true;
    }
    if (!$fengpan) {
        $robots = get_query_vals('fn_robots', '*', "roomid = {$_SESSION['agent_room']} and game = '{$BetGame}' order by rand() desc limit 1");
        $headimg = $robots['headimg'];
        $name = $robots['name'];
        $plan = $robots['plan'];
        $plan = explode('|', $plan);
        if ($headimg == '') {
            exit;
        }
        if ($headimg == '' || $name == '' || $plan == '') return;
        $use = rand(0, count($plan) - 1);
        $plan = trim(get_query_val('fn_robotplan', 'content', array('id' => $plan[$use])));
        if (preg_match("/^fangan([0-9]+)$/", $plan, $matches)) {
            $data = file_get_contents(__DIR__ . '/fangan/fangan' . $matches[1] . '.txt');
            if (!empty($data)) {
                $data = explode("\n", trim($data));
                $key = array_rand($data, 1);
                $plan=$data[$key];
            }
        } else {
            if (preg_match("/{随机名次}/", $plan)) {
                $i2 = substr_count($plan, '{随机名次}');
                for ($i = 0; $i < $i2; $i++) {
                    $plan = str_replace_once("{随机名次}", rand(10,99), $plan);
                }
            }
            if (preg_match("/{随机特码}/", $plan)) {
                $i2 = substr_count($plan, '{随机特码}');
                for ($i = 0; $i < $i2; $i++) {
                    $plan = str_replace_once("{随机特码}", rand(10,99), $plan);
                }
            }
            if (preg_match("/{随机双面}/", $plan)) {
                $val = rand(1, 4);
                if ($val == 1) {
                    $val = '大';
                } elseif ($val == 2) {
                    $val = '小';
                } elseif ($val == 3) {
                    $val = '单';
                } elseif ($val == 4) {
                    $val = '双';
                }
                $plan = str_replace('{随机双面}', $val, $plan);
            }
            if (preg_match("/{随机龙虎}/", $plan)) {
                $val = rand(1, 2);
                if ($val == 1) {
                    $val = '龙';
                } elseif ($val == 2) {
                    $val = '虎';
                }
                $plan = str_replace('{随机龙虎}', $val, $plan);
            }
            if (preg_match("/{随机极值}/", $plan)) {
                $val = rand(1, 2);
                if ($val == 1) {
                    $val = '极大';
                } elseif ($val == 2) {
                    $val = '极小';
                }
                $plan = str_replace('{随机极值}', $val, $plan);
            }
            if (preg_match("/{随机组合1}/", $plan)) {
                $val = rand(1, 2);
                if ($val == 1) {
                    $val = '大单';
                } elseif ($val == 2) {
                    $val = '大双';
                }
                $plan = str_replace('{随机组合1}', $val, $plan);
            }
            if (preg_match("/{随机组合2}/", $plan)) {
                $val = rand(1, 2);
                if ($val == 1) {
                    $val = '小单';
                } elseif ($val == 2) {
                    $val = '小双';
                }
                $plan = str_replace('{随机组合2}', $val, $plan);
            }
            if (preg_match("/{随机数字}/", $plan)) {
                $i2 = substr_count($plan, '{随机数字}');
                for ($i = 0; $i < $i2; $i++) {
                    $plan = str_replace_once("{随机数字}", rand(0, 27), $plan);
                }
            }
            if (preg_match("/{随机和值}/", $plan)) {
                $i2 = substr_count($plan, '{随机和值}');
                for ($i = 0; $i < $i2; $i++) {
                    $plan = str_replace_once("{随机和值}", rand(3, 19), $plan);
                }
            }
            if (preg_match("/{随机特殊}/", $plan)) {
                $val = rand(1, 3);
                if ($val == 1) {
                    $val = '豹子';
                } elseif ($val == 2) {
                    $val = '对子';
                } elseif ($val == 3) {
                    $val = '顺子';
                }
                $plan = str_replace('{随机特殊}', $val, $plan);
            }
            if (preg_match("/{随机金额1}/", $plan)) {
                $plan = str_replace('{随机金额1}', rand(20, 300), $plan);
            }
            if (preg_match("/{随机金额2}/", $plan)) {
                $plan = str_replace('{随机金额2}', rand(300, 1000), $plan);
            }
            if (preg_match("/{随机金额3}/", $plan)) {
                $plan = str_replace('{随机金额3}', rand(1000, 3000), $plan);
            }
        }
        if (!empty($plan)) {
            insert_query("fn_chat", array("userid" => "robot", "username" => $name, 'headimg' => $headimg, 'content' => $plan, 'addtime' => date('H:i:s'), 'game' => $BetGame, 'roomid' => $_SESSION['agent_room'], 'type' => 'U3'));
            if (get_query_val("fn_setting", "setting_tishi", array("roomid" => $_SESSION['agent_room'])) == 'open') {
                管理员喊话("@$name,投注成功！请选择左侧菜单核对投注！", $BetGame);
                toOrder($plan, $_SESSION['agent_room'], "robot", $name, $BetGame, $headimg, $BetTerm);
            }
        }
    }
}

function toOrder($content, $roomid, $userid, $nickname, $gameName, $headimg, $addQihao)
{
    $gmidAli = [];
    $gmidAli[1] = 'pk10';
    $gmidAli[2] = 'xyft';
    $gmidAli[3] = 'cqssc';
    $gmidAli[4] = 'xy28';
    $gmidAli[5] = 'jnd28';
    $gmidAli[6] = 'jsmt';
    $gmidAli[7] = 'jssc';
    $gmidAli[8] = 'jsssc';
    $typeNum = array_flip($gmidAli);
    $gameTypeId = $typeNum[$gameName];
    $content = str_replace("冠亚和", "和", $content);
    $content = str_replace("冠亚", "和", $content);
    $content = str_replace("冠军", "1/", $content);
    $content = str_replace("亚军", "2/", $content);
    $content = str_replace("冠", "1/", $content);
    $content = str_replace("亚", "2/", $content);
    $content = str_replace("一", "1/", $content);
    $content = str_replace("二", "2/", $content);
    $content = str_replace("三", "3/", $content);
    $content = str_replace("四", "4/", $content);
    $content = str_replace("五", "5/", $content);
    $content = str_replace("六", "6/", $content);
    $content = str_replace("七", "7/", $content);
    $content = str_replace("八", "8/", $content);
    $content = str_replace("九", "9/", $content);
    $content = str_replace("十", "0/", $content);
    $content = str_replace(".", "/", $content);
    $content = preg_replace("/[位名各-]/u", "/", $content);
    $content = preg_replace("/(和|合|H|h)\//u", "$1", $content);
    $content = preg_replace("/[和合Hh]/u", "和/", $content);
    $content = preg_replace("/(大单|小单|大双|小双|大|小|单|双|龙|虎)\//u", "$1", $content);
    $content = preg_replace("/\/(大单|小单|大双|小双|大|小|单|双|龙|虎)/u", "$1", $content);
    $content = preg_replace("/(大单|小单|大双|小双|大|小|单|双|龙|虎)/u", "/$1/", $content);
    switch ($gameName) {
        case 'pk10':
            $table = 'fn_lottery1';
            $ordertable = "fn_order";
            break;
        case "xyft":
            $table = 'fn_lottery2';
            $ordertable = "fn_order";
            break;
        case "cqssc":
            $table = 'fn_lottery3';
            $ordertable = "fn_order";
            break;
        case "xy28":
            $table = 'fn_lottery4';
            $ordertable = "fn_order";
            break;
        case "jsmt":
            $table = 'fn_lottery6';
            $ordertable = "fn_mtorder";
            break;
        case "jssc":
            $table = 'fn_lottery7';
            $ordertable = "fn_jsscorder";
            break;
    }
    /* $dx_min = get_query_val($table, 'daxiao_min', array('roomid' => $roomid));
     $dx_max = get_query_val($table, 'daxiao_max', array('roomid' => $roomid));
     $ds_min = get_query_val($table, 'danshuang_min', array('roomid' => $roomid));
     $ds_max = get_query_val($table, 'danshuang_max', array('roomid' => $roomid));
     $lh_min = get_query_val($table, 'longhu_min', array('roomid' => $roomid));
     $lh_max = get_query_val($table, 'longhu_max', array('roomid' => $roomid));
     $tm_min = get_query_val($table, 'tema_min', array('roomid' => $roomid));
     $tm_max = get_query_val($table, 'tema_max', array('roomid' => $roomid));
     $hz_min = get_query_val($table, 'he_min', array('roomid' => $roomid));
     $hz_max = get_query_val($table, 'he_max', array('roomid' => $roomid));
     $zh_min = get_query_val($table, 'zuhe_min', array('roomid' => $roomid));
     $zh_max = get_query_val($table, 'zuhe_max', array('roomid' => $roomid));*/
    $zym_8 = 'true';
    $touzhu = false;
    $A = explode(" ", $content);
    $zym_2 = "";
    foreach ($A as $ai) {
        $ai = str_replace(" ", "", $ai);
        if (empty($ai)) continue;
        if (substr($ai, 0, 1) == '/') $ai = '1' . $ai;
        $b = explode("/", $ai);
        if (count($b) == 2) {
            $ai = '1/' . $ai;
            $b = explode("/", $ai);
        }

        if (count($b) != 3) continue;
        if ($b[0] == "" || $b[1] == "" || (int)$b[2] < 1) continue;
        //$zym_9 = 查询用户余额($userid);

        $zym_10 = $b[0];
        $zym_6 = $b[1];
        $zym_5 = (int)$b[2];
        insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $zym_10, 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $roomid, 'status' => '未结算', 'jia' => $zym_8, 'type' => $gameTypeId));

    }
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="refresh" content="<?php echo  $refreshset ?>"/>
    <title>聊天下注机器人</title>
</head>
<body>
<?php
echo $BetGame . "已自动发言: <img src=\"" . $headimg . "\" alt=\"\" width=\"28\" height=\"28\" /> " . $name . "[$content]";
?>
</body>
</html>