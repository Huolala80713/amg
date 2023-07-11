<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$game = $_GET['game'] == "" ? '' : $_GET['game'];
$page = $_GET['page']>=1?$_GET['page']:1;
$pagesize = $_GET['pagesize'];
$table = 'fn_order';
$sql = "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and userid != 'robot'" ;
if($game){
    $type = getGameIdByCode($game);
    $sql .= " and type = '{$type}'" ;
}
select_query($table ,'*' , $sql , ['id desc'] , ($page-1)*$pagesize . ',' . $pagesize);
$list = [];
while($con = db_fetch_array()){
    $list[] = $con;
}
foreach($list as &$con){
    if(getGameCodeById($con['type']) == 'jnd28'){
        $con['content_text'] = $con['content'] . ' / '. $con['money'];
    }else{
        $mingci = ((is_numeric($con['mingci']) && $con['mingci']==0)?10:$con['mingci']);
        $content = ((is_numeric($con['content']) && $con['content']==0)?10:$con['content']);
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
        $con['content_text'] = $mingci . ' / ' . $content . ' / '. $con['money'];
    }
    $con['btn'] = '<a href="javascript:back('.$con['id'].');" class="btn btn-success btn-sm">退还</a>&nbsp;&nbsp;<a href="javascript:del('.$con['id'].')" class="btn btn-danger btn-sm">删除</a>';
}
$data = [
    'list' => $list,
    'totalCount' => get_query_val($table,'count(id) as counts' , $sql)
];
echo json_encode($data);