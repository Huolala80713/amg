<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "function.php";
$info = getinfo($_SESSION['userid']);
$user = get_query_vals('fn_user','*',array('roomid'=>$_SESSION['roomid'],'userid'=>$_SESSION['userid']));
function getfileType($file)
{
    return substr(strrchr($file, '.'), 1);
}

if($_POST){
    $weichat_number = $_POST['weichat_number'];
    $weichat_name = $_POST['weichat_name'];
    $alipay_number = $_POST['alipay_number'];
    $alipay_name = $_POST['alipay_name'];
    $qq_number = $_POST['qq_number'];
    $bank_number = $_POST['bank_number'];
    $bank_name = $_POST['bank_name'];
    $bank_username = $_POST['bank_username'];
    $bank_fenhang = $_POST['bank_fenhang'];
    if ($_FILES['alipay_image']['size'] > 0) {
        if ((($_FILES["alipay_image"]["type"] == "image/gif") || ($_FILES["alipay_image"]["type"] == "image/jpeg") || ($_FILES["alipay_image"]["type"] == "image/png")) && ($_FILES["alipay_image"]["size"] < 2000000)) {
            if ($_FILES["alipay_image"]["error"] > 0) {
                ajaxMsg('支付宝收款码上传文件出错..错误代码:' . $_FILES["alipay_image"]["error"],0);
            } else {
                $alipay_image = '/upload/' . date('Ymd') . '/' . date('Ymd') . time() . '.' . getfileType($_FILES['alipay_image']['name']);
                $filedir = dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . $alipay_image;
                if(!file_exists(dirname($filedir))){
                    mkdir(dirname($filedir) , 0775 ,true);
                }
                move_uploaded_file($_FILES["alipay_image"]["tmp_name"], $filedir);
            }
        } else {
            ajaxMsg('支付宝收款码图片格式错误',0);
        }
    } else {
        $alipay_image = null;
    }
    if ($_FILES['wechat_image']['size'] > 0) {
        if ((($_FILES["wechat_image"]["type"] == "image/gif") || ($_FILES["wechat_image"]["type"] == "image/jpeg") || ($_FILES["wechat_image"]["type"] == "image/png")) && ($_FILES["wechat_image"]["size"] < 2000000)) {
            if ($_FILES["wechat_image"]["error"] > 0) {
                ajaxMsg('微信收款码上传文件出错..错误代码:' . $_FILES["alipay_image"]["error"],0);
            } else {
                $weichat_image = '/upload/' . date('Ymd') . '/' . date('Ymd') . (time() + 1) . '.' . getfileType($_FILES['wechat_image']['name']);
                $filedir = dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . $weichat_image;
                if(!file_exists(dirname($filedir))){
                    mkdir(dirname($filedir) , 0775 ,true);
                }
                move_uploaded_file($_FILES["wechat_image"]["tmp_name"], $filedir);
            }
        } else {
            ajaxMsg('微信收款码图片格式错误',0);
        }
    } else {
        $weichat_image = null;
    }
    update_query("fn_user", array("weichat_number" => $weichat_number,"weichat_name" => $weichat_name,"alipay_number" => $alipay_number,"alipay_name" => $alipay_name,"qq_number" => $qq_number,"bank_number" => $bank_number,"bank_name" => $bank_name,"bank_username" => $bank_username,"bank_fenhang" => $bank_fenhang), array('roomid' => $_SESSION['agent_room'],'id'=>$user['id']));
    if ($weichat_image != null) update_query('fn_user', array('weichat_image' => $weichat_image), array('roomid' => $_SESSION['agent_room'],'id'=>$user['id']));
    if ($alipay_image != null) update_query('fn_user', array('alipay_image' => $alipay_image), array('roomid' => $_SESSION['agent_room'],'id'=>$user['id']));
    ajaxMsg('提交成功');
    die();
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
    <script type="text/javascript" src="/Style/newjs/jquery.form.js"></script>
<!--    <script type="text/javascript" src="/Templates/user/js/jquery-1.7.2.js?v=1.2"></script>-->
    <script type="text/javascript" src="/Templates/user/js/global.js?v=1.2"></script>
    <script type="text/javascript" src="/Templates/user/js/common.v3.js?v=1.2"></script>
    <script type="text/javascript" src="/Templates/user/js/jweixin-1.0.0.js"></script>
    <title>收款方式</title>
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
    <form action="" id="from" method="post" enctype='multipart/form-data'>
        <div class="wx_cfb_account_center_wrap">
            <!--入口-->
            <div class="wx_cfb_ac_fund_detail">
                <div class="user_info clearfix">
                    <div class="back" onclick="window.history.back();"><img src="/Style/newimg/leftar.png"></div>
                    <div class="titlexb">收款方式</div>
                </div>
            </div>

            <div style="padding: 20px;padding-top: 0">
                <div id="offline_pay">
                    <div id="recharge_type_panel">
                        <ul id="recharge_type">
                            <li data-type="weixin" class="weixin active">
                                <img src="/Style/newimg/weixin.png" style="height: 90%;width:auto;margin: auto">
                                <span class="unchecked checked"></span>
                            </li>
                            <li data-type="alipay" class="alipay">
                                <img src="/Style/newimg/alipay.png" style="height: 90%;width:auto;margin: auto">
                                <span class="unchecked"></span>
                            </li>
                            <li data-type="union" class="union">
                                <img src="/Style/newimg/union.png" style="height: 90%;width:auto;margin: auto">
                                <span class="unchecked"></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <dl class="room_info">
                    <dt id="weixin_panel">
                        <div class="upload">
                            <div style="width: 100%;position: relative;width: 200px;height: 200px;margin: 0 auto;">
                                <img class="wechat_image" src="<?php echo $user['weichat_image']?$user['weichat_image']:'/Style/newimg/plus.png';?>" />
                                <input id="wechat_image" name="wechat_image" type="file" style="width: 200px;position: absolute;top: 0;left: 0;opacity: 0;height: 200px;">
                            </div>
                            <p style="padding-top: 10px;">点击图案添加收款二维码</p>
                            <input class="input" type="text" name="weichat_number" value="<?php echo $user['weichat_number'];?>" placeholder="微信账号"/>
                            <input class="input" type="text" name="weichat_name"  value="<?php echo $user['weichat_name'];?>" placeholder="支微信实名用户姓名" />
                        </div>
                    </dt>
                    <dt id="alipay_panel" style="display: none">
                        <div class="upload">
                            <div style="width: 100%;position: relative;width: 200px;height: 200px;margin: 0 auto;">
                                <img class="alipay_image" src="<?php echo $user['alipay_image']?$user['alipay_image']:'/Style/newimg/plus.png';?>" />
                                <input id="alipay_image" name="alipay_image" type="file" style="width: 100%;position: absolute;top: 0;left: 0;opacity: 0;">
                            </div>
                            <p style="padding-top: 10px;">点击图案添加收款二维码</p>
                            <input class="input" type="text" name="alipay_number" value="<?php echo $user['alipay_number'];?>" placeholder="支付宝账号"/>
                            <input class="input" type="text" name="alipay_name" value="<?php echo $user['alipay_name'];?>" placeholder="支付宝实名用户姓名" />
                        </div>
                    </dt>
                    <dt id="union_panel" style="display: none">
                        <input class="input" type="text" name="bank_name" value="<?php echo $user['bank_name'];?>" placeholder="银行名称"/>
                        <input class="input" type="text" name="bank_fenhang" value="<?php echo $user['bank_fenhang'];?>" placeholder="分行名称" />
                        <input class="input" type="text" name="bank_username" value="<?php echo $user['bank_username'];?>"  placeholder="收款人姓名"/>
                        <input class="input" type="text" name="bank_number" value="<?php echo $user['bank_number'];?>" placeholder="银行卡号" />
                    </dt>
                </dl>
                <div id="submit" style="background: #5dccf9;height: 50px;line-height: 50px;text-align: center;font-size: 18px;color: #fff;margin-top: 25px;border-radius: 10px;">确认提交</div>
            </div>
        </div>
    </form>
</div>
<link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
<script type="text/javascript" src="/Style/pop/js/popups.js"></script>
<style>

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
        background: #f5f5f5;
        padding: 10px 0;
    }
    #recharge_type_panel p{
        float: left;
    }
    #recharge_type{
        display: inline-block;
        height: 70px;
        margin-left: 15px;
        display: flex;
    }
    #recharge_type li{
        flex: 1;
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
        box-sizing: border-box;
        padding: 20px 15%;
        width: 100%;
        margin: 20px auto;
        margin-bottom: 0;
        border: 1px solid #efefef;
        background: #f5f5f5;
        border-radius: 10px;
    }
    .room_info .upload{
        width: 100%;
        text-align: center;
        font-size: 18px;
        margin: 0 auto;
    }
    .room_info .upload img{
        width: 200px;
        height: 200px;
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
    .room_info .input{
        display:block;
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #aaa;
        background: #fff;
        padding: 8px 10px;
        text-align: center;
        border-radius: 10px;
        margin-top: 15px;
    }
    .room_info .input::placeholder{
        color: #999;
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
        $("#recharge_type").on('click' , 'li' , function (){
            $("#recharge_type > li > span").removeClass('checked');
            $(this).find('span').addClass('checked');
            $("#recharge_type > li").removeClass('active');
            $(this).addClass('active')
            $('.room_info').find('dt').hide();
            $('#' + $(this).data('type') + '_panel').show();
        });
        $('#submit').on('click' , function (){
            var data = {
                weichat_number : $('input[name="weichat_number"]').val(),
                weichat_name : $('input[name="weichat_name"]').val(),
                alipay_number : $('input[name="alipay_number"]').val(),
                alipay_name : $('input[name="alipay_name"]').val(),
                qq_number : $('input[name="qq_number"]').val(),
                bank_number : $('input[name="bank_number"]').val(),
                bank_name : $('input[name="bank_name"]').val(),
                bank_username : $('input[name="bank_username"]').val(),
                bank_fenhang : $('input[name="bank_fenhang"]').val()
            };
            $('#from').ajaxSubmit({
                "url": "",
                "type": "POST",
                "dataType": "json",
                "data": {},
                "success": function(res) {
                    if(res.status == 1){
                        jqtoast(res.msg , 2500);
                        setTimeout(function (){
                            location.reload();
                        } , 2500);
                    }else{
                        jqtoast(res.msg);
                    }
                }
            });
        });
        $('#alipay_image,#wechat_image').on('change' , function(e){
            var dom = $(this).attr('id');
            $.each(this.files , function(index , file){
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function(evt) {
                    var img = new Image();
                    img.onload = function() {
                        $("." + dom).attr('src' , evt.target.result);
                    }
                    img.src = evt.target.result;
                }
            });
        })
    })
</script>
</body>
</html>

