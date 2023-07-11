<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$arr = array();
if($_POST['id'] != '' || $_POST['userid']){
    $id = $_POST['id'];
    $userid = $_POST['userid'];
    if($id){
        $ban = get_query_vals('fn_ban' , 'username,userid' , "id = {$id}");
        delete_query("fn_ban", "id = {$id}");
    }else{
        $ban = get_query_vals('fn_ban' , 'username,userid' , "userid = '{$userid}'");
        delete_query("fn_ban", " userid = '{$userid}'");
    }
    doLog('取消禁言用户【' . $ban['username'] . '】');
    $arr['success'] = true;
}else{
    $arr['success'] = false;
    $arr['msg'] = '参数错误..Err(-1203)';
}
echo json_encode($arr);
?>