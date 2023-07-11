<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_POST['type'];
switch ($type){
    case 'modifypass':
        $pass1 = $_POST['pass1'];
        $pass2 = $_POST['pass2'];
        $Userid = $_POST['id'];
        if(empty($Userid)){
            echo json_encode(array("success" => false, "msg" => "用户ID不能为空"));
            exit();
        }
        if(empty($pass1) || empty($pass2)){
            echo json_encode(array("success" => false, "msg" => "密码填写错误"));
            exit();
        }
        if($pass1 != $pass2){
            echo json_encode(array("success" => false, "msg" => "确认密码与新密码不一致"));
            exit();
        }
        update_query('fn_user', array('userpass' => md5($pass1)), array('id' => $Userid, 'roomid' => $_SESSION['agent_room']));
        doLog('修改用户密码【' . get_query_val('fn_user' , 'username' , ['id' => $Userid, 'roomid' => $_SESSION['agent_room']]) . '】');
        echo json_encode(array("success" => true, "msg" => "操作成功"));
        break;
    case 'lahei':
        $Userid = $_POST['id'];
        if(empty($Userid)){
            echo json_encode(array("success" => false, "msg" => "用户ID不能为空"));
            exit();
        }
        $user = get_query_vals('fn_user' , 'is_black,username' , ['id' => $Userid, 'roomid' => $_SESSION['agent_room']]);
        update_query('fn_user', array('token'=>"",'is_black' => $user['is_black']==1?0:1), array('id' => $Userid, 'roomid' => $_SESSION['agent_room']));
        if($user['is_black'] == 1){
            doLog('取消拉黑用户【'  . $user['username'] . '】');
        }else{
            doLog('拉黑用户【'  . $user['username'] . '】');
        }
        echo json_encode(array("success" => true, "msg" => "操作成功"));
        break;
    case 'remark':
        $id = $_POST['id'];
        if(empty($id)){
            echo json_encode(array("success" => false, "msg" => "用户ID不能为空"));
            exit();
        }
        $user = get_query_vals('fn_user' , 'is_black,username' , ['id' => $id, 'roomid' => $_SESSION['agent_room']]);
        update_query('fn_user', array('remark' => htmlspecialchars($_POST['remark'])), array('id' => $id, 'roomid' => $_SESSION['agent_room']));
        doLog('备注用户信息【'  . $user['username'] . '】');
        echo json_encode(array("success" => true, "msg" => "操作成功"));
        break;
}
exit();
?>