<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
    <title>AMG入口</title>

    <link rel="Stylesheet" type="text/css" href="/Style/newcss/common.css" />
    <link rel="Stylesheet" type="text/css" href="/Style/newcss/roomdoor.css?v=<?=time()?>" />
    <script type="text/javascript" src="/Style/newjs/jquery-1.10.1.min.js"></script>
    <link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
    <script type="text/javascript" src="/Style/pop/js/popups.js"></script>
    <script type="text/javascript">
        window.onerror=function (x,y,z) {
            //alert(x+"=="+y+"=="+z);
        }
    </script>
    <style type="text/css">
        input{ime-mode:disabled;}
    </style>
<script type="text/javascript" src="/Style/plus.js"></script>

</head>
<body>
<div class="mainbox max-width">
    <div class="roomtop" style="flex-shrink: 0">
        <img src="/Style/newimg/roomtopbg.png">
        <div class="userinfo">
            <p><img style="height: 0.8rem!important;width: 0.8rem!important;" src="<?php echo $_SESSION['headimg'];?>"></p>
            <p>
                <span style="font-size: 16px;"><?php echo get_query_val('fn_user','uid',"userid = '{$_SESSION['userid']}'");?></span><br/>
                <span style="font-size: 16px;"><?php echo $_SESSION['username'];?></span>
            </p>
        </div>
        <div class="top-btn">
            <li onclick="window.location.href='/Templates/user/setting.php'">
                <img class="bgimg" src="/Style/newimg/setting-bg.png">
                <span style="font-size: 0.25rem;">
                    <img src="/Style/newimg/setting.png" style="margin-right: 0.05rem">个人设置
                </span>
            </li>
            <li id="logout">
                <img class="bgimg" src="/Style/newimg/exit-bg.png">
                <span style="font-size: 0.25rem;">
                    <img src="/Style/newimg/exit.png" style="margin-right: 0.05rem">安全退出
                </span>
            </li>
        </div>
    </div>
    <ul id="roomnum" class="roomnum" style="width: 70%;margin: 0 auto;">
        <li data-type="1" style="width: 45%;background: url('/Style/newimg/roomdoor1.png') no-repeat center;background-size: cover;height: 35px;line-height: 35px;text-align: center;font-size: 16px;">
            房间号码
        </li>
<!--        <li data-type="2" style="width: 45%;margin-left:10%;background: url('/Style/newimg/roomdoor2.png') no-repeat center;background-size: cover;height: 35px;line-height: 35px;text-align: center;font-size: 16px;">-->
<!--            邀请码-->
<!--        </li>-->
    </ul>
    <script type="text/javascript">
        var find_type = 1;
        $(function (){
            $("#roomnum").on('click' , 'li' , function (){
                $("#roomnum").find('li').css({
                    background: "url('/Style/newimg/roomdoor2.png') no-repeat center"
                });
                $(this).css({
                    background: "url('/Style/newimg/roomdoor1.png') no-repeat center"
                });
                $('.room_number').find('input').val('');
                $('.invite_code').find('input').val('');
                find_type = $(this).data('type');
                if($(this).data('type') == 1){
                    $('.room_number').show();
                    $('.invite_code').hide();
                }else{
                    $('.room_number').hide();
                    $('.invite_code').show();
                }
            })
        })
    </script>
    <div class="room-num-input room_number" id="numinput" style="flex-shrink: 0;padding: 0.3rem 0.2rem;">
        <input type="number" maxlength="1" value="">
        <input type="number" maxlength="1" value="">
        <input type="number" maxlength="1" value="">
        <input type="number" maxlength="1" value="">
        <input type="number" maxlength="1" value="">
        <input type="number" maxlength="1" value="">
    </div>
    <div class="room-num-input invite_code" style="flex-shrink: 0;padding: 0.3rem 0.2rem;display: none;">
        <input id="invite_code" name="invite_code" type="text" style="width: 100%;" value="" placeholder="请输入邀请码">
    </div>
    <div class="room-in" id="loginroom" style="flex-shrink: 0"><img src="/Style/newimg/tologin.png"><span>进入房间</span></div>
    <div class="room-history" style="flex-shrink: 10;padding-top: 0.2rem;">
        <div class="history-title" style="color: #000;font-weight: bold;">历史房间:</div>

        <div class="listroom">
            <?php
            select_query("fn_user", '*', "`userid` = '{$_SESSION['userid']}' and roomid<>0 order by `id` desc limit 3");
            while($con = db_fetch_array()) {
                $roomInfo=get_query_vals('fn_room','*',['roomid'=>$con['roomid']]);
                $roomSetting=get_query_vals('fn_setting','*',['roomid'=>$con['roomid']]);
                ?>
                <li class="roomrow" data-roomid="<?php echo $con['roomid'];?>"><p><img src="<?php echo $roomSetting['setting_robotsimg'];?>"></p><p><?php echo $roomInfo['roomname'];?></p></li>
                <?php
            }
            ?>

        </div>
    </div>
    <!--<div class="btn-bg"><img src="/Style/newimg/loginbt-dark.png"></div>-->
</div>
<!--<div class="footer_menu">
    <li data-url="/action.php?do=kefu"><img src="/Style/newimg/m_icon_4.png"></li>
    <li data-url="/Templates/user/"><img src="/Style/newimg/m_icon_2.png"></li>
    <li data-url=""><img src="/Style/newimg/m_icon_3.png"></li>
    <li data-url="/Templates/user/setting.php"><img src="/Style/newimg/m_icon_1.png"></li>
</div>-->
<script type="text/javascript">
    $('.footer_menu li').on('click',function () {
        var url=$(this).data('url');
        window.location.href=url;
    });
    $('.roomrow[data-roomid]').on('click',function () {
        var roomid=$(this).data('roomid');
        $.ajax({
            url:'action.php?do=findroom',
            dataType:'json',
            type:'post',
            data:{'room':roomid,invite_code:'invite_code'},
            success:function (res) {
                if(res.status==1){
                    jqtoast('进入房间，跳转中');
                    setTimeout(function () {
                        window.location.href='/action.php?do=gamelist';
                    },1000);
                }else{
                    jqtoast(res.msg);
                }
            }
        })
    })
</script>
<script type="text/javascript">
var keyAll=['','','','','',''];
var fromInput=0;
$('#numinput input').on('keyup',function (e) {
    var index=$('#numinput input').index(this);
    var thisValue=$(this).val();
    if(e.keyCode==8){
        //if($(this).val()=="") $(this).prev('input').focus();
    }else{
        //$("<b>|"+e.keyCode+"</b>").appendTo('#testss');
        //console.log(e.key,index);
        /*var ev=e||event;
        var asciicode=ev.keyCode;
        alert(asciicode);
        keyAll[index]=ev.key;
        $(this).val('');*/
        if(thisValue!="") $(this).val(thisValue.substr(-1));
        $(this).next('input').focus();
        //showText();
    }
}).on('keydown',function (e) {
    var thisValue=$(this).val();
    if(e.keyCode==8 && thisValue=="") $(this).prev('input').focus();
    //$("<b>*"+e.keyCode+"</b>").appendTo('#testss');
    //e.preventDefault();
});
var xtime;
function showText(){
    clearTimeout(xtime);
    xtime=setTimeout(function () {
        for (i=0;i<6;i++){
            if(typeof keyAll[i]=="undefined") break;
            $('#numinput input').eq(i).val(keyAll[i]);
            //console.log('add',keyAll[i]);
        }
    },50);

}
<?php 
if(get_query_val('fn_user','is_change_pw',"userid = '{$_SESSION['userid']}'") == 0){
?>
gotopwd();
<?php 
}
?>
//跳转
function gotopwd(){
	jqtoast('请修改初始密码！即将跳转');
	setTimeout(function () {
		window.location.href = "/Templates/user/set-pass.php"
    },2000);

}

$('#loginroom').on('click',function () {
    var totalStr='';
    var data = {
        find_type:find_type
    };
    if(find_type == 1){
        $('#numinput input').each(function () {
            totalStr+=""+$(this).val();
        });
        if(totalStr == ''){
            return jqtoast('房间号不能为空');
        }
        data['room'] = totalStr;
    }
    if(find_type == 2){
        var invite_code = $('#invite_code').val();
        if(invite_code == ''){
            return jqtoast('邀请码不能为空');
        }
        data['room'] = invite_code;
    }

    $.ajax({
        url:'action.php?do=findroom',
        dataType:'json',
        type:'post',
        data:data,
        success:function (res) {
            if(res.status==1){
                jqtoast('进入房间，跳转中');
                setTimeout(function () {
                    window.location.href='/action.php?do=gamelist';
                },1000)
            }else{
                jqtoast(res.msg);
            }
        }
    })
})
    $('#logout').on('click',function () {
        jqalert({
            content:'确认要退出吗？',
            yestext:'退出',
            notext:'取消',
            yesfn:function () {
                window.location.href='/action.php?do=logout';
            }
        })

    })
</script>

</body>
</html>