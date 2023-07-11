<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
if($type == '1'){
    $id = $_GET['userid'];
    $date = $_GET['datetype'];
    if(empty($date)){
        if($_GET['time']){
            $date = explode(' - ' , $_GET['time']);
        }else{
            //$date = [date('Y-m-d'),date('Y-m-d')];
        }

    }else{
        $date = explode(',' , $date);
    }
    $username = get_query_val('fn_user', 'username', array('id' => $id));
    $id = get_query_val('fn_user', 'userid', array('id' => $id));
    $sql = "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}'";
    if(count($date) == 2){
        $date[0] = date('Y-m-d 06:00:00' , strtotime($date[0]));
        $date[1] = date('Y-m-d 05:59:59' , strtotime($date[1] . ' + 1day'));
        $sql .= " and (`addtime` between '{$date[0]}' and '{$date[1]}')";
    }
    select_query("fn_marklog", '*', $sql);
    while($con = db_fetch_array()){
        $cons[] = $con;
        $data['data'][] = array($con['id'], $username, $con['type'], $con['money'], $con['content'], $con['addtime']);
    }
    if (count($cons) == 0){
        $data['data'][] = null;
    }
    echo json_encode($data);
    exit();
}elseif($type == '2'){
    $id = $_GET['userid'];
    $time = $_GET['time'] == "" ? date('Y-m-d'): $_GET['time'];
    $date = $_GET['datetype'];
    if(empty($date)){
        if($_GET['time']){
            $date = explode(' - ' , $_GET['time']);
        }else{
            //$date = [date('Y-m-d'),date('Y-m-d')];
        }
    }else{
        $date = explode(',' , $date);
    }
    $code = $_GET['code'];
    $allmoney = 0;
    $allstatus = 0;
    $id = get_query_val('fn_user', 'userid', array('id' => $id));
    $sql = " roomid = '{$_SESSION['agent_room']}' and userid = '{$id}'";
    if($code && $code != 'undefined'){
        $sql = " and type = '" . getGameIdByCode($code) . "' and ";
    }
    if(count($date) == 2){
        $date[0] = date('Y-m-d 06:00:00' , strtotime($date[0]));
        $date[1] = date('Y-m-d 05:59:59' , strtotime($date[1] . ' + 1day'));
        $sql .= " and (`addtime` between '{$date[0]}' and '{$date[1]}')";
    }
    select_query('fn_order', '*', $sql);
    $cons = $cons = [];
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $peilv = $con['peilv'];
        //$peilv = getPeilv($con['userid'] , $con['roomid'] , $con['type'] , $con['mingci'] , $con['content']);
        if(getGameCodeById($con['type']) == 'jnd28'){
            $content = $con['content'];
        }else{
            $mingci = ((is_numeric($con['mingci']) && $con['mingci']==0)?10:$con['mingci']);
            $content = ((is_numeric($con['content']) && $con['content']==0)?10:$con['content']);
            //$content = (is_numeric($content))?('第' . $content . '车道'):$content;
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
            $content = $mingci.' / '.$content;
        }
        $content .= ' / 赔率：' . $peilv;
        if($con['status'] != '已撤单')$allmoney += (int)$con['money'];
        if($con['status'] > 0)$allstatus += $con['status'];
        $data['data'][] = array('#' . $con['id'], $con['username'], getGameTxtName($con['type']), $con['term'], $content, $con['money'], $con['addtime'], $con['status']);
    }



//    if($code == "" || $code == 'pk10' || $code == 'xyft'){
//        select_query('fn_order', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
//        while($con = db_fetch_array()){
//            $game = strlen($con['term']) < 8 ? '澳洲幸运10' : '幸运飞艇';
//            if($code == 'pk10' && $game == '幸运飞艇')continue;
//            if($code == 'xyft' && $game == '澳洲幸运10')continue;
//            $cons[] = $con;
//            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
//            if($con['status'] > 0)$allstatus += $con['status'];
//            $data['data'][] = array('#' . $con['id'], $con['username'], $game, $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
//        }
//    }
//    if($code == '' || $code == 'cqssc'){
//        select_query('fn_order', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
//        while($con = db_fetch_array()){
//            $cons[] = $con;
//            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
//            if($con['status'] > 0)$allstatus += $con['status'];
//            $data['data'][] = array('#' . $con['id'], $con['username'], '重庆时时彩', $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
//        }
//    }
//    if($code == '' || $code == 'xy28' || $code == 'jnd28'){
//        select_query('fn_order', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
//        while($con = db_fetch_array()){
//            $game = (int)$con['term'] > 2000000 ? '加拿大28' : '幸运28';
//            if($code == 'xy28' && $game == '加拿大28')continue;
//            if($code == 'jnd28' && $game == '幸运28')continue;
//            $cons[] = $con;
//            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
//            if($con['status'] > 0)$allstatus += $con['status'];
//            $data['data'][] = array('#' . $con['id'], $con['username'], $game, $con['term'], $con['content'], $con['money'], $con['addtime'], $con['status']);
//        }
//    }
//    if($code == '' || $code == 'jsmt'){
//        select_query('fn_order', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
//        while($con = db_fetch_array()){
//            $cons[] = $con;
//            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
//            if($con['status'] > 0)$allstatus += $con['status'];
//            $data['data'][] = array('#' . $con['id'], $con['username'], '极速摩托', $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
//        }
//    }
//    if($code == '' || $code == 'jssc'){
//        select_query('fn_jsscorder', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
//        while($con = db_fetch_array()){
//            $cons[] = $con;
//            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
//            if($con['status'] > 0)$allstatus += $con['status'];
//            $data['data'][] = array('#' . $con['id'], $con['username'], '极速赛车', $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
//        }
//    }
//    if($code == '' || $code == 'jsssc'){
//        select_query('fn_jssscorder', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
//        while($con = db_fetch_array()){
//            $cons[] = $con;
//            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
//            if($con['status'] > 0)$allstatus += $con['status'];
//            $data['data'][] = array('#' . $con['id'], $con['username'], '极速时时彩', $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
//        }
//    }
    if (count($cons) == 0){
        $data['data'][] = null;
    }
    $allstatus = $allstatus - $allmoney;
    $data['allmoney'] = sprintf("%.2f", substr(sprintf("%.3f", $allmoney), 0, -2));
    $data['allstatus'] = sprintf("%.2f", substr(sprintf("%.3f", $allstatus), 0, -2));
    echo json_encode($data);
    exit();
}elseif($type == '3'){
    $term = $_GET['term'];
    $game = $_GET['game'];

    if($game == 'jnd28'){
        select_query('fn_order', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }else{
        select_query('fn_order', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        $allz = 0;
        $allm = 0;
        while($con = db_fetch_array()){
            $cons[] = $con;

            $mingci = ((is_numeric($con['mingci']) && $con['mingci']==0)?10:$con['mingci']);
            $content = ((is_numeric($con['content']) && $con['content']==0)?10:$con['content']);
            //$content = (is_numeric($content))?('第' . $content . '车道'):$content;
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
            $arr['data'][] = array($con['id'], $con['username'], $mingci . '/' . $content, $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }
    return ;
    if($game == 'pk10' || $game == 'xyft'){
        select_query('fn_order', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        $allz = 0;
        $allm = 0;
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }elseif($game == 'cqssc'){
        select_query('fn_order', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }elseif($game == 'xy28' || $game == 'jnd28'){
        select_query('fn_order', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }elseif($game == 'jsmt'){
        select_query('fn_order', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }elseif($game == 'jssc'){
        select_query('fn_jsscorder', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }elseif($game == 'jsssc'){
        select_query('fn_jssscorder', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }
}
?>