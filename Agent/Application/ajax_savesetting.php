<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
function getfileType($file)
{
    return substr(strrchr($file, '.'), 1);
}
if($_GET['form'] == 'agentsetting'){
    $game = $_GET['game'];
    $BetGame = getGameCodeById($game);
    $table = '';
    if ($BetGame == 'pk10') {
        $table = 'fn_lottery1';
    } elseif ($BetGame == 'xyft') {
        $table = 'fn_lottery2';
    } elseif ($BetGame == 'cqssc') {
        $table = 'fn_lottery3';
    } elseif ($BetGame == 'xy28') {
        $table = 'fn_lottery4';
    } elseif ($BetGame == 'jnd28') {
        $table = 'fn_lottery5';
    } elseif ($BetGame == 'jsmt') {
        $table = 'fn_lottery6';
    } elseif ($BetGame == 'jssc') {
        $table = 'fn_lottery7';
    } elseif ($BetGame == 'jsssc') {
        $table = 'fn_lottery8';
    } elseif ($BetGame == 'xglhc') {
        $table = 'fn_lottery9';
    }
    $fandian = $_POST['fandian'];
    $peilv_step = isset($_POST['peilv_step']) ? $_POST['peilv_step'] : '';
    if($fandian !== '' || $peilv_step !== '' || empty($table)){
        doLog('操作' . getGameTxtName($game) . '游戏代理设置');
        $update_data = [];
        $update_data['fandian'] = $fandian;
        if($peilv_step){
            $update_data['peilv_step'] = $peilv_step;
        }
        update_query($table, $update_data, array('roomid' => $_SESSION['agent_room']));
        echo '<script>alert("设置成功!"); window.location.href="/zb9n8rUvp0.php?m=setting&t=agent&game=' . $game . '";</script>';
    }else{
        echo '<script>alert("提交参数错误，请核对!"); window.location.href="/zb9n8rUvp0.php?m=setting&t=agent&game=' . $game . '";</script>';
    }
    die();
}else if ($_GET['form'] == 'form1') {
    $betOK = $_POST['betOK'] == 'on' ? 'open' : 'disable';
    $cancelBet = $_POST['cancelBet'] == 'on' ? 'open' : 'disable';
    $isChat = $_POST['isChat'] == 'on' ? 'open' : 'disable';
    $display_custom = $_POST['display_custom'] == 'on' ? 'true' : 'false';
    $display_extend = $_POST['display_extend'] == 'on' ? 'true' : 'false';
    $display_plan = $_POST['display_plan'] == 'on' ? 'true' : 'false';
    $display_game = $_POST['display_game'] == 'on' ? 'true' : 'false';
    $roomname = $_POST['roomname'];
    $people = $_POST['people'];
    $wordkeys = $_POST['wordkeys'];
    $videoword = $_POST['videoword'];
    $settinggame = $_POST['settinggame'];
    $msg1_time = $_POST['msg1_time'];
    $msg2_time = $_POST['msg2_time'];
    $msg3_time = $_POST['msg3_time'];
    $msg1_cont = $_POST['msg1_cont'];
    $msg2_cont = $_POST['msg2_cont'];
    $msg3_cont = $_POST['msg3_cont'];


    $weichat_number = $_POST['weichat_number'];
    $weichat_name = $_POST['weichat_name'];
    $alipay_number = $_POST['alipay_number'];
    $alipay_name = $_POST['alipay_name'];
    $qq_number = $_POST['qq_number'];
    $bank_number = $_POST['bank_number'];
    $bank_name = $_POST['bank_name'];
    $bank_username = $_POST['bank_username'];
    $bank_fenhang = $_POST['bank_fenhang'];

    if ($_FILES['alipay_image']['size'] > 0) {
        if ((($_FILES["alipay_image"]["type"] == "image/gif") || ($_FILES["alipay_image"]["type"] == "image/jpeg") || ($_FILES["alipay_image"]["type"] == "image/png")) && ($_FILES["alipay_image"]["size"] < 2000000)) {
            if ($_FILES["alipay_image"]["error"] > 0) {
                echo '<script>alert("上传文件出错..错误代码:' . $_FILES["alipay_image"]["error"] . '"); window.location.href = "/zb9n8rUvp0.php?m=setting&a=setting";</script>';
            } else {
                $alipay_image = '/upload/' . date('Ymd') . time() . '.' . getfileType($_FILES['alipay_image']['name']);
                $filedir = dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . $alipay_image;
                move_uploaded_file($_FILES["alipay_image"]["tmp_name"], $filedir);
            }
        } else {
            echo '<script>alert("支付宝收款码图片格式错误"); window.location.href = "/zb9n8rUvp0.php?m=setting&a=setting";</script>';
        }
    } else {
        $alipay_image = null;
    }

    if ($_FILES['weichat_image']['size'] > 0) {
        if ((($_FILES["weichat_image"]["type"] == "image/gif") || ($_FILES["weichat_image"]["type"] == "image/jpeg") || ($_FILES["weichat_image"]["type"] == "image/png")) && ($_FILES["weichat_image"]["size"] < 2000000)) {
            if ($_FILES["weichat_image"]["error"] > 0) {
                echo '<script>alert("上传文件出错..错误代码:' . $_FILES["weichat_image"]["error"] . '"); window.location.href="/zb9n8rUvp0.php?m=setting&a=setting";</script>';
            } else {
                $weichat_image = '/upload/' . date('Ymd') . (time() + 1) . '.' . getfileType($_FILES['weichat_image']['name']);
                $filedir = dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . $weichat_image;
                move_uploaded_file($_FILES["weichat_image"]["tmp_name"], $filedir);
            }
        } else {
            echo '<script>alert("微信收款码图片格式错误"); window.location.href = "/zb9n8rUvp0.php?m=setting&a=setting";</script>';
        }
    } else {
        $weichat_image = null;
    }

    if ($_FILES['qrcode']['size'] > 0) {
        if ((($_FILES["qrcode"]["type"] == "image/gif") || ($_FILES["qrcode"]["type"] == "image/jpeg") || ($_FILES["qrcode"]["type"] == "image/png")) && ($_FILES["qrcode"]["size"] < 2000000)) {
            if ($_FILES["qrcode"]["error"] > 0) {
                echo '<script>alert("上传文件出错..错误代码:' . $_FILES["qrcode"]["error"] . '"); window.location.href = "/zb9n8rUvp0.php?m=setting&a=setting";</script>';
            } else {
                $qrcode = '/upload/' . date('Ymd') . time() . '.' . getfileType($_FILES['qrcode']['name']);
                $filedir = dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . $qrcode;
                move_uploaded_file($_FILES["qrcode"]["tmp_name"], $filedir);
            }
        } else {
            echo '<script>alert("客服二维码图片格式错误"); window.location.href = "/zb9n8rUvp0.php?m=setting&a=setting";</script>';
        }
    } else {
        $qrcode = null;
    }

    if ($_FILES['robotimg']['size'] > 0) {
        if ((($_FILES["robotimg"]["type"] == "image/gif") || ($_FILES["robotimg"]["type"] == "image/jpeg") || ($_FILES["robotimg"]["type"] == "image/png")) && ($_FILES["robotimg"]["size"] < 2000000)) {
            if ($_FILES["robotimg"]["error"] > 0) {
                echo '<script>alert("上传文件出错..错误代码:' . $_FILES["robotimg"]["error"] . '"); window.location.href="/zb9n8rUvp0.php?m=setting&a=setting";</script>';
            } else {
                $robotimg = '/upload/' . date('Ymd') . (time() + 1) . '.' . getfileType($_FILES['robotimg']['name']);
                $filedir = dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . $robotimg;
                move_uploaded_file($_FILES["robotimg"]["tmp_name"], $filedir);
            }
        } else {
            echo '<script>alert("机器人头像图片格式错误"); window.location.href = "/zb9n8rUvp0.php?m=setting&a=setting";</script>';
        }
    } else {
        $robotimg = null;
    }
    if ($_FILES['customimg']['size'] > 0) {
        if ((($_FILES["customimg"]["type"] == "image/gif") || ($_FILES["customimg"]["type"] == "image/jpeg") || ($_FILES["customimg"]["type"] == "image/png")) && ($_FILES["customimg"]["size"] < 2000000)) {
            if ($_FILES["customimg"]["error"] > 0) {
                echo '<script>alert("上传文件出错..错误代码:' . $_FILES["customimg"]["error"] . '"); window.location.href="/zb9n8rUvp0.php?m=setting&a=setting";</script>';
            } else {
                $customimg = '/upload/' . date('Ymd') . (time() + 2) . '.' . getfileType($_FILES['customimg']['name']);
                $filedir = dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . $customimg;
                move_uploaded_file($_FILES["customimg"]["tmp_name"], $filedir);
            }
        } else {
            echo '<script>alert("客服头像图片格式错误"); window.location.href = "/zb9n8rUvp0.php?m=setting&a=setting";</script>';
        }
    } else {
        $customimg = null;
    }

    update_query("fn_setting", array("weichat_number" => $weichat_number,"weichat_name" => $weichat_name,"alipay_number" => $alipay_number,"alipay_name" => $alipay_name,"qq_number" => $qq_number,"bank_number" => $bank_number,"bank_name" => $bank_name,"bank_username" => $bank_username,"bank_fenhang" => $bank_fenhang,"setting_tishi" => $betOK, 'setting_cancelbet' => $cancelBet, 'setting_ischat' => $isChat, 'setting_game' => $settinggame, 'display_custom' => $display_custom, 'display_extend' => $display_extend, 'display_plan' => $display_plan, 'display_game' => $display_game/*, 'setting_people' => $people*/, 'setting_wordkeys' => $wordkeys, 'setting_video' => $videoword, 'msg1_time' => $msg1_time, 'msg1_cont' => $msg1_cont, 'msg2_time' => $msg2_time, 'msg2_cont' => $msg2_cont, 'msg3_time' => $msg3_time, 'msg3_cont' => $msg3_cont), array('roomid' => $_SESSION['agent_room']));
    if ($qrcode != null) update_query('fn_setting', array('setting_qrcode' => $qrcode), array('roomid' => $_SESSION['agent_room']));
    if ($robotimg != null) update_query('fn_setting', array('setting_robotsimg' => $robotimg), array('roomid' => $_SESSION['agent_room']));
    if ($customimg != null) update_query('fn_setting', array('setting_sysimg' => $customimg), array('roomid' => $_SESSION['agent_room']));
    if ($weichat_image != null) update_query('fn_setting', array('weichat_image' => $weichat_image), array('roomid' => $_SESSION['agent_room']));
    if ($alipay_image != null) update_query('fn_setting', array('alipay_image' => $alipay_image), array('roomid' => $_SESSION['agent_room']));
    doLog('修改系统参数设置');
    update_query("fn_room", array("roomname" => $roomname), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=setting&a=setting";</script>';
} elseif ($_GET['form'] == 'form2') {
    $title1 = $_POST['rulestitle1'];
    $page1 = $_POST['rulespage1'];
    $title2 = $_POST['rulestitle2'];
    $page2 = $_POST['rulespage2'];
    $title3 = $_POST['rulestitle3'];
    $page3 = $_POST['rulespage3'];
    $title4 = $_POST['rulestitle4'];
    $page4 = $_POST['rulespage4'];
    $title5 = $_POST['rulestitle5'];
    $page5 = $_POST['rulespage5'];
    $title6 = $_POST['rulestitle6'];
    $page6 = $_POST['rulespage6'];
    $title7 = $_POST['rulestitle7'];
    $page7 = $_POST['rulespage7'];
    $title8 = $_POST['rulestitle8'];
    $page8 = $_POST['rulespage8'];
    $res = $title1 . '|' . $page1 . '|' . $title2 . '|' . $page2 . '|' . $title3 . '|' . $page3 . '|' . $title4 . '|' . $page4 . '|' . $title5 . '|' . $page5 . '|' . $title6 . '|' . $page6 . '|' . $title7 . '|' . $page7 . '|' . $title8 . '|' . $page8;
    update_query("fn_setting", array("setting_rules" => $res), array('roomid' => $_SESSION['agent_room']));
    doLog('修改系统参数设置');
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=setting&a=setting";</script>';
} elseif ($_GET['form'] == 'form3') {
    $cont = $_POST['customcont'];
    doLog('修改系统客服栏内容设置');
    update_query("fn_setting", array("setting_kefu" => $cont), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=setting&a=setting";</script>';
} elseif ($_GET['form'] == 'form4') {
    $cont = $_POST['customcont'];
    update_query("fn_setting", array("setting_fengpan30s" => $cont), array('roomid' => $_SESSION['agent_room']));
    doLog('修改系统客服栏内容设置');
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=setting&a=setting";</script>';
} elseif ($_GET['form'] == 'form5') {
    $cont = $_POST['customcont'];
    doLog('修改系统客服栏内容设置');
    update_query("fn_setting", array("setting_fengpan" => $cont), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=setting&a=setting";</script>';
}
?>