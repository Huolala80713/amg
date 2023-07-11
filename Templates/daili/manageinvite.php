<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "../user/function.php";
$id = $_GET['id'];
$user_id = $_GET['user_id'];
$type = $_GET['type']?$_GET['type']:'create';
$invite_code_type = 'dl';
if($type == 'kaihu'){
    $title = '立即开户';
}else{
    $title = '生成邀请码';
}
if($id){
    $invite_code = get_query_val('fn_invite_code' , 'invite_code' , ['id'=>$id]);
    $type = 'update_invite';
    $title = '邀请码' . $invite_code . '修改返点';
}
if($user_id){
    $title = '用户' . $user_id . '修改返点';
    $type = 'update_user';
}
$game_list = getGameList();
$default_agent_list = [];
foreach ($game_list as $key => $game){
    $BetGame = getGameCodeById($key);
    $table = '';
    if ($BetGame == 'pk10') {
        $table = 'fn_lottery1';
    } elseif ($BetGame == 'xyft') {
        $table = 'fn_lottery2';
    } elseif ($BetGame == 'cqssc') {
        $table = 'fn_lottery3';
    } elseif ($BetGame == 'xy28') {
        $table = 'fn_lottery4';
    } elseif ($BetGame == 'jnd28') {
        $table = 'fn_lottery5';
    } elseif ($BetGame == 'jsmt') {
        $table = 'fn_lottery6';
    } elseif ($BetGame == 'jssc') {
        $table = 'fn_lottery7';
    } elseif ($BetGame == 'jsssc') {
        $table = 'fn_lottery8';
    }
    $default_agent_list[$key] = get_query_val($table , 'fandian' , ['roomid'=>$_SESSION['roomid']]);
}
$user = get_query_vals('fn_user', 'fandian,agent,isagent', ['userid'=>$_SESSION['userid'],'roomid'=>$_SESSION['roomid']]);
if($user['agent']){
    $agent_list = json_decode($user['fandian'] , true);
    $default_agent_list = $agent_list;
}else{
    $agent_list = $default_agent_list;
}
if($id){

    $invite_code = get_query_vals('fn_invite_code' , 'fandian,type' , ['id'=>$id]);
    $agent_list = json_decode($invite_code['fandian'] , true);
    $invite_code_type = $invite_code['type']?"dl":"wj";
}
if($user_id){
    $user = get_query_vals('fn_user' , 'fandian,isagent' , ['userid'=>$user_id]);
    $agent_list = json_decode($user['fandian'] , true);
    $invite_code_type = $user['isagent'] == 'true'?"dl":"wj";
}
if($_POST['do'] == 'create'){
    $fandian = [];
    foreach ($default_agent_list as $key=>$value){
        $fandian_val = $_POST[getGameCodeById($key)];
        $fandian_val = explode('.' , $fandian_val);
        if(count($fandian_val) == 2 && strlen($fandian_val[1]) > 2){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误,小数点后只能两位' , 0);
        }elseif(count($fandian_val) > 2){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误' , 0);
        }
        $fandian_val = implode('.' , $fandian_val);
        if($fandian_val === ''){
            return ajaxMsg('请设置游戏' . getGameTxtName($key) . '返点' , 0);
        }
        if($value < $fandian_val){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误，最大不能超过' . $value . '‱' , 0);
        }else{
            $fandian[$key] = $fandian_val;
        }
    }
    $data = [
        'roomid' => $_SESSION['roomid'],
        'userid' => $_SESSION['userid'],
        'invite_code' => getInviteCode(8),
        'add_time' => date('Y-m-d H:i:s'),
        'type' => $_POST['type'] == 'dl' ? 1 : 0,
        'fandian' => json_encode($fandian)
    ];
    insert_query('fn_invite_code' , $data);
    return ajaxMsg('邀请码生成成功' , 1);
}elseif($_POST['do'] == 'update_user'){
    if(empty($_POST['user_id'])){
        return ajaxMsg('参数错误' , 1);
    }
    $fandian = [];
    foreach ($default_agent_list as $key=>$value){
	//判断用户是否下单
        $sql = '';
        if(date('H') < 6){
            $time1 = date('Y-m-d 06:00:00' , strtotime('-1day'));
            $time2 = date('Y-m-d 05:59:59');
        }else{
            $time1 = date('Y-m-d 06:00:00');
            $time2 = date('Y-m-d 05:59:59' , strtotime('+1day'));
        }
//        $time1 = date('Y-m-d 06:00:00');
//        $time2 = date('Y-m-d 05:59:59' , strtotime('+1day'));
        $sql = "(addtime between '{$time1}' and '{$time2}') and ";
        $count = get_query_val('fn_order' , 'count(id) as counts' , $sql . "roomid='{$_SESSION['roomid']}' and userid = '{$_POST['user_id']}' and type = '{$key}'");
        if($count){
            return ajaxMsg('用户已经投注，请余' . $time2 . '后进行修改' , 1);
        }
        $fandian_val = $_POST[getGameCodeById($key)];
        $fandian_val = explode('.' , $fandian_val);
        if(count($fandian_val) == 2 && strlen($fandian_val[1]) > 2){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误,小数点后只能两位' , 0);
        }elseif(count($fandian_val) > 2){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误' , 0);
        }
        $fandian_val = implode('.' , $fandian_val);
        if($fandian_val === ''){
            return ajaxMsg('请设置游戏' . getGameTxtName($key) . '返点' , 0);
        }
        if($value < $fandian_val){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误，最大不能超过' . $value . '%' , 0);
        }else{
            $fandian[$key] = $fandian_val;
        }
    }
    update_query('fn_user', ['fandian' => json_encode($fandian)] , ['userid'=>$_POST['user_id']] );
    return ajaxMsg('修改成功' , 1);
}elseif($_POST['do'] == 'update_invite'){
    if(empty($_POST['id'])){
        return ajaxMsg('参数错误' , 1);
    }
    $fandian = [];
    foreach ($default_agent_list as $key=>$value){
        $fandian_val = $_POST[getGameCodeById($key)];
        $fandian_val = explode('.' , $fandian_val);
        if(count($fandian_val) == 2 && strlen($fandian_val[1]) > 2){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误,小数点后只能两位' , 0);
        }elseif(count($fandian_val) > 2){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误' , 0);
        }
        $fandian_val = implode('.' , $fandian_val);
        if($fandian_val === ''){
            return ajaxMsg('请设置游戏' . getGameTxtName($key) . '返点' , 0);
        }
        if($value < $fandian_val){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误，最大不能超过' . $value . '%' , 0);
        }else{
            $fandian[$key] = $fandian_val;
        }
    }
    update_query('fn_invite_code', ['fandian' => json_encode($fandian)] , ['id'=>$_POST['id']] );
    return ajaxMsg('修改成功' , 1);
}elseif($_POST['do'] == 'kaihu'){
    $fandian = [];
    foreach ($default_agent_list as $key=>$value){
        $fandian_val = $_POST[getGameCodeById($key)];
        $fandian_val = explode('.' , $fandian_val);
        if(count($fandian_val) == 2 && strlen($fandian_val[1]) > 2){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误,小数点后只能两位' , 0);
        }elseif(count($fandian_val) > 2){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误' , 0);
        }
        $fandian_val = implode('.' , $fandian_val);
        if($fandian_val === ''){
            return ajaxMsg('请设置游戏' . getGameTxtName($key) . '返点' , 0);
        }
        if($value < $fandian_val){
            return ajaxMsg('游戏' . getGameTxtName($key) . '返点设置错误，最大不能超过' . $value . '%' , 0);
        }else{
            $fandian[$key] = $fandian_val;
        }
    }
    $user_refexp = "/[a-zA-Z0-9]{6,16}/";
    $password_refexp = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d){6,16}/";
    $username = htmlspecialchars($_POST['username']);
    $userpass = htmlspecialchars($_POST['userpass']);
    if($username == ''){
        ajaxMsg('请输入用户名！',0);
    }
    if($userpass == ''){
        ajaxMsg('请输入密码！',0);
    }
    if(!preg_match($password_refexp , $userpass)){
        ajaxMsg('请输入6-16位字母和数字组合的登录密码！',0);
    }
    $newid=0;
    $row = get_query_val('fn_user','id',['userid'=>$username]);
    if(!empty($row)) ajaxMsg('用户名已被使用！',0);
    $avg=mt_rand(1,209);
    $avgimg='/Style/avt/'.$avg.'.jpg';
    $agent = [
        'userid' => $_SESSION['userid'],
        'fandian' => json_encode($fandian)
    ];

    $data = [
        'roomid' => $_SESSION['roomid'],
        'userid' => $_SESSION['userid'],
        'invite_code' => getInviteCode(8),
        'add_time' => date('Y-m-d H:i:s'),
        'type' => $_POST['type'] == 'dl' ? 1 : 0,
        'fandian' => json_encode($fandian)
    ];
    insert_query('fn_invite_code' , $data);
    insert_query("fn_user",[
		'invite_code'=>$data['invite_code'],
		'userid'=>$username,
		'uid'=>getUserId(6),
		'isagent'=>$data['type'] == 1 ? 'true' : 'false',
		'money'=>0,
		'agent'=>$agent['userid'],
		'fandian'=>$agent['fandian'],
		'roomid'=>$_SESSION['roomid'],
		'username'=>$username,
		'userpass'=>md5($userpass),
		'headimg'=>$avgimg,
		'jia'=>'false',
		'create_time'=>time()
		],$newid);
    if($newid>0){
        $reg_user = get_query_val('fn_invite_code','reg_user',['invite_code'=>$data['invite_code']]);
        $reg_user = explode(',' , $reg_user);
        $reg_user = array_unique(array_filter($reg_user));
        $reg_user[] = $username;
        update_query('fn_invite_code', ['reg_user' => implode(',' , $reg_user)] , ['invite_code'=>$data['invite_code']] );
        ajaxMsg('开户成功');
    }else{
        ajaxMsg('开户失败',0);
    }
}
?>
<!DOCTYPE html>
<html data-dpr="1" style="font-size: 48px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $title;?></title>
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <link rel="stylesheet" href="/Style/newweb/css/ydui.css">
    <link rel="stylesheet" href="/Style/newweb/css/demo.css">
    <link rel="stylesheet" href="/Style/newweb/css/common21.css">

    <link rel="stylesheet" type="text/css" href="/Style/newcss/iconfont/iconfont.css" />
    <script src="/Style/newweb/js/ydui.flexible.js"></script>
    <script src="/Style/newweb/js/rolldate.min.js"></script>
    <script src="/Style/newweb/js/jquery.js"></script>
    <script src="/Style/newweb/js/common.js"></script>
    <style type="text/css">
        ul{margin:0;padding:0}
        li{list-style-type:none}
        .rolldate-container{font-size:20px;color:#333;text-align:center}
        .rolldate-container header{position:relative;line-height:60px;font-size:18px;border-bottom:1px solid #e0e0e0}
        .rolldate-container .rolldate-mask{position:fixed;width:100%;height:100%;top:0;left:0;background:#000;opacity:.4;z-index:999}
        .rolldate-container .rolldate-panel{position:fixed;bottom:0;left:0;width:100%;height:273px;z-index:1000;background:#fff;-webkit-animation-duration:.3s;animation-duration:.3s;-webkit-animation-delay:0s;animation-delay:0s;-webkit-animation-iteration-count:1;animation-iteration-count:1}
        .rolldate-container .rolldate-btn{position:absolute;left:0;top:0;height:100%;padding:0 15px;color:#666;font-size:16px;cursor:pointer;-webkit-tap-highlight-color:transparent}
        .rolldate-container.wx .rolldate-btn{height:150%}
        .rolldate-container .rolldate-confirm{left:auto;right:0;color:#007bff}
        .rolldate-container .rolldate-content{position:relative;top:20px}
        .rolldate-container .rolldate-wrapper{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}
        .rolldate-container .rolldate-wrapper>div{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;height:173px;line-height:36px;overflow:hidden;-webkit-flex-basis:-8e;-ms-flex-preferred-size:-8e;flex-basis:-8e;width:1%}
        .rolldate-container .rolldate-wrapper ul{margin-top:68px}
        .rolldate-container .rolldate-wrapper li{height:36px}
        .rolldate-container .rolldate-dim{position:absolute;left:0;top:0;width:100%;height:68px;background:-webkit-gradient(linear,left bottom,left top,from(hsla(0,0%,100%,.4)),to(hsla(0,0%,100%,.8)));background:-webkit-linear-gradient(bottom,hsla(0,0%,100%,.4),hsla(0,0%,100%,.8));background:-o-linear-gradient(bottom,hsla(0,0%,100%,.4),hsla(0,0%,100%,.8));background:-webkit-gradient(linear, left bottom, left top, from(hsla(0, 0%, 100%, 0.4)), to(hsla(0, 0%, 100%, 0.8)));background:-webkit-linear-gradient(bottom, hsla(0, 0%, 100%, 0.4), hsla(0, 0%, 100%, 0.8));background:-o-linear-gradient(bottom, hsla(0, 0%, 100%, 0.4), hsla(0, 0%, 100%, 0.8));background:linear-gradient(0deg,hsla(0,0%,100%,.4),hsla(0,0%,100%,.8));pointer-events:none;-webkit-transform:translateZ(0);transform:translateZ(0);z-index:10}
        .rolldate-container .mask-top{border-bottom:1px solid #ebebeb}
        .rolldate-container .mask-bottom{top:auto;bottom:1px;border-top:1px solid #ebebeb}
        .rolldate-container .fadeIn{-webkit-animation-name:fadeIn;animation-name:fadeIn}
        .rolldate-container .fadeOut{-webkit-animation-name:fadeOut;animation-name:fadeOut}
        @-webkit-keyframes fadeIn{
            0%{bottom:-273px}
            to{bottom:0}
        }
        @keyframes fadeIn{
            0%{bottom:-273px}
            to{bottom:0}
        }
        @-webkit-keyframes fadeOut{
            0%{bottom:0}
            to{bottom:-273px;display:none}
        }
        @keyframes fadeOut{
            0%{bottom:0}
            to{bottom:-273px;display:none}
        }
        @media screen and (max-width:414px){
            .rolldate-container{font-size:18px}
        }
        @media screen and (max-width:320px){
            .rolldate-container{font-size:15px}
        }
        .total-log {
            box-sizing: border-box;
            padding: 0 8px;
            width: 100%;
            font-size: 0.25rem;
            background: #f6f6f6 ;
            margin-bottom: 0.1rem;
            margin-top: 0.3rem;
        }
        .total-table{
            width: 100%;
            text-align: center;
            border-color:#69594c;
            border-collapse: collapse;
        }
        .total-table tr th{
            color: #000;
            font-size: 18px;
            background: #efefef;
            font-weight: bold;
        }
        .total-table tr th,td{
            border-bottom:1px solid #ccc;
            padding: 15px 10px;
            font-size: 16px;
        }
        /*.total-table th{*/
        /*    background:#ddd;*/
        /*    color: #000;*/
        /*    font-weight: bold;*/
        /*    font-size: 0.25rem;*/
        /*}*/
        .total-table td{
            color:#000;
            background: #f3f3f3;
        }
        .cell-right input{
            text-align: right;
        }
        .sub{
            width: 80%;
            margin-left: 10%;
        }
        .btn1{
            width: 60%;
            height: 0.5rem;
            background: #3A4F90;
            color: #fff;
            border-radius: 5px;
            border: 0;
        }
    </style>
</head>
<body>

<header class="m-navbar" style="">
    <a href="javascript:history.back(-1)" class="navbar-item">
        <img src="/Style/newimg/leftar.png" style="height: 0.5rem;">
    </a>
    <div class="navbar-center">
        <span class="navbar-title" style=""><?php echo $title;?></span>
    </div>
</header>
<form id="agent">
<div class="m-cell" style="margin-top:0.35rem;">
    <label class="cell-right type" style="display: none">
    <input type="radio" name="type" value="dl" checked/>
    </label>
<!--    <div class="cell-item redio_panel">-->
<!--        <div class="cell-left" style="font-size: 17px;padding-right: 15px;">开户类型</div>-->
<!--        <label class="cell-right type">-->
<!--            <input type="radio" name="type" --><?php //if($invite_code_type == 'dl'):?><!--checked--><?php //endif;?><!-- value="dl"/>-->
<!--            <i class="cell-checkbox-icon"></i>代理类型-->
<!--        </label>-->
<!--        <label class="cell-right type">-->
<!--            <input type="radio" name="type" --><?php //if($invite_code_type == 'wj'):?><!--checked--><?php //endif;?><!-- value="wj" />-->
<!--            <i class="cell-checkbox-icon"></i>玩家类型-->
<!--        </label>-->
<!--    </div>-->
    <div class="cell-item redio_panel" style="border-top: 0.05rem solid #efefef">
        <div class="cell-left" style="line-height: 1rem;">请先为下级设置返点，<a href="rebatedesc.php" style="color: rgb(220, 59, 64);">点击查看返点赔率表</a></div>
    </div>
    <style>
        .cell-right{-webkit-justify-content:flex-start !important;justify-content:flex-start !important;font-size: 0.30rem;}
        .cell-right .cell-checkbox-icon{padding-right: 5px;}
        .redio_panel:after{border:0px !important;}
        .cell-input{
            height: 0.7rem;
            line-height: 0.7rem;
            border: 1px solid #cfcfcf;
            margin-top: 0.1rem;
            border-radius: 8px;
            padding-right: 30px;
            padding-left: 8px;
            text-align: left !important;
        }
        .cell-item:not(:last-child):after{
            border: 0;
        }
    </style>
</div>
<div class="m-cell" style="margin-top:0.35rem;">
    <?php if(in_array($type , ['kaihu'])):?>
    <div class="cell-item" style="display: flex;position: relative;border-bottom: 0;">
        <div class="cell-left" style="flex:1;font-size: 16px;">用户账号：</div>
        <div class="cell-right" style="flex:1;">
            <input type="text" placeholder="请输入用户账号" class="cell-input agent" name="username" value="">
        </div>
    </div>
    <div class="cell-item" style="display: flex;position: relative;border-bottom: 0;">
        <div class="cell-left" style="flex:1;font-size: 16px;">用户密码：</div>
        <div class="cell-right" style="flex:1;">
            <input type="password" placeholder="请输入用户密码" class="cell-input agent" name="userpass" value="">
        </div>
    </div>
    <?php endif;?>
    <?php foreach ($game_list as $key => $game):?>
    <div class="cell-item" style="display: flex;position: relative;border-bottom: 0;">
        <div class="cell-left" style="flex:1;font-size: 16px;"><?php echo $game;?>：</div>
        <div class="cell-right" style="flex:1;">
            <input type="text" placeholder="请输入返点" class="cell-input agent" name="<?php echo getGameCodeById($key);?>" value="<?php echo isset($agent_list[$key])?$agent_list[$key]:0;?>">
        </div>
        <div class="cell-right" style="min-height: auto;flex:0.1;position: absolute;height: 0.7rem;line-height: 0.7rem;top: 0.2rem;right: 0;width: 45px;text-align: center;">%</div>
    </div>
    <?php endforeach;?>
    <input type="hidden" name="do" value="<?php echo $type;?>">
    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
    <input type="hidden" name="id" value="<?php echo $id;?>">
</div>
</form>
<?php if($id){?>
    <input type="button" class="btn-block b-blue sub" value="修改邀请码返点">
<?php }elseif($user_id){?>
    <input type="button" class="btn-block b-blue sub" value="修改用户返点">
<?php }else{?>
    <input type="button" class="btn-block b-blue sub" value="<?php echo $title;?>">
<?php }?>

<script src="/Style/newweb/js/ydui.js"></script>
<link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
<script type="text/javascript" src="/Style/pop/js/popups.js"></script>
<script type="text/javascript">
    $(".sub").on('click' , function(){
        let dotype = '<?php echo $type?>';
        let type = $('.type').find('input[type="radio"]:checked').val();
        var data = $('#agent').serializeArray();
        $.ajax({
            url:location.href,
            dataType:'json',
            type:'post',
            data:data,
            success:function(res){
                if(res.status==1){
                    jqtoast(res.msg,2500);
                    setTimeout(function () {
                        if(dotype == 'update_invite'){
                            location.href = 'invitecode.php?type=<?php echo $invite_code_type;?>';
                        }else if(dotype == 'update_user'){
                            location.href = 'huiyuan.php';
                        }else{
                            location.href = 'invitecode.php?type=' + type;
                        }
                    },2500)
                }else{
                    jqtoast(res.msg);
                }
            },
            error:function (){
                return jqtoast('网络错误');
            }
        });
    })
</script>
</body>
</html>
