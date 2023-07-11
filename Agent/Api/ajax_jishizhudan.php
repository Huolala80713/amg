<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$game = $_GET['game'] == "" ? '' : $_GET['game'];
$term = $_GET['term'];
$date = $_GET['date'];
$datetype = $_GET['datetype'];
$page = $_GET['page']>=1?$_GET['page']:1;
$pagesize = $_GET['pagesize'];
$userid = $_GET['userid'];
$sql = "jia = 'false'";
if($date != ''){
    $date = explode(' - ',$date);
    $sql .= " and (`addtime` between '{$date[0]}' and '{$date[1]}')";
}
$type = '';
if($game){
    $type = getGameIdByCode($game);
}
if($term != '') $sql .= " and term = '{$term}'";
if($type != '') $sql .= " and type = '{$type}'";
if($userid != '') $sql .= " and userid = '{$userid}'";
$table = 'fn_order';

select_query($table,'*', $sql ,['id desc'], ($page-1)*$pagesize . ',' . $pagesize);
$all_touzhu = 0;
$all_res = 0;
$list = [];
while($con = db_fetch_array()){
    foreach ($con as $key => $value){
        if(!is_string($key)){
            unset($con[$key]);
        }
    }
    $list[] = $con;
}
foreach($list as &$con){
	$con['headimg'] = WEB_HOST.$con['headimg'];
	$con['btn'] = '
	<a href="javascript:addremark(' . $con['id'] . ')" class="btn btn-danger btn-sm">备注</a>
	';
	$con['remark'] = get_query_val('fn_user', 'remark', array('userid'=>$con['userid']));
    $con['game'] = getGameTxtNameByCode(getGameCodeById($con['type']));
    if(getGameCodeById($con['type']) == 'jnd28'){
        $con['content_text'] = $con['content'];
    }else{
        $mingci = ((is_numeric($con['mingci']) && $con['mingci']==0)?10:$con['mingci']);
        $content = (is_numeric($con['content']) && $con['content']==0)?10:$con['content'];
        //$content = is_numeric($con['content'])?'第' . $con['content'] . '车道':$con['content'];
        switch ($mingci){
            case 1:
                $mingci = '冠军';
                break;
            case 2:
                $mingci = '亚军';
                break;
            case 3:
                $mingci = '第三名';
                break;
            case 4:
                $mingci = '第四名';
                break;
            case 5:
                $mingci = '第五名';
                break;
            case 6:
                $mingci = '第六名';
                break;
            case 7:
                $mingci = '第七名';
                break;
            case 8:
                $mingci = '第八名';
                break;
            case 9:
                $mingci = '第九名';
                break;
            case 10:
                $mingci = '第十名';
                break;
            case '和':
                $mingci = '冠亚和';
                break;
        }
        $con['content_text'] =  $mingci . ' / ' . $content;
    }

}
$all_touzhu = get_query_val($table,'sum(money) as touzhu' , $sql . " and status != '已撤单'");
$all_res = get_query_val($table,'sum(status) as res' , $sql . " and status != '已撤单' and status != '未结算' and status > 0");
$data = [
    'list' => $list,
    'all_res' => round($all_res , 2),
    'all_touzhu' => $all_touzhu,
    'all_yk' => round($all_res - $all_touzhu , 4),
    'totalCount' => get_query_val($table,'count(id) as counts' , $sql)
];
echo json_encode($data);