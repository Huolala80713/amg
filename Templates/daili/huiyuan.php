<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "../user/function.php";
$user_id = $_GET['userid'] == "" ? $_SESSION['userid'] : $_GET['userid'];
$user_ids = getAgentUserList($user_id , 1);
$list = tuanduilist($user_ids, $_GET['p']>0?$_GET['p']:1 , 10);
$user_list = $list['list'];
$page = getPageList($list['count'] , 10 , '/Templates/daili/huiyuan.php');
?>
<!DOCTYPE html>
<html data-dpr="1" style="font-size: 48px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>会员管理</title>
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
        <span class="navbar-title" style="">会员管理</span>
    </div>
</header>
<div style="position: relative;background-color:rgba(195, 197, 194, 0.33);padding: 15px;">
    <input placeholder="下级用户查询" name="userid" id="userid" type="text" style="width: 100%;font-size: 16px;padding: 10px;height: 45px;line-height:25px;border-radius: 8px;border: 0;outline: none;background: #fff;" value="<?php echo $_GET['userid'];?>">

    <input type="button" class="btn-block b-blue sub" value="查询" style="width: 60px;height:30px;line-height: 30px;border-radius: 5px;margin: 0;position: absolute;right:25px;top: 22.5px;font-size: 16px;">
</div>
<div class="total-log" style="margin-top: 0;padding: 0;">
    <table class="total-table" style="font-size: 0.3rem;">
        <tbody>
        <tr>
            <th>账号</th>
            <th>昵称</th>
            <th>登录时间</th>
            <th>下级用户</th>
        </tr>
        <?php
        if(!empty($user_list)):
            ?>
            <?php foreach ($user_list as $user){?>
            <tr onclick="updateuser('<?php echo $user['userid'];?>')">
                <td><?php echo $user['userid'];?></td>
                <td><?php echo $user['username'];?></td>
                <td><?php echo date('y-m-d' , $user['statustime']);?><br><?php echo date('H:i:s' , $user['statustime']);?></td>
                <td><span style="text-decoration: underline;color: red;"><?php echo count(getAgentUserList($user['userid'] , 1));?></span></td>
            </tr>
            <?php }?>
        <?php endif;?>
        </tbody>
    </table>
</div>
<div id="popBox" class="_problemBox" style="">
    <div class="blackBg"></div>
    <div class="moreLayer">
        <ul>
            <li>
                <a class="username"></a>
            </li>
            <li>
                <a class="chakan">查看下级</a>
            </li>
            <li>
                <a class="baobiao">数据报表</a>
            </li>
<!--            <li>-->
<!--                <a class="updatefandian">修改返点</a>-->
<!--            </li>-->
        </ul>
        <ul>
            <li>
                <a class="cancel">取消</a>
            </li>
        </ul>
    </div>
</div>
<?php if($page):?>
    <div class="pages anim anim-3 yema">
        <div class="page_panel">
            <?php echo $page;?>
        </div>
    </div>
<?php endif;?>
<script src="/Style/newweb/js/ydui.js"></script>
<script type="text/javascript">
    let user_id = '';
    $(".sub").click(function(){
        location.href='/Templates/daili/huiyuan.php?userid=' + $("#userid").val()
    });
    $(".cancel").click(function(){
        $('.username').text('');
        user_id = '';
    });
    $(".chakan").click(function(){
        location.href='/Templates/daili/huiyuan.php?userid=' + user_id;
    });
    $(".baobiao").click(function(){
        location.href='/Templates/daili/agentreport.php?userid=' + user_id;
    });
    $(".updatefandian").on('click' , function(){
        window.location.href="manageinvite.php?user_id=" + user_id;
    })
    function updateuser(userid){
        user_id = userid;
        $('.username').text(userid);
        $('#popBox').show();
    }

</script>
</body>
</html>