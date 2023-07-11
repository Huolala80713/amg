<?php
include dirname(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__))))) . "/Public/config.php";
require "../function.php";
$info = getinfo($_SESSION['userid']);
if($_POST){
    $money = htmlspecialchars($_POST['money']);
    if(empty($money)){
        ajaxMsg('请输入上分金额',0);
    }
    if(get_query_val('fn_upmark', 'count(id)', array('roomid' => $_SESSION['roomid'], 'userid' => $_SESSION['userid'], 'status' => '未处理', 'type' => '上分'))){
        ajaxMsg('您还有一笔上分请求等待处理',0);
    }
    $res = recharge($_SESSION['username'], $_SESSION['userid'], $money);
    if($res){
        ajaxMsg('上分请求提交成功');
    }else{
        ajaxMsg('上分请求提交失败',0);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="user-scalable=no,width=device-width" />
    <meta name="baidu-site-verification" content="W8Wrhmg6wj" />
    <meta content="telephone=no" name="format-detection">
    <meta content="1" name="jfz_login_status">
    <link rel="stylesheet" type="text/css" href="/Templates/user/css/common.css?v=1.2" />
    <link rel="stylesheet" type="text/css" href="/Templates/user/css/new_cfb.css?v=1.2" />
    <script type="text/javascript" src="/Templates/user/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Templates/user/js/jquery-1.7.2.js?v=1.2"></script>
    <script type="text/javascript" src="/Templates/user/js/global.js?v=1.2"></script>
    <script type="text/javascript" src="/Templates/user/js/common.v3.js?v=1.2"></script>
    <script type="text/javascript" src="/Templates/user/js/jweixin-1.0.0.js"></script>
    <title>上分</title>
    <script type="text/javascript" src="/Style/plus.js"></script>
    <style type="text/css">
        .user_info{display: flex;position: relative}
        .user_info img{height: 2rem;width: auto;}
        .user_info .back{position: absolute;left: 1rem;top: 1rem}
        .user_info .titlexb{flex-grow: 10;display: flex;justify-content: center;font-size: 1.6rem;color: white;font-weight: 700}

        .blockrow{display: flex;justify-content: space-between;padding: 1rem 2rem;box-sizing: border-box;background: white;border-bottom: 1px solid #DEDEDE;align-items: center}
        .blockrow li{list-style: none;flex-grow: 0;flex-shrink: 0;}
        .blockrow li.lefttitle{flex-grow: 10;font-size: 1.5rem;color: #333333}
        .headerup{margin-right: 0.5rem}
        .headerup img{width: 5rem;height: 5rem;}
        .rightarr img{height: 2rem;width: auto}
    </style>
</head>
<body style="background: #efefef;">
<div class="wx_cfb_container wx_cfb_account_center_container">
    <div class="wx_cfb_account_center_wrap">
        <!--入口-->
        <div class="wx_cfb_ac_fund_detail">
            <div class="user_info clearfix">
                <div class="back" onclick="window.history.back();"><img src="/Style/newimg/leftar.png"></div>
                <div class="titlexb">上分</div>
            </div>
        </div>
        <ul id="recharge_list">
            <li>线上上分</li>
            <li class="active">线下上分</li>
        </ul>
        <div style="padding: 20px;padding-top: 0">
            <div id="online_pay" style="display: none;padding-top: 20px;">

            </div>
            <div id="offline_pay" style="padding-top: 20px;">
                <p>上分金额</p>
                <input type="text" placeholder="请输入上分金额" class="recharge_money" name="money" />
                <div id="recharge_type_panel">
                    <p>上分类型</p>
                    <ul id="recharge_type">
                        <li data-type="weixin" class="weixin active">
                            <img src="/Style/newimg/weixin.png" style="width: 90%;margin: auto">
                            <span class="unchecked checked"></span>
                        </li>
                        <li data-type="alipay" class="alipay">
                            <img src="/Style/newimg/alipay.png" style="width: 90%;margin: auto">
                            <span class="unchecked"></span>
                        </li>
                        <li data-type="union" class="union">
                            <img src="/Style/newimg/union.png" style="width: 90%;margin: auto">
                            <span class="unchecked"></span>
                        </li>
                    </ul>
                </div>
                <dl class="room_info">
                    <dd class="tip_title">房主信息</dd>
                    <dt id="weixin_panel">
                        <img class="image" src="/Style/newimg/weixin.png" />
                        <p>房主微信：<?php echo get_query_val('fn_setting','weichat_number',array('roomid'=>$_SESSION['roomid'])); ?> <a onclick="copy('<?php echo get_query_val('fn_setting','weichat_number',array('roomid'=>$_SESSION['roomid'])); ?>')" class="btn">复制</a></p>
                        <p>收款人姓名：<?php echo get_query_val('fn_setting','weichat_name',array('roomid'=>$_SESSION['roomid'])); ?> <a onclick="copy('<?php echo get_query_val('fn_setting','weichat_name',array('roomid'=>$_SESSION['roomid'])); ?>')" class="btn">复制</a></p>
                        <p>房主QQ：<?php echo get_query_val('fn_setting','qq_number',array('roomid'=>$_SESSION['roomid'])); ?> <a onclick="copy('<?php echo get_query_val('fn_setting','qq_number',array('roomid'=>$_SESSION['roomid'])); ?>')" class="btn">复制</a></p>
                    </dt>
                    <dt id="alipay_panel" style="display: none">
                        <img class="image" src="/Style/newimg/alipay.png" />
                        <p>房主支付宝：<?php echo get_query_val('fn_setting','alipay_number',array('roomid'=>$_SESSION['roomid'])); ?> <a onclick="copy('<?php echo get_query_val('fn_setting','alipay_number',array('roomid'=>$_SESSION['roomid'])); ?>')" class="btn">复制</a></p>
                        <p>收款人姓名：<?php echo get_query_val('fn_setting','alipay_name',array('roomid'=>$_SESSION['roomid'])); ?> <a onclick="copy('<?php echo get_query_val('fn_setting','alipay_name',array('roomid'=>$_SESSION['roomid'])); ?>')" class="btn">复制</a></p>
                        <p>房主QQ：<?php echo get_query_val('fn_setting','qq_number',array('roomid'=>$_SESSION['roomid'])); ?> <a onclick="copy('<?php echo get_query_val('fn_setting','qq_number',array('roomid'=>$_SESSION['roomid'])); ?>')" class="btn">复制</a></p>
                    </dt>
                    <dt id="union_panel" style="display: none">
                        <p>银行名称：<?php echo get_query_val('fn_setting','bank_name',array('roomid'=>$_SESSION['roomid'])); ?> <a onclick="copy('<?php echo get_query_val('fn_setting','bank_name',array('roomid'=>$_SESSION['roomid'])); ?>')" class="btn">复制</a></p>
                        <p>分行名称：<?php echo get_query_val('fn_setting','bank_fenhang',array('roomid'=>$_SESSION['roomid'])); ?> <a onclick="copy('<?php echo get_query_val('fn_setting','bank_fenhang',array('roomid'=>$_SESSION['roomid'])); ?>')" class="btn">复制</a></p>
                        <p>收款人姓名：<?php echo get_query_val('fn_setting','bank_username',array('roomid'=>$_SESSION['roomid'])); ?> <a onclick="copy('<?php echo get_query_val('fn_setting','bank_username',array('roomid'=>$_SESSION['roomid'])); ?>')" class="btn">复制</a></p>
                        <p>银行卡号：<?php echo get_query_val('fn_setting','bank_number',array('roomid'=>$_SESSION['roomid'])); ?> <a onclick="copy('<?php echo get_query_val('fn_setting','bank_number',array('roomid'=>$_SESSION['roomid'])); ?>')" class="btn">复制</a></p>
                    </dt>
                </dl>
                <div id="submit" style="background: #5dccf9;height: 50px;line-height: 50px;text-align: center;font-size: 18px;color: #fff;margin-top: 25px;border-radius: 10px;">确认提交</div>
            </div>
        </div>
    </div>
</div>
<link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
<script type="text/javascript" src="/Style/pop/js/popups.js"></script>
<style>
    #recharge_list{
        display: flex;
        width: 80%;
        margin: 0 auto;
        height: 40px;
        line-height: 40px;
        font-size: 16px;
        padding: 10px;
    }
    #recharge_list li{
        width: 45%;
        background: red;
        text-align: center;
        background: #f5f5f5;
        border-radius: 8px;
    }
    #recharge_list li:last-child{
        margin-left: 10%;
    }
    #recharge_list li.active{
        background: #5dccf9;
        color: #fff;
    }
    #offline_pay{

    }
    #offline_pay p{

    }
    .recharge_money{
        border-radius: 8px;
        padding: 10px;
        background: #fff;
        margin-top: 10px;
        display: inline-block;
        box-sizing: border-box;
        width: 100%;
    }
    .recharge_money::placeholder{
        color: #afafaf;
    }
    #recharge_type_panel{
        margin-top: 20px;
        height: 70px;
        line-height: 70px;
    }
    #recharge_type_panel p{
        float: left;
    }
    #recharge_type{
        display: inline-block;
        height: 70px;
        margin-left: 15px;
    }
    #recharge_type li{
        cursor: pointer;
        width: 68px;
        height: 68px;
        border-radius: 8px;
        background-color: #eff2fb;
        display: inline-block;
        margin-right: 10px;
        position: relative;
        border: 1px solid #cfcfcf;
    }
    #recharge_type li .unchecked,#recharge_type li .checked{
        position: absolute;
        right: 0;
        bottom: 0;
        height: 30px;
        width: 30px;
        border-bottom-right-radius: 8px;
    }
    #recharge_type li .unchecked{
        background: url("/Style/newimg/unchecked.png") no-repeat right bottom;
        background-size: 90%;
    }
    #recharge_type li .checked{
        background: url("/Style/newimg/checked.png") no-repeat right bottom;
        background-size: 90%;
    }
    #recharge_type li.active{
        background: #fff !important;
    }
    .room_info{
        margin-top: 20px;
        padding-bottom: 20px;
        width: 100%;
        border: 1px solid #efefef;
        background: #fff;
        border-radius: 10px;
    }
    .room_info .tip_title{
        height: 50px;
        line-height: 50px;
        border-bottom: 1px solid #cfcfcf;
        font-size: 16px;
        padding-left: 10px;
    }
    .room_info .image{
        display: block;
        width: 120px;
        margin: 25px auto;
        height: 120px;
        border-radius: 5px;
        border: 1px solid #cfcfcf;
    }
    .room_info dt p{
        height: 45px;
        line-height: 45px;
        justify-content:center;
        width: 90%;
        margin: 0 auto;
    }
    .room_info .btn{
        display: inline-block;
        padding: 5px 15px;
        background: #5dccf9;
        border-radius: 10px;
        color: #fff;
        line-height: normal;
        margin-left: 10px;
        float: right;
    }
</style>
<link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
<script type="text/javascript" src="/Style/pop/js/popups.js"></script>
<script type="text/javascript">
    function copy(value){
        var input = document.createElement("input");
        document.body.appendChild(input);
        input.setAttribute('value' , value);
        input.select();
        if(document.execCommand('copy')){
            document.execCommand('copy');
            jqtoast('复制成功');
        }
        document.body.removeChild(input);
    }
    $(function (){
        $("#recharge_list").on('click' , 'li' , function (){
            $("#recharge_list").find('li').removeClass('active');
            $(this).addClass('active');
            if($(this).index() == 1){
                $("#offline_pay").show();
                $("#online_pay").hide();
            }else{
                $("#online_pay").show();
                $("#offline_pay").hide();
            }
        });
        $("#recharge_type").on('click' , 'li' , function (){
            $("#recharge_type > li > span").removeClass('checked');
            $("#recharge_type > li").removeClass('active');
            $(this).addClass('active')
            $(this).find('span').addClass('checked');

            $('.room_info').find('dt').hide();
            $('#' + $(this).data('type') + '_panel').show();
        });
        $('#submit').on('click' , function (){
            var money = $('.recharge_money').val().replace(/\s/g , '');
            if(money == ''){
                return jqtoast('请输入上分金额');
            }
            money = parseFloat(money);
            if(isNaN(money)){
                return jqtoast('上分金额输入错误');
            }
            $.ajax({
                url:'',
                data:{money:money},
                type:'post',
                dataType:'json',
                success:function (res){
                    if(res.status == 1){
                        jqtoast('上分请求提交成功' , 2500);
                        setTimeout(function (){
                            location.reload();
                        } , 2500);
                    }else{
                        return jqtoast(res.msg);
                    }
                },
                error:function(){
                    return jqtoast('网络错误稍后再试');
                }
            });
        });
    })
</script>
</body>
</html>

