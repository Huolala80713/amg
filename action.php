<?php
//2200-10-20 以post来的数据
//header("Content-type:text/html;charset=utf-8");
include_once("Public/config.php");

$do=isset($_GET['do'])?$_GET['do']:'';
$cando=['reg','xinyong_touzhu','guize','roomdoor','login','doreg','dologin','findroom','room','logout','gamelist','upop','pop-history','pop-history-pc','pop-forecast','forecast-pc','pop-clong','zoushi-pc','pop-zhudan','kefu','uploader','uppass','upuserid'];
if(in_array($do,$cando)){
    switch ($do){
        case 'reg':
            require 'Templates/reg.php';
            break;
        case 'doreg':
            $user_refexp = "/[a-zA-Z0-9]{6,16}/";
            $password_refexp = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d){6,16}/";
            $userName = htmlspecialchars($_POST['userName']);
            $userPass = htmlspecialchars($_POST['pass']);
            $repass = htmlspecialchars($_POST['repass']);
            $invite_code = htmlspecialchars($_POST['invite_code']);
            if($userName == ''){
                ajaxMsg('请输入用户名！',0);
            }
            if($userPass == ''){
                ajaxMsg('请输入密码！',0);
            }
            if($repass == ''){
                ajaxMsg('请再次输入密码！',0);
            }
            if($invite_code == ''){
                ajaxMsg('请输入邀请码！',0);
            }
//            if(!preg_match($user_refexp , $userName)){
//                ajaxMsg('请输入6-16位字母和数字组合的用户名！',0);
//            }
            if(!preg_match($password_refexp , $userPass)){
                ajaxMsg('请输入6-16位大写字母，小写字母和数字组合的登录密码！',0);
            }
            if($repass != $userPass){
                ajaxMsg('两次输入的密码不一致！',0);
            }
            $newid=0;
            $row=get_query_val('fn_user','id',['userid'=>$userName]);
            if(!empty($row)) ajaxMsg('用户名已被使用！',0);
            $avg=mt_rand(1,209);
            $avgimg='/Style/avt/'.$avg.'.jpg';
            $agent = [
                'userid' => '',
                'fandian' => '',
                'type' => 0
            ];
            if($invite_code){
                $agent = get_query_vals('fn_invite_code','userid,fandian,type',['invite_code'=>$invite_code]);
                if(empty($agent)){
                    ajaxMsg('未找到该邀请码！',0);
                }
            }
            insert_query("fn_user",[
				'userid'=>$userName,
				'uid'=>getUserId(6),
				'isagent'=>$agent['type']?'true':'false',
				'money'=>0,
				'agent'=>$agent['userid'],
				'fandian'=>$agent['fandian'],
				'invite_code'=>$invite_code,
				'roomid'=>$_SESSION['room_id']?$_SESSION['room_id']:$_SESSION['roomdefault_id'],
				'username'=>$userName,
				'userpass'=>md5($userPass),
				'headimg'=>$avgimg,
				'jia'=>'false',
				'create_time'=>time()
				],$newid);
            if($newid>0){
                update_query("fn_user" , ['username'=>'U' . $newid . rand(10000,99999)],['userid'=>$userName,'roomid'=>$_SESSION['room_id']?$_SESSION['room_id']:$_SESSION['roomdefault_id']]);
                $reg_user = get_query_val('fn_invite_code','reg_user',['invite_code'=>$invite_code]);
                $reg_user = explode(',' , $reg_user);
                $reg_user = array_unique(array_filter($reg_user));
                $reg_user[] = $userName;
                update_query('fn_invite_code', ['reg_user' => implode(',' , $reg_user)] , ['invite_code'=>$invite_code] );
                ajaxMsg('注册成功');
            }else{
                ajaxMsg('注册失败',0);
            }
            break;
        case 'login':
//            if(isset($_SESSION['user']) && $_SESSION['user']) {
//                header('Location:/action.php?do=roomdoor');
//            }
//            if(isset($_SESSION['roomid']) && $_SESSION['roomid']) {
//                header('Location:/action.php?do=roomdoor');
//            }
            require 'Templates/login.php';
            break;
        case 'dologin':
            $userName=$_POST['userName'];
            $userPass=md5($_POST['pass']);
            $newid=0;
            $msgs['userid']=$userName;
            $msgs['userpass'] = $userPass;
            $row = get_query_vals('fn_user','*',$msgs);//,'userPass'=>$userPass
            //var_dump($userPass);
//            $conn=mysqli_connect('127.0.0.1','123','123','123');//源码分享站--www.ymfxz.com
//            $sql = "select * from fn_user where userid="."'".$userName."'".' and userpass='."'".$userPass."'";
//            $count = mysqli_query($conn,$sql);
//            $count = mysqli_fetch_assoc($count);
            if(empty($row)){
                ajaxMsg('用户名或者密码错误！' , 0);
            }else{
		        if($row['is_black'] == 1){
                    ajaxMsg('账号异常，无法登录！' , 0);
                    return ;
                }
                $token = date('YmdHis') . uniqid();
                $_SESSION['user']=$row;
                $_SESSION['token']=$token;
                $_SESSION['userid']=$row['userid'];
                $_SESSION['username']=$row['username'];
                $_SESSION['headimg']=$row['headimg'];
                update_query('fn_user' , ['statustime'=>time(),'token'=>$token], ['userid'=>$row['userid']]);
                ajaxMsg('登录成功');
            }
            break;
        case "roomdoor":
            checkLogin();
            require 'Templates/roomdoor.php';
            break;
        case "kefu":
            require 'Templates/kefu.php';
            break;
        case 'findroom':
            $roomID = htmlspecialchars($_POST['room']);
            $invite_code = htmlspecialchars($_POST['invite_code']);
            $find_type = htmlspecialchars($_POST['find_type']);
            $userName=$_SESSION['userid'];
            if(!isset($_SESSION['user'])) ajaxMsg('请先登录',0);
            if(empty($roomID) && $find_type == 1) ajaxMsg('房间号不能为空',0);
            if(empty($invite_code) && $find_type == 2) ajaxMsg('房间号不能为空',0);

            $row=get_query_vals('fn_room','',['roomid'=>$roomID]);
            if(!$row) ajaxMsg('房间不存在',0);
            if(strtotime($row['roomtime']) - time() < 0) ajaxMsg('当前房间已到期，请提醒管理员进行续费!',0);

            $rowUser=get_query_vals('fn_user','*',['userid'=>$userName,'roomid'=>$roomID]);
            if(!$rowUser){
                $oldUser=get_query_vals('fn_user','*',['userid'=>$userName]);
                $addUser=[];
                $addUser['username']=$oldUser['username'];
                $addUser['userid']=$oldUser['userid'];
                $addUser['userpass']=$oldUser['userpass'];
                $addUser['headimg']=$oldUser['headimg'];
                $addUser['money']=0;
                $addUser['roomid']=$roomID;
                $addUser['statustime']=time();
                $addUser['fandian']=$oldUser['fandian'];
                $addUser['invite_code']=$oldUser['invite_code'];
                $addUser['agent']=$oldUser['agent'];
                insert_query("fn_user",$addUser,$newid);
            }else{
                update_query('fn_user' , ['statustime'=>time()], ['id'=>$rowUser['id']]);
            }
            $_SESSION['room']=$row;
            $_SESSION['roomid']=$roomID;
            ajaxMsg('进入房间');
            break;

        case "room":
            checkLogin();
            if(!isset($_SESSION['room'])) {
                header('Location:/action.php?do=roomdoor');
            }
//            if(!$_SESSION['roomid']) ajaxMsg('请先选择好房间',0);
//            if(!isset($_SESSION['user'])) ajaxMsg('请先登录',0);
            if(isset($_GET['game'])&&$_GET['game']>0&&isset($gmidAli[$_GET['game']])){
                setcookie('game',$gmidAli[$_GET['game']] , time() + 24 * 3600);
                $_COOKIE['game']=$gmidAli[$_GET['game']];
            }elseif(!isset($_COOKIE['game'])){
//                ajaxMsg('请选择游戏',0);
                header('Location:/action.php?do=gamelist');
            }
            if($_GET['game'] == 5){
                require 'Templates/room_pc.php';
                break;
            }else{
            if($_GET['game'] == 1){
                require 'Templates/room_az10.php';
                break;
            }else{
                if($_GET['game'] == 2){
                    require 'Templates/room_xyft.php';
                    break;
                }else{
                require 'Templates/room.php';
                break;
           }
            }
             }
        case 'logout':
            setcookie('userid',null , -time() + 3600);
            $_COOKIE['userid'] = null;
            session_destroy();
            header('Location:/fn.php');
            break;
        case "gamelist":
            checkLogin();
            if(!isset($_SESSION['room'])) {
                header('Location:/action.php?do=roomdoor');
            }
//            if(!$_SESSION['roomid']) ajaxMsg('请先选择好房间',0);

            //
            include_once("Public/config_lhc.php");
            $list=[];
            $game_list = getGameList();
            foreach ($game_list as $key => $val){
                $info = get_query_vals('fn_lottery' . $key, 'gameopen', array('roomid' => $_SESSION['roomid']));
                if($key == 9){
                    $list[$key]=['game_id'=>$key,'game_name'=>$val,'is_open'=>$info['gameopen'],'opinfo'=>getLhcOpenInfo(getGameCodeById($key) , 1)];
                }else{
                    $list[$key]=['game_id'=>$key,'game_name'=>$val,'is_open'=>$info['gameopen'],'opinfo'=>getOpenInfo(getGameCodeById($key) , 1)];
                }

            }
            //var_dump($list);
            krsort($list);
            // $info1 = get_query_vals('fn_lottery1', 'gameopen', array('roomid' => $_SESSION['roomid']));
            // $info2 = get_query_vals('fn_lottery2', 'gameopen', array('roomid' => $_SESSION['roomid']));
            // $info3 = get_query_vals('fn_lottery3', 'gameopen', array('roomid' => $_SESSION['roomid']));
            // $info4 = get_query_vals('fn_lottery4', 'gameopen', array('roomid' => $_SESSION['roomid']));
            // $info5 = get_query_vals('fn_lottery5', 'gameopen', array('roomid' => $_SESSION['roomid']));
            // $info6 = get_query_vals('fn_lottery6', 'gameopen', array('roomid' => $_SESSION['roomid']));
            // $info7 = get_query_vals('fn_lottery7', 'gameopen', array('roomid' => $_SESSION['roomid']));
            // $info8 = get_query_vals('fn_lottery8', 'gameopen', array('roomid' => $_SESSION['roomid']));
            // $list=[];
            // $list[]=['game_id'=>4,'is_open'=>$info4['gameopen'],'opinfo'=>getOpenInfo('xy28' , 1)];
            // $list[]=['game_id'=>6,'is_open'=>$info6['gameopen'],'opinfo'=>getOpenInfo('jsmt' , 1)];
            // $list[]=['game_id'=>8,'is_open'=>$info8['gameopen'],'opinfo'=>getOpenInfo('jsssc' , 1)];
            // $list[]=['game_id'=>7,'is_open'=>$info7['gameopen'],'opinfo'=>getOpenInfo('jssc' , 1)];
            // $list[]=['game_id'=>3,'is_open'=>$info3['gameopen'],'opinfo'=>getOpenInfo('cqssc' , 1)];
            // $list[]=['game_id'=>5,'is_open'=>$info5['gameopen'],'opinfo'=>getOpenInfo('jnd28' , 1)];
            // $list[]=['game_id'=>2,'is_open'=>$info2['gameopen'],'opinfo'=>getOpenInfo('xyft' , 1)];
            // $list[]=['game_id'=>1,'is_open'=>$info1['gameopen'],'opinfo'=>getOpenInfo('pk10' , 1)];
//            print_r($list);exit;
            require 'Templates/gamelist.php';
            break;
        case "upop":
            global $gmidAli;
            if(!isset($_SESSION['user'])) ajaxMsg('请先登录',0);
            $gameList=$_GET['gid'];
            $returnList=[];
            foreach ($gameList as $gid){
                if($gid == 9){
                    $openInfo=getLhcOpenInfo($gmidAli[$gid] , 1);
                }else{
                    $openInfo=getOpenInfo($gmidAli[$gid] , 1);
                }
                $openInfo['gameid']=$gid;
                $returnList[]=$openInfo;
            }
            ajaxMsg(['list'=>$returnList]);
            break;
        case "pop-history":
            ob_start();
            require_once "Templates/history.php";
            $html=ob_get_contents();
            ob_end_clean();
            ajaxMsg(['html'=>$html]);
            break;
        case "pop-history-pc":
            ob_start();
            require_once "Templates/history_pc.php";
            $html=ob_get_contents();
            ob_end_clean();
            ajaxMsg(['html'=>$html]);
            break;
        case "pop-forecast":
            ob_start();
            require_once "Templates/forecast.php";
            $html=ob_get_contents();
            ob_end_clean();
            ajaxMsg(['html'=>$html]);
            break;
        case "forecast-pc":
            ob_start();
            require_once "Templates/forecast_pc.php";
            $html=ob_get_contents();
            ob_end_clean();
            ajaxMsg(['html'=>$html]);
            break;
        case "pop-clong":
            ob_start();
            require_once "Templates/changlong.php";
            $html=ob_get_contents();
            ob_end_clean();
            ajaxMsg(['html'=>$html]);
            break;
        case "zoushi-pc":
            ob_start();
            require_once "Templates/zoushi_pc.php";
            $html=ob_get_contents();
            ob_end_clean();
            ajaxMsg(['html'=>$html]);
            break;
        case "pop-zhudan":
            ob_start();
            require_once "Templates/zhudan.php";
            $html=ob_get_contents();
            ob_end_clean();
            ajaxMsg(['html'=>$html]);
            break;
        case "uploader":
            require_once "Templates/uploader.php";
            break;
        case "uppass":
            $password_refexp = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d){6,16}/";
            $userPass = htmlspecialchars($_POST['newpass']);
            if(!preg_match($password_refexp , $userPass)){
                ajaxMsg('请输入6-16位大写字母，小写字母和数字组合的登录密码！',0);
            }
            $pass=md5($userPass);
            update_query("fn_user", array("userpass" => $pass,"is_change_pw" => 1), array('userid' => $_SESSION['userid']));
            ajaxMsg(['status'=>1,'msg'=>'操作成功']);
            break;
        case "upuserid":
            $username=strip_tags($_POST['username']);
            update_query("fn_user", array('username' => $username), array("userid" => $_SESSION['userid']));
            $_SESSION['username']=$username;
            ajaxMsg(['status'=>1,'msg'=>'操作成功']);
            break;
        case "guize":
            if(!isset($_SESSION['user'])) ajaxMsg('请先登录',0);
            if(!$_SESSION['roomid']) ajaxMsg('请先选择好房间',0);
            $list[] = get_query_vals('fn_lottery1', '1 as id,gameopen,rules', array('roomid' => $_SESSION['roomid']));
            $list[] = get_query_vals('fn_lottery2', '2 as id,gameopen,rules', array('roomid' => $_SESSION['roomid']));
            $list[] = get_query_vals('fn_lottery3', '3 as id,gameopen,rules', array('roomid' => $_SESSION['roomid']));
            $list[] = get_query_vals('fn_lottery4', '4 as id,gameopen,rules', array('roomid' => $_SESSION['roomid']));
            $list[] = get_query_vals('fn_lottery5', '5 as id,gameopen,rules', array('roomid' => $_SESSION['roomid']));
            $list[] = get_query_vals('fn_lottery6', '6 as id,gameopen,rules', array('roomid' => $_SESSION['roomid']));
            $list[] = get_query_vals('fn_lottery7', '7 as id,gameopen,rules', array('roomid' => $_SESSION['roomid']));
            $list[] = get_query_vals('fn_lottery8', '8 as id,gameopen,rules', array('roomid' => $_SESSION['roomid']));
            require_once "Templates/guize.php";
            break;
        case 'xinyong_touzhu':
            ob_start();
            require_once "Templates/xinyong_touzhu.php";
            $html=ob_get_contents();
            ob_end_clean();
            ajaxMsg(['html'=>$html]);
            break;
        default:
            break;
    }
}

?>