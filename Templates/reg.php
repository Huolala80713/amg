<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
    <title>注册</title>
    <link rel="Stylesheet" type="text/css" href="/Style/newcss/common.css" />
    <link rel="Stylesheet" type="text/css" href="/Style/newcss/login.css" />
    <script type="text/javascript" src="/Style/newjs/jquery-1.10.1.min.js"></script>
    <link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
    <script type="text/javascript" src="/Style/pop/js/popups.js"></script>
<script type="text/javascript" src="/Style/plus.js"></script>
</head>
<body>
<div class="mainbox max-width">
    <div class="toplogo"><img src="/Style/newimg/logo.png"></div>
    <div class="main-content">
        <div class="reg-bg"><img src="/Style/newimg/reg.png"></div>
        <div class="input-box">
            <li class="left-icon"><img src="/Style/newimg/header-ico.png"></li>
            <li class="right-input"><input id="uname" type="text" placeholder="请填写6-16位用户名"></li>
        </div>
        <div class="input-box">
            <li class="left-icon"><img src="/Style/newimg/lock-ico.png"></li>
            <li class="right-input"><input id="pass" type="password" placeholder="请填写8-16位密码"></li>
        </div>
        <div class="input-box">
            <li class="left-icon"><img src="/Style/newimg/lock-ico.png"></li>
            <li class="right-input"><input id="repass" type="password" placeholder="再次输入密码"></li>
        </div>
        <div class="input-box">
            <li class="left-icon"><img src="/Style/newimg/yaoqingma.png"></li>
            <li class="right-input"><input id="invite_code" type="text" placeholder="请输入邀请码"></li>
        </div>
        <div class="toreg" id="doreg"><img src="/Style/newimg/toreg.png"><span>会员注册</span></div>
        <div class="toreg" id="dologin"><img src="/Style/newimg/tologin.png"><span>返回登陆</span></div>
    </div>
    <div class="btn-bg"><img src="/Style/newimg/loginbt.png"></div>
</div>
<script type="text/javascript">
    $('#dologin').on('click',function () {
        window.location.href='/action.php?do=login';
    })
    $('#doreg').on('click',function () {
        var userName=$('#uname').val();
        var pass=$('#pass').val();
        var repass=$('#repass').val();
        var invite_code = $('#invite_code').val();
        var regUserNameExp = /[a-zA-Z0-9]{6,16}/;
        var regPassExp = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d){6,16}/;
        if(userName=="") return jqtoast('用户名不能为空');
        if(pass=="") return jqtoast('密码不能为空');
        if(repass=="") return jqtoast('请再次输入密码');
        if(invite_code=="") return jqtoast('请输入邀请码');
        // if(!userName.match(regUserNameExp)){
        //     return jqtoast('请输入6-16位字母和数字组合的用户名');
        // }
        if(!pass.match(regPassExp)){
            return jqtoast('请输入6-16位大写字符，小写字母和数字组合的登录密码');
        }
        if(repass!=pass) return jqtoast('两次输入密码不一致');
        $.ajax({
            url:'action.php?do=doreg',
            dataType:'json',
            method:'post',
            data:{'userName':userName,'pass':pass,'repass':repass,'invite_code':invite_code},
            success:function (res) {
                if(res.status==1){
                    jqtoast('注册成功，跳转到登录页');
                    setTimeout(function () {
                        window.location.href='/action.php?do=login';
                    },1000)
                }else{
                    jqtoast(res.msg);
                }
            }
        })
    })
</script>
</body>
</html>