<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "function.php";
$info = getinfo($_SESSION['userid']);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="user-scalable=no,width=device-width" />
    <meta name="baidu-site-verification" content="W8Wrhmg6wj" />
    <meta content="telephone=no" name="format-detection">
    <meta content="1" name="jfz_login_status">
    <link rel="stylesheet" type="text/css" href="css/common.css?v=1.2" />
    <link rel="stylesheet" type="text/css" href="css/new_cfb.css?v=1.2" />
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-1.7.2.js?v=1.2"></script>
    <script type="text/javascript" src="js/global.js?v=1.2"></script>
    <script type="text/javascript" src="js/common.v3.js?v=1.2"></script>
    <script type="text/javascript" src="js/jweixin-1.0.0.js"></script>
    <link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
    <script type="text/javascript" src="/Style/pop/js/popups.js"></script>
    <title>个人中心</title>
    <style type="text/css">
        .user_info{display: flex;position: relative}
        .user_info img{height: 2rem;width: auto;}
        .user_info .back{position: absolute;left: 1rem;top: 1rem}
        .user_info .titlexb{flex-grow: 10;display: flex;justify-content: center;font-size: 1.6rem;color: white;font-weight: 700}

        .blockrow{display: flex;justify-content: space-between;padding: 1rem 2rem;box-sizing: border-box;background: white;border-bottom: 1px solid #DEDEDE;align-items: center}
        .blockrow li{list-style: none;flex-grow: 0;flex-shrink: 0;}
        .blockrow li.lefttitle{flex-grow: 0;font-size: 1.5rem;color: #333333;flex-shrink: 0;width: 10rem}
        .blockrow li.inputbox{flex-grow: 10;}
        .blockrow li.inputbox input{width: 100%;padding: 1rem 2rem;background: none;outline: none;border: 0 none}
        .blockrow.subbt{justify-content: center;align-items: center}
        .blockrow.subbt li{width: 40%;background: #52A9F6;border-radius: 5px;color: white;text-align: center;padding: 0.5rem 1rem;font-size: 1.5rem}
    </style>
<script type="text/javascript" src="/Style/plus.js"></script>
</head>
<body>

    <div class="wx_cfb_container wx_cfb_account_center_container">
        <div class="wx_cfb_account_center_wrap">
            <div class="wx_cfb_ac_fund_detail">
                <div class="user_info clearfix">
                    <div class="back" onclick="window.history.back();"><img src="/Style/newimg/leftar.png"></div>
                    <div class="titlexb">修改密码</div>
                </div>
            </div>
                <!--入口-->
            <div class="wx_cfb_entry_list">
                <div class="blockrow">
                    <li class="lefttitle">输入新密码</li>
                    <li class="inputbox"><input placeholder="请输入密码" type="password" id="pass"></li>
                </div>
                <div class="blockrow">
                    <li class="lefttitle">再次输入密码</li>
                    <li class="inputbox"><input placeholder="请输入密码" type="password" id="repass"></li>
                </div>
                <div class="blockrow subbt">
                    <li class="sub" onclick="subpass()">提交</li>
                </div>
            </div>
        </div>
    </div>
    <input type="file" style="visibility: hidden;height: 1px;width: 1px;" name="userheader" value="" id="userheader">
    <div class="wx_cfb_fixed_btn_box" style="display: none">
        <div class="wx_cfb_fixed_btn_wrap">
            <div class="btn_box clearfix">
                <a href="/action.php?do=room&rid=<?php echo $_SESSION['roomid'];
?>" class="btn tel_btn clearfix">
                    <em class="ico ui_ico_size_40 ui_tel_ico"></em><span class="txt">返回游戏</span>
                </a>
            </div>
        </div>
    </div>

<script type="text/javascript">
    function subpass() {
        var newpass=$('#pass').val();
        var repass=$('#repass').val();
        if(newpass==""||repass=="") return jqtoast('请输入密码!');
        if(newpass!=repass) return jqtoast('两次输入密码不一致!');
        $.ajax({
            url:"/action.php?do=uppass",
            dataType:'json',
            type:'post',
            data:{"newpass":newpass},
            success:function (res) {
                if(res.status=="1"){
                    jqtoast('更新成功' , 2500);
                    setTimeout(function (){
                        //window.location.reload();
						window.location.href = '/action.php?do=login';
						
                    } , 2500);
                }else{
                    jqtoast(res.msg);
                }
            }
        })
    }
</script>
</body>
</html>