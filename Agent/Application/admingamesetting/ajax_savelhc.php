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

    update_query("fn_admin_lottery9",
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
            "shengxiao"=>$shengxiao


        ), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=admin_g_setting&g=9";</script>';
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

    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/zb9n8rUvp0.php?m=admin_g_setting&g=9";</script>';
}
?>