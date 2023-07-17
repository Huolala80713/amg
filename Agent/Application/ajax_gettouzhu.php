<?php include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$game = $_GET['g'];
if($game){
    $game_type = getGameIdByCode($game);
    $time = $_GET['time'] == "" ? date('Y-m-d'): $_GET['time'];

    $time = date('Y-m-d 06:00:00' , strtotime($time));
    $time2 = date('Y-m-d 05:59:59' , strtotime($time . ' + 1day'));


    $term =  $_GET['term'];
    $sql_ = $sql = "userid !='robot' and `roomid` = '{$_SESSION['agent_room']}' and `type` = {$game_type} and status != '已撤单'";
    if($term){
        $sql .= " and term = '{$term}'";
        $sql_ .= " and term = '{$term}'";
    }
    $touzhu_amount = 0;
    $paijian_amount = 0;
    if($game == 'jnd28'){
        select_query("fn_order", 'sum(money) as money,content', $sql . ' group by content');
        $list = [];
        while($con = db_fetch_array()){
            $cons[] = $con;
            $list[$con['content']] = number_format($con['money'] , 2 , '.' , '');
        }
        $touzhu_amount = get_query_val('fn_order' , 'sum(money)' , $sql);
        $paijian_amount = get_query_val('fn_order' , 'sum(status)' , $sql . ' and status > 0 and status != "未结算"');
        select_query('fn_order' , 'count(userid) as counts' , $sql . ' group by userid');
        $users = [];
        while($cons = db_fetch_array()){
            $users[] = $cons;
        }
        $arr['status'] = 1;
        $arr['data'] = [
            'list' => $list,
            'user_count' => count($users),
            'amount' => number_format($touzhu_amount , 2 , '.' , ''),
            'paijian_amount' => number_format($paijian_amount , 2 , '.' , ''),
            'yingkui' => number_format($paijian_amount - $touzhu_amount , 2 , '.' , '')
        ];
    }else if($game == 'xglhc'){
//        $wanfa = htmlspecialchars($_GET['wanfa']);
//        switch ($wanfa){
//            case 1:
//                $sql .= 'and (mingci = 3 or mingci = 4 or mingci = 5 or mingci = 6)';
//                break;
//            case 2:
//                $sql .= 'and (mingci = 7 or mingci = 8 or mingci = 9 or mingci = "0")';
//                break;
//            default:
//                $sql .= 'and (mingci = "和" or mingci = 1 or mingci = 2)';
//                break;
//        }
            select_query("fn_order", 'sum(money) as money,content,mingci', $sql . ' group by mingci,content');
            $list = [];
            while($con = db_fetch_array()){
                $cons[] = $con;
                if(is_numeric($con['mingci']) && $con['mingci'] == 0){
                    $con['mingci'] = 10;
                }
                if(is_numeric($con['content']) && $con['content'] == 0){
                    $con['content'] = 10;
                }
                if(is_numeric($con['content']) && $con['content'] < 10){
                    $con['content'] = '0' . $con['content'];
                }
                $list[str_replace('#','-',$con['mingci'])] = number_format($con['money'] , 2 , '.' , '');
            }
            $touzhu_amount = get_query_val('fn_order' , 'sum(money)' , $sql);
            $paijian_amount = get_query_val('fn_order' , 'sum(status)' , $sql . ' and status > 0 and status != "未结算"');

            select_query('fn_order' , 'count(userid) as counts' , $sql . ' group by userid');
            $users = [];
            while($cons = db_fetch_array()){
                $users[] = $cons;
            }
            $arr['status'] = 1;
            $arr['data'] = [
                'list' => $list,
                'user_count' => count($users),
                'amount' => number_format($touzhu_amount , 2 , '.' , ''),
                'paijian_amount' => number_format($paijian_amount , 2 , '.' , ''),
                'yingkui' => number_format($paijian_amount - $touzhu_amount , 2 , '.' , ''),
            ];
    }else{
//        $wanfa = htmlspecialchars($_GET['wanfa']);
//        switch ($wanfa){
//            case 1:
//                $sql .= 'and (mingci = 3 or mingci = 4 or mingci = 5 or mingci = 6)';
//                break;
//            case 2:
//                $sql .= 'and (mingci = 7 or mingci = 8 or mingci = 9 or mingci = "0")';
//                break;
//            default:
//                $sql .= 'and (mingci = "和" or mingci = 1 or mingci = 2)';
//                break;
//        }
        select_query("fn_order", 'sum(money) as money,content,mingci', $sql . ' group by mingci,content');
        $list = [];
        while($con = db_fetch_array()){
            $cons[] = $con;
            if(is_numeric($con['mingci']) && $con['mingci'] == 0){
                $con['mingci'] = 10;
            }
            if(is_numeric($con['content']) && $con['content'] == 0){
                $con['content'] = 10;
            }
            if(is_numeric($con['content']) && $con['content'] < 10){
                $con['content'] = '0' . $con['content'];
            }
            $list[$con['mingci'].$con['content']] = number_format($con['money'] , 2 , '.' , '');
        }
        $touzhu_amount = get_query_val('fn_order' , 'sum(money)' , $sql);
        $paijian_amount = get_query_val('fn_order' , 'sum(status)' , $sql . ' and status > 0 and status != "未结算"');

        $gj = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = 1');
        $yj = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = 2');
        $san = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = 3');
        $si = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = 4');
        $wu = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = 5');
        $liu = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = 6');
        $qi = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = 7');
        $ba = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = 8');
        $jiu = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = 9');
        $shi = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = "0"');


        $gyhe = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = "和" and (content != "大" and content != "小" and content != "单" and content != "双")');

        $gydx = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = "和" and (content = "大" or content = "小")');

        $gyds = get_query_val('fn_order' , 'sum(money)' , $sql_ . ' and mingci = "和" and (content = "单" or content = "双")');

        select_query('fn_order' , 'count(userid) as counts' , $sql . ' group by userid');
        $users = [];
        while($cons = db_fetch_array()){
            $users[] = $cons;
        }
        $arr['status'] = 1;
        $arr['data'] = [
            'list' => $list,
            'user_count' => count($users),
            'amount' => number_format($touzhu_amount , 2 , '.' , ''),
            'paijian_amount' => number_format($paijian_amount , 2 , '.' , ''),
            'yingkui' => number_format($paijian_amount - $touzhu_amount , 2 , '.' , ''),
            'shi' => number_format($shi , 2 , '.' , ''),
            'jiu' => number_format($jiu , 2 , '.' , ''),
            'ba' => number_format($ba , 2 , '.' , ''),
            'qi' => number_format($ba , 2 , '.' , ''),
            'liu' => number_format($liu , 2, '.' , ''),
            'wu' => number_format($wu , 2 , '.' , ''),
            'si' => number_format($si , 2 , '.' , ''),
            'san' => number_format($san , 2 , '.' , ''),
            'yj' => number_format($yj , 2, '.' , ''),
            'gj' => number_format($gj , 2 , '.' , ''),
            'gyhe' => number_format($gyhe , 2 , '.' , ''),
            'gydx' => number_format($gydx , 2 , '.' , ''),
            'gyds' => number_format($gyds , 2 , '.' , '')
        ];
    }
}else{
    $arr['status'] = 0;
    $arr['data'] = null;
}
echo json_encode($arr);
?>