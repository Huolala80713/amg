<?php
include(dirname(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__))))) . "/Public/config.php");
if($_GET['form'] == 'form1'){
    $tema_haoma = $_POST['tema_haoma'];
    $tema_shuangmian_daxiao = $_POST['tema_shuangmian_daxiao'];
    $tema_shuangmian_danshuang = $_POST['tema_shuangmian_danshuang'];
    $tema_shuangmian_dxds = $_POST['tema_shuangmian_dxds'];
    $tema_shuangmian_red = $_POST['tema_shuangmian_red'];
    $tema_shuangmian_blue = $_POST['tema_shuangmian_blue'];
    $tema_shuangmian_green = $_POST['tema_shuangmian_green'];
    $tema_sx = $_POST['tema_shengxiao'];
    $tema_sx_tu = $_POST['tema_shengxiao_tu'];

    $zhengma_haoma = $_POST['zhengma_haoma'];
    $zhengma_shuangmian_daxiao = $_POST['zhengma_shuangmian_daxiao'];
    $zhengma_shuangmian_danshuang = $_POST['zhengma_shuangmian_danshuang'];
    $zhengma_shuangmian_dxds = $_POST['zhengma_shuangmian_dxds'];
    $zhengma_shuangmian_red = $_POST['zhengma_shuangmian_red'];
    $zhengma_shuangmian_blue = $_POST['zhengma_shuangmian_blue'];
    $zhengma_shuangmian_green = $_POST['zhengma_shuangmian_green'];
    $zhengma_sx = $_POST['zhengma_shengxiao'];
    $zhengma_sx_tu = $_POST['zhengma_shengxiao_tu'];

    //生肖
    $shengxiao = $_POST['shengxiao'];
    $shengxiao_tu = $_POST['shengxiao_tu'];
    //var_dump($_POST);die();
    update_query("fn_lottery9",
        array(
            "tema_haoma" => $tema_haoma,
            "tema_shuangmian_daxiao" => $tema_shuangmian_daxiao,
            "tema_shuangmian_danshuang" => $tema_shuangmian_danshuang,
            "tema_shuangmian_dxds" => $tema_shuangmian_dxds,
            "tema_shuangmian_red" => $tema_shuangmian_red,
            "tema_shuangmian_blue" => $tema_shuangmian_blue,
            "tema_shuangmian_green" => $tema_shuangmian_green,
            "tema_shengxiao" => $tema_sx,
            "tema_shengxiao_tu" => $tema_sx_tu,
            "zhengma_haoma" => $zhengma_haoma,
            "zhengma_shuangmian_daxiao" => $zhengma_shuangmian_daxiao,
            "zhengma_shuangmian_danshuang" => $zhengma_shuangmian_danshuang,
            "zhengma_shuangmian_dxds" => $zhengma_shuangmian_dxds,
            "zhengma_shuangmian_red" => $zhengma_shuangmian_red,
            "zhengma_shuangmian_blue" => $zhengma_shuangmian_blue,
            "zhengma_shuangmian_green" => $zhengma_shuangmian_green,
            "zhengma_shengxiao" => $zhengma_sx,
            "zhengma_shengxiao_tu" => $zhengma_sx_tu,
            "shengxiao_tu"=>$shengxiao_tu,
            "shengxiao"=>$shengxiao,

            "tema_haoma_step" => $_POST['tema_haoma_step'],
            "tema_shuangmian_daxiao_step" => $_POST['tema_shuangmian_daxiao_step'],
            "tema_shuangmian_danshuang_step" => $_POST['tema_shuangmian_danshuang_step'],
            "tema_shuangmian_dxds_step" => $_POST['tema_shuangmian_dxds_step'],
            "tema_shuangmian_red_step" => $_POST['tema_shuangmian_red_step'],
            "tema_shuangmian_blue_step" => $_POST['tema_shuangmian_blue_step'],
            "tema_shuangmian_green_step" => $_POST['tema_shuangmian_green_step'],
            "tema_shengxiao_step" => $_POST['tema_shengxiao_step'],
            "tema_shengxiao_tu_step" => $_POST['tema_shengxiao_tu_step'],
            "zhengma_haoma_step" => $_POST['zhengma_haoma_step'],
            "zhengma_shuangmian_daxiao_step" => $_POST['zhengma_shuangmian_daxiao_step'],
            "zhengma_shuangmian_danshuang_step" => $_POST['zhengma_shuangmian_danshuang_step'],
            "zhengma_shuangmian_dxds_step" => $_POST['zhengma_shuangmian_dxds_step'],
            "zhengma_shuangmian_red_step" => $_POST['zhengma_shuangmian_red_step'],
            "zhengma_shuangmian_blue_step" => $_POST['zhengma_shuangmian_blue_step'],
            "zhengma_shuangmian_green_step" => $_POST['zhengma_shuangmian_green_step'],
            "zhengma_shengxiao_step" => $_POST['zhengma_shengxiao_step'],
            "zhengma_shengxiao_tu_step" => $_POST['zhengma_shengxiao_tu_step'],
            "shengxiao_step" => $_POST['shengxiao_step'],
            "shengxiao_tu_step" => $_POST['shengxiao_tu_step'],

        ), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=g_setting&g=9";</script>';
}elseif($_GET['form'] == 'form2'){

    update_query("fn_lottery9",
        array(
            "haoma_min" => $_POST['haoma_min'],
            "haoma_max" => $_POST['haoma_max'],
            "shuangmian_max" => $_POST['shuangmian_max'],
            "shuangmian_min" => $_POST['shuangmian_min'],
            "shengxiao_min" => $_POST['shengxiao_min'],
            "shengxiao_max" => $_POST['shengxiao_max'],
        ), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=g_setting&g=9";</script>';
}elseif($_GET['form'] == 'form3'){
    $open = $_POST['opengame'] == 'on' ? 'true' : 'false';
    //$fengtime = $_POST['fengtime'];
    $begin_bet_hours = (21 - $_POST['begin_bet_hours']);
    $begin_bet_min = $_POST['begin_bet_min'];
    $begin_bet_times = ($begin_bet_hours * 3600) + ((30 - $begin_bet_min)*60);

    //封盘时间
    $begin_fengpan_hours = (21 - $_POST['begin_fengpan_hours']);
    $begin_fengpan_min = $_POST['begin_fengpan_min'];
    $fengtime = ($begin_fengpan_hours * 3600) + ((30 - $begin_fengpan_min)*60);

    var_dump($fengtime);
//    var_dump($begin_bet_min);
//    var_dump($begin_bet_hours);
//    var_dump($begin_bet_times);
    $peilv_step = $_POST['peilv_step'];
    update_query("fn_lottery9", array("fengtime" => $fengtime, 'gameopen' => $open, 'begin_bet_times' => $begin_bet_times, 'peilv_step' => $peilv_step), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=g_setting&g=9";</script>';
}elseif($_GET['form'] == 'form4'){
    $content = $_POST['customcont'];
    update_query("fn_lottery9", array("rules" => $content), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=g_setting&g=9";</script>';
}elseif($_GET['form'] == 'form9'){//20230729

    $content = $_POST;
    $wanfa = [];
    foreach($content as $k=>$v){
        $v_arr = explode("__",trim($k));
        //$wanfa[trim($v_arr[0])]['id'] = $v_arr[0];
        $wanfa[trim($v_arr[0])][$v_arr[1]] = $v;
    }
    if($wanfa){
        foreach($wanfa as $wk=>$wv){
            update_query("fn_lhc_wanfa", $wv, array("id" => $wk));
        }
    }

    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=g_setting&g=9";</script>';
}
?>