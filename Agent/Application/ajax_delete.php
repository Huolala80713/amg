<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$time = $_POST['time'];
$pass = $_POST['pass'];
if($pass != 'feiniao8787'){
    echo json_encode(array('success' => false, 'msg' => '验证安全码错误!请联系管理员进行该操作'));
    exit;
}
db_query("TRUNCATE fn_chat");
delete_query("fn_custom", "`addtime` < '$time 23:59:59' and roomid = '{$_SESSION['agent_room']}'");
delete_query("fn_order", "`addtime` < '$time 23:59:59' and roomid = '{$_SESSION['agent_room']}'");
delete_query("fn_pcorder", "`addtime` < '$time 23:59:59' and roomid = '{$_SESSION['agent_room']}'");
delete_query("fn_mtorder", "`addtime` < '$time 23:59:59' and roomid = '{$_SESSION['agent_room']}'");
delete_query("fn_marklog", "`addtime` < '$time 23:59:59' and roomid = '{$_SESSION['agent_room']}'");
delete_query("fn_open", "`time` < '$time 23:59:59'");
delete_query("fn_upmark", "`time` < '$time 23:59:59' and roomid = '{$_SESSION['agent_room']}'");
delete_query("fn_flyorder", "`time` < '$time 23:59:59' and roomid = '{$_SESSION['agent_room']}'");
delete_query("fn_chat", "roomid = '{$_SESSION['agent_room']}'");
delete_query("fn_marklog", "`addtime` < '$time 23:59:59'  and roomid = '{$_SESSION['agent_room']}'");
echo json_encode(array("success" => true, "msg" => "删除成功"));
doLog('执行了清理数据');
exit;
?>