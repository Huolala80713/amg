<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
    <title>登录</title>
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
        <div class="reg-bg"><img src="/Style/newimg/login.png"></div>
        <div class="input-box">
            <li class="left-icon"><img src="/Style/newimg/header-ico.png"></li>
            <li class="right-input"><input id="uname" type="text" placeholder="请输入用户名"></li>
        </div>
        <div class="input-box">
            <li class="left-icon"><img src="/Style/newimg/lock-ico.png"></li>
            <li class="right-input"><input id="pass" type="password" placeholder="请输入密码"></li>
        </div>
              
   
         
        <div class="remember" style="display:none;"><b id="reck" class="toremember check"></b><span class="toremember">记住密码</span></div>
        
        <div class="toreg" id="dologin"><img src="/Style/newimg/tologin.png"><span>登录</span></div>
    </div>
    <div class="reg-way">
        
        
        <!--li class="/" onclick="alert(&#39;微信登录暂时关闭！&#39;)"><img src="/Style/newimg/regwx.png"></li><!--微信登录-->
        
        <li id="doreg"><img src="/Style/newimg/regweb.png"></li>
        
    </div>
    
    <div class="btn-bg"><img src="/Style/newimg/loginbt.png"></div>
</div>
<script type="text/javascript">
    $('.toremember').on('click',function () {
        if($('#reck').hasClass('check')) $('#reck').removeClass('check');
        else $('#reck').addClass('check');
    });
    $('#doreg').on('click',function () {
        window.location.href='/action.php?do=reg';
    });
    $('#dologin').on('click',function () {
        var userName=$('#uname').val();
        var pass=$('#pass').val();
        if(userName=="") return jqtoast('用户名不能为空');
        if(pass.length<6) return jqtoast('密码最少6位');
        $.ajax({
            url:'action.php?do=dologin',
            dataType:'json',
            method:'post',
            data:{'userName':userName,'pass':pass},
            success:function (res) {res
                if(res.status==1){
                    jqtoast('登录成功，跳转中');
                    setTimeout(function () {
                        window.location.href='/action.php?do=roomdoor';
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