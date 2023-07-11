<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
if(!in_array($type , ['add','edit','getinfo','delete'])){
    return ajaxMsg('参数错误', 0);
}
$username = htmlspecialchars($_GET['username']);
$password = htmlspecialchars($_GET['password']);
$auth_type = htmlspecialchars($_GET['auth_type']);
$auth = $_GET['auth'];
if($auth){
    foreach ($auth as $key=>$value){
        $value = explode('_' , $value);
        if(count($value) > 1){
            $auth[] = $value[0];
        }
    }
}
$auth = array_unique($auth);
$status = htmlspecialchars($_GET['status']);
if($type == 'add'){
    if(empty($username)){
        return ajaxMsg('登录用户名不能为空', 0);
    }
    if(empty($password)){
        return ajaxMsg('登录密码不能为空', 0);
    }
    if(empty($auth_type)){
        return ajaxMsg('请选择账号类型', 0);
    }
    $roomadmin = get_query_val('fn_admin' , 'roomadmin' , ['roomid'=>$_SESSION['agent_room'],'roomadmin'=>$username]);
    if(!empty($roomadmin)){
        return ajaxMsg('登录账号已存在', 0);
    }
    $id = '';
    insert_query('fn_admin' , ['auth_type'=>$auth_type,'roomid'=>$_SESSION['agent_room'],'roomadmin'=>$username,'roompass'=>md5($password),'add_time'=>date('Y-m-d H:i:s'),'status'=>$status,'auth'=>implode(',',$auth)] , $id);
    if($id){
        doLog('添加管理员【' . $id . '】');
        return ajaxMsg('添加成功', 1);
    }else{
        return ajaxMsg('添加失败', 0);
    }
}elseif($type == 'edit'){
    $id = htmlspecialchars($_GET['id']);
    if(empty($id)){
        return ajaxMsg('ID参数不能为空', 0);
    }
    $roomadmin = get_query_vals('fn_admin' , '*' , ['id'=>$id,'roomid'=>$_SESSION['agent_room']]);
    if($roomadmin['auth_type'] == 1){
       // return ajaxMsg('超级管理员不允许修改', 0);
    }
    $data = ['auth'=>implode(',' , $auth)];
    if($password){
        $data['roompass'] = md5($password);
    }
//    if(empty($auth_type)){
//        return ajaxMsg('请选择账号类型', 0);
//    }else{
//        $data['auth_type'] = $auth_type;
//    }
    $res = update_query('fn_admin' , $data , ['id'=>$id,'roomid'=>$_SESSION['agent_room']]);
    if($res !== false){
        doLog('编辑管理员【' . $id . '】');
        return ajaxMsg('修改成功', 1);
    }else{
        return ajaxMsg('修改失败', 0);
    }
}elseif($type == 'getinfo'){
    $id = htmlspecialchars($_GET['id']);
    if(empty($id)){
        return ajaxMsg('ID参数不能为空', 0);
    }
    $roomadmin = get_query_vals('fn_admin' , '*' , ['id'=>$id,'roomid'=>$_SESSION['agent_room']]);
    if($roomadmin){
        $roomadmin['auth'] = explode(',' , $roomadmin['auth']);
        $arr['status'] = 1;
        $arr['data'] = $roomadmin;
        echo json_encode($arr);
        die();
    }else{
        return ajaxMsg('未找到管理员信息', 0);
    }
}elseif($type == 'delete'){
    $id = htmlspecialchars($_GET['id']);
    if(empty($id)){
        return ajaxMsg('ID参数不能为空', 0);
    }
    $roomadmin = get_query_vals('fn_admin' , '*' , ['id'=>$id,'roomid'=>$_SESSION['agent_room']]);
    if($roomadmin['auth_type'] == 1){
        return ajaxMsg('超级管理员不允许删除', 0);
    }
    delete_query('fn_admin' ,  ['id'=>$id,'roomid'=>$_SESSION['agent_room']]);
    doLog('删除管理员【' . $id . '】');
    $arr['status'] = 1;
    $arr['data'] = [];
    echo json_encode($arr);
    die();
}

?>