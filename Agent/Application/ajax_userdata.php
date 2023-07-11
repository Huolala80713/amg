<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
if($type == 'add'){
    $user_refexp = "/[a-zA-Z0-9]{6,16}/";
    $password_refexp = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d){6,16}/";
    $username = htmlspecialchars($_GET['username']);
    $password = htmlspecialchars($_GET['password']);
    $agent = htmlspecialchars($_GET['agent']);
    if($username == ''){
        ajaxMsg('请输入用户名！',0);
    }
    if($password == ''){
        ajaxMsg('请输入密码！',0);
    }
    if(!preg_match($password_refexp , $password)){
        ajaxMsg('请输入6-16位大小写字母和数字组合的登录密码！',0);
    }
    $newid=0;
    $row=get_query_val('fn_user','id',['userid'=>$username]);
    if(!empty($row)) ajaxMsg('用户名已被使用！',0);
    $avg=mt_rand(1,209);
    $avgimg='/Style/avt/'.$avg.'.jpg';
    insert_query("fn_user",[
		'userid'=>$username,
		'uid'=>getUserId(6),
		'isagent'=>$agent,
		'money'=>0,
		'roomid'=>$_SESSION['agent_room'],
		'username'=>$username,
		'userpass'=>md5($password),
		'headimg'=>$avgimg,
		'jia'=>'false',
		'create_time'=>time()
		],$newid);
    if($newid>0){
        doLog('注册会员【' . $username . '】');
        ajaxMsg('注册成功');
    }else {
        ajaxMsg('注册失败',0);
    }
    die();
}elseif($type == 'getuser'){
    $time = $_GET['time'];
    $time = explode(' - ', $time);
    $time[0] = date('Y-m-d 06:00:00' , strtotime($time[0]));
    $time[1] = date('Y-m-d 05:59:59' , strtotime($time[1] . ' + 1day'));
    $arr = array();
    select_query("fn_user", '*', "roomid = '{$_SESSION['agent_room']}' and jia = 'false'");
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
	//
	$ALL_sf = 0;
	$ALL_xf = 0;
	$ALL_yk = 0;
	$ALL_allm = 0;
	$fandian = 0;
    foreach($cons as $con){
        $sf = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '上分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");
        $xf = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '下分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");
        $allm = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
        $allz = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
        $fd = (double)get_query_val('fn_marklog','sum(`money`)',"roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `type` = '返点'  and (`addtime` between '{$time[0]}' and '{$time[1]}')");
        $yk = $allz - $allm;
        $yk = sprintf("%.2f", substr(sprintf("%.3f", $yk), 0, -2));
		if($allm>0 || $allz || $fd || $sf || $xf || $con['money'] > 0){
		//
		$ALL_sf = $ALL_sf + $sf;
		$ALL_xf = $ALL_xf + $xf;
		$ALL_yk = $ALL_yk + $yk;
		$ALL_allm = $ALL_allm + $allm;
        $fandian += $fd;

        $arr['data'][] = array($con['id'], $con['username'], $sf, $xf, $yk, $allm, round($fd , 4),$con['money'],"<a href=\"#\" onclick=\"disreport('".$con['id']."','".$con['username']."')\" class=\"btn btn-success btn-sm\">报表查询</a>");
		}
    }
	//zong gezi
	$arr['data'][] = array('合计', '', $ALL_sf, $ALL_xf, $ALL_yk, $ALL_allm,round($fandian , 4),'','');
	
    echo json_encode($arr);
}elseif($type == 'onetui'){
    $time = $_POST['time'];
    $time = date('Y-m-d 06:00:00' , strtotime($time));
    $time2 = date('Y-m-d 05:59:59' , strtotime($time . ' + 1day'));


    $mode = $_POST['mode'];
    $plan1 = $_POST['plan1'];
    $plan1s = $_POST['plan1s'];
    $plan2 = $_POST['plan2'] == '-' ? '': $_POST['plan2'];
    $plan2s = $_POST['plan2s'];
    $plan3 = $_POST['plan3'] == '-' ? '': $_POST['plan3'];
    $plan3s = $_POST['plan3s'];
    if($plan1 != '' && $plan1s != ''){
        $plan1 = explode('-', $plan1);
        $plan1s = $plan1s / 100;
        if($plan2 != '' && $plan2s != ''){
            $plan2 = explode('-', $plan2);
            $plan2s = $plan2s / 100;
        }
        if($plan3 != '' && $plan3s != ''){
            $plan3 = explode('-', $plan3);
            $plan3s = $plan3s / 100;
        }
    }else{
        echo json_encode(array("success" => false, "msg" => "方案没有填写!!"));
        exit();
    }
    $arr = array();
    switch($mode){
    case 'liushui': $arr['mode'] = '流水';
        break;
    case "kuisun": $arr['mode'] = '亏损';
        break;
    case "yingli": $arr['mode'] = '盈利';
        break;
    case "shangfen": $arr['mode'] = '上分';
        break;
    case "xiafen": $arr['mode'] = '下分';
        break;
    }
    select_query("fn_user", '*', "roomid = '{$_SESSION['agent_room']}' and jia = 'false'");
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        switch($mode){
        case 'liushui': $liushui = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $liushui += (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $liushui += (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $liushui += (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $liushui += (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $liushui += (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
                $tui = $liushui * $plan1s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
    //            continue;
            }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
                $tui = $liushui * $plan2s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
    //            continue;
            }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
                $tui = $liushui * $plan3s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
    //            continue;
            }else{
    //            continue;
            }
            break;
        case "kuisun": $m = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $z - $m;
            $m = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $yk + ($z - $m);
            $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $yk + ($z - $m);
            $m = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $yk + ($z - $m);
            $m = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $yk + ($z - $m);
            $m = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $yk + ($z - $m);
            if($yk < 0){
                $yk = abs($yk);
                if(($yk > $plan1[0] && $yk < $plan1[1]) && $plan1s != ''){
                    $tui = $yk * $plan1s;
                    用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                    $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
    //                continue;
                }elseif(($yk > $plan2[0] && $yk < $plan2[1]) && $plan2s != ''){
                    $tui = $yk * $plan2s;
                    用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                    $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
    //                continue;
                }elseif(($yk > $plan3[0] && $yk < $plan3[1]) && $plan3s != ''){
                    $tui = $yk * $plan3s;
                    用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                    $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
    //                continue;
                }else{
    //                continue;
                }
            }
            break;
        case "yingli": $m = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $z - $m;
            $m = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $yk + ($z - $m);
            $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $yk + ($z - $m);
            $m = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $yk + ($z - $m);
            $m = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $yk + ($z - $m);
            $m = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time}' and '{$time2}')  and (status > 0 or status < 0)");
            $z = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time}' and '{$time2}') and status > 0 and userid = '{$con['userid']}'");
            $yk = $yk + ($z - $m);
            if($yk > 0){
                if(($yk > $plan1[0] && $yk < $plan1[1]) && $plan1s != ''){
                    $tui = $yk * $plan1s;
                    用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                    $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
    //                continue;
                }elseif(($yk > $plan2[0] && $yk < $plan2[1]) && $plan2s != ''){
                    $tui = $yk * $plan2s;
                    用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                    $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
    //                continue;
                }elseif(($yk > $plan3[0] && $yk < $plan3[1]) && $plan3s != ''){
                    $tui = $yk * $plan3s;
                    用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                    $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
    //                continue;
                }else{
    //                continue;
                }
            }
            break;
        case "shangfen": $liushui = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '上分' and status = '已处理' and (`time` between '{$time}' and '{$time2}')");
            if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
                $tui = $liushui * $plan1s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
    //            continue;
            }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
                $tui = $liushui * $plan2s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
    //            continue;
            }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
                $tui = $liushui * $plan3s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
    //            continue;
            }else{
    //            continue;
            }
            break;
        case "xiafen": $liushui = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '下分' and status = '已处理' and (`time` between '{$time}' and '{$time2}')");
            if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
                $tui = $liushui * $plan1s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
    //            continue;
            }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
                $tui = $liushui * $plan2s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
    //            continue;
            }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
                $tui = $liushui * $plan3s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
    //            continue;
            }else{
    //            continue;
            }
            break;
        }
    }
$arr['success'] = true;
echo json_encode($arr);
exit();
}elseif($type == 'twotui'){
$time = $_POST['time'];
$time = explode(' - ', $time);
$time[0] = date('Y-m-d 06:00:00' , strtotime($time[0]));
$time[1] = date('Y-m-d 05:59:59' , strtotime($time[1] . ' + 1day'));
$mode = $_POST['mode'];
$plan1 = $_POST['plan1'];
$plan1s = $_POST['plan1s'];
$plan2 = $_POST['plan2'] == '-' ? '': $_POST['plan2'];
$plan2s = $_POST['plan2s'];
$plan3 = $_POST['plan3'] == '-' ? '': $_POST['plan3'];
$plan3s = $_POST['plan3s'];
if($plan1 != '' && $plan1s != ''){
    $plan1 = explode('-', $plan1);
    $plan1s = $plan1s / 100;
    if($plan2 != '' && $plan2s != ''){
        $plan2 = explode('-', $plan2);
        $plan2s = $plan2s / 100;
    }
    if($plan3 != '' && $plan3s != ''){
        $plan3 = explode('-', $plan3);
        $plan3s = $plan3s / 100;
    }
}else{
    echo json_encode(array("success" => false, "msg" => "方案没有填写!!"));
    exit();
}
$arr = array();
switch($mode){
case 'liushui': $arr['mode'] = '流水';
    break;
case "kuisun": $arr['mode'] = '亏损';
    break;
case "yingli": $arr['mode'] = '盈利';
    break;
case "shangfen": $arr['mode'] = '上分';
    break;
case "xiafen": $arr['mode'] = '下分';
    break;
}
select_query("fn_user", '*', "roomid = '{$_SESSION['agent_room']}' and jia = 'false'");
while($con = db_fetch_array()){
$cons[] = $con;
}
foreach($cons as $con){
switch($mode){
case 'liushui': $liushui = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $liushui += (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $liushui += (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $liushui += (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $liushui += (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $liushui += (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
        $tui = $liushui * $plan1s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
//        continue;
    }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
        $tui = $liushui * $plan2s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
//        continue;
    }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
        $tui = $liushui * $plan3s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
//        continue;
    }else{
//        continue;
    }
    break;
case "kuisun": $m = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $z - $m;
    $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    if($yk < 0){
        $yk = abs($yk);
        if(($yk > $plan1[0] && $yk < $plan1[1]) && $plan1s != ''){
            $tui = $yk * $plan1s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
//        continue;
        }elseif(($yk > $plan2[0] && $yk < $plan2[1]) && $plan2s != ''){
            $tui = $yk * $plan2s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
//        continue;
        }elseif(($yk > $plan3[0] && $yk < $plan3[1]) && $plan3s != ''){
            $tui = $yk * $plan3s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
//        continue;
        }else{
//        continue;
        }
    }
    break;
case "yingli": $m = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $z - $m;
    $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    if($yk > 0){
        if(($yk > $plan1[0] && $yk < $plan1[1]) && $plan1s != ''){
            $tui = $yk * $plan1s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
//        continue;
        }elseif(($yk > $plan2[0] && $yk < $plan2[1]) && $plan2s != ''){
            $tui = $yk * $plan2s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
//        continue;
        }elseif(($yk > $plan3[0] && $yk < $plan3[1]) && $plan3s != ''){
            $tui = $yk * $plan3s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
//        continue;
        }else{
//        continue;
        }
    }
    break;
case "shangfen": $liushui = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '上分' and status = '已处理' and (`time` between '{$time[0]}' and '{$time[1]}')");
    if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
        $tui = $liushui * $plan1s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
//        continue;
    }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
        $tui = $liushui * $plan2s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
//        continue;
    }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
        $tui = $liushui * $plan3s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
//        continue;
    }else{
//        continue;
    }
    break;
case "xiafen": $liushui = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '下分' and status = '已处理' and (`time` between '{$time[1]}' and '{$time[0]}')");
    if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
        $tui = $liushui * $plan1s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
//        continue;
    }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
        $tui = $liushui * $plan2s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
//        continue;
    }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
        $tui = $liushui * $plan3s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
//        continue;
    }else{
//        continue;
    }
    break;
}
}
$arr['success'] = true;
echo json_encode($arr);
exit();
}
function 用户_上分($Userid, $Money, $room, $time){
update_query('fn_user', array('money' => '+=' . $Money), array('userid' => $Userid, 'roomid' => $room));
insert_query("fn_marklog", array("roomid" => $room, 'userid' => $Userid, 'type' => '上分', 'content' => $time . '系统退水', 'money' => $Money, 'addtime' => 'now()'));
}
?>