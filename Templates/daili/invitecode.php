<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "../user/function.php";
if($_POST['do'] == 'del'){
    $id = htmlspecialchars($_POST['id']);
    if(empty($id)){
        return ajaxMsg('参数错误' , 0);
    }
    delete_query('fn_invite_code' , ['id'=>$id]);
    return ajaxMsg('删除成功' , 1);
}
$user_id = $_GET['userid'] == "" ? $_SESSION['userid'] : $_GET['userid'];
$type = $_GET['type']?$_GET['type']:'dl';
$list = invitecodelist($user_id , $type , $_GET['p']>0?$_GET['p']:1 , 5);
$invitecodelist = $list['list'];
$page = getPageList($list['count'] , 5 , '/Templates/daili/invitecode.php');
?>
<!DOCTYPE html>
<html data-dpr="1" style="font-size: 48px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>邀请码管理</title>
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
    <a href="index.php" class="navbar-item">
        <img src="/Style/newimg/leftar.png" style="height: 0.5rem;">
    </a>
    <div class="navbar-center">
        <span class="navbar-title" style="">邀请码管理</span>
    </div>
</header>
<div class="m-cell" style="margin-top:0.35rem;">
    <div class="cell-item redio_panel">
        <div class="cell-left" style="font-size: 17px;padding-right: 15px;">开户类型</div>
        <label class="cell-right type">
            <input type="radio" name="type" value="dl" <?php if($type == 'dl'):?>checked<?php endif;?>/>
            <i class="cell-checkbox-icon"></i>代理类型
        </label>
        <label class="cell-right type">
            <input type="radio" name="type" value="wj" <?php if($type == 'wj'):?>checked<?php endif;?>/>
            <i class="cell-checkbox-icon"></i>玩家类型
        </label>
    </div>

    <style>
        .cell-right{-webkit-justify-content:flex-start !important;justify-content:flex-start !important;font-size: 0.30rem;}
        .cell-right .cell-checkbox-icon{padding-right: 5px;}
        .redio_panel:after{border:0px !important;}
    </style>
</div>
<div class="total-log" style="margin-top: 0;padding: 0;">
    <table class="total-table" style="font-size: 0.3rem;">
        <tbody>
        <tr>
            <th>邀请码</th>
            <th>生成时间</th>
            <th>状态</th>
        </tr>
        <?php
        if(!empty($invitecodelist)):
            ?>
            <?php foreach ($invitecodelist as $invitecode){?>
            <tr onclick="updateinivitecode('<?php echo $invitecode['id'];?>','<?php echo $invitecode['fandian_len'];?>')">
                <td style="color: rgb(51, 136, 255);"><?php echo $invitecode['invite_code'];?></td>
                <td><?php echo $invitecode['add_time'];?></td>
                <td>注册（<?php echo $invitecode['reg_count'];?>）<?php if(intval($invitecode['fandian_len']) < 7){ echo "<font color='red'>修改</font>";}?></td>
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
            <li class="fandian_edit" style="display: none">
                <a class="updatefandian" data-type="update">修改返点</a>
            </li>
            <li class="fandian_show">
                <a class="updatefandian" data-type="show">查看返点</a>
            </li>

            <li>
                <a class="delete">删除邀请码</a>
            </li>
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
<link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
<script type="text/javascript" src="/Style/pop/js/popups.js"></script>
<script type="text/javascript">
    let inivitecode_id = '';
    $(".type").on('click' , function () {
        if($(this).find('input[name="type"]:checked')){
            search();
        }
    });
    function search(){
        window.location.href="?type=" + $('input[name="type"]:checked').val();
    }
    function updateinivitecode(id,len){
        inivitecode_id = id;
        $("#popBox").show();
        if(len < 7){
            $(".fandian_edit").show()
        }else{
            $(".fandian_edit").hide()
        }
    }
    $(".cancel").click(function(){
        inivitecode_id = '';
    });
    $(".updatefandian").on('click' , function(){
        var type = $(this).attr('data-type');
        console.log(type);
        window.location.href="manageinvite.php?id=" + inivitecode_id + "&button_type="+type;
    })
    $(".delete").on('click' , function(){
        if(!confirm("确定要删除？")){
            return;
        }
        $("#popBox").hide();
        $.ajax({
            url:location.href,
            dataType:'json',
            type:'post',
            data:{do:'del',id:inivitecode_id},
            success:function(res){
                if(res.status==1){
                    jqtoast('删除成功',2500);
                    setTimeout(function () {
                        location.reload();
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