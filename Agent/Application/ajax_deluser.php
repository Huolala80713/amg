<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
if($_GET['t'] == '1'){
    $arr = array();
    if($_POST['id'] == ''){
        $arr['success'] = false;
        $arr['msg'] = '参数错误..Err(-1203)';
    }else{
        $username = get_query_val('fn_user' , 'username' , ['id'=>$_POST['id']]);
        doLog('删除用户【' . $username . '】');
        delete_query("fn_user", array("id" => $_POST['id']));
        $arr['success'] = true;
    }
    echo json_encode($arr);
}elseif($_GET['t'] == '2'){
    echo $_POST['checkid'];
}
?>