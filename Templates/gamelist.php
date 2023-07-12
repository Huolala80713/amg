<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
    <title>欢迎来到【<?php echo get_query_val("fn_room", "roomname", array("roomid" => $_SESSION['roomid']));
        ?>】房间</title>

    <link rel="Stylesheet" type="text/css" href="/Style/newcss/common.css" />
    <link rel="Stylesheet" type="text/css" href="/Style/newcss/gamelist.css?t=8" />
    <style>
        .footer_menu2{display: flex;position: fixed;bottom: 0px;width: 100%;left: 0px;justify-content: space-between;align-items: center;padding: 0.1rem 0.2rem;box-sizing: border-box}
        .footer_menu2 li{display: flex;}
        .footer_menu2 li img{width: auto;height: 1rem}
        .aaa{
            width: 50px;
            float: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    <script type="text/javascript" src="/Style/newjs/jquery-1.10.1.min.js"></script>
    <script type="text/javascript">var gimdIDS=[];</script>
    <script type="text/javascript" src="/Style/plus.js"></script>
</head>
<body>
<div style="display: flex;background: url(/Style/newimg/bgdark.png) no-repeat left top;background-size: cover;height: 100%;width: 100%;position: fixed;z-index: -10;"></div>
<div class="mainbox max-width" style="position: initial;display: block;overflow: hidden;background: none;">
    <div style="position: fixed;top: 0;left: 0;">
        <div class="roomtop">
            <img src="/Style/newimg/logo.png" style="width: 50%;margin: 0 auto;display: block;">
            <div class="top-btn">
                <img id="goback" src="/Style/newimg/gameback.png">
            </div>
        </div>

        <div class="notice">
            <div class="leftlaba"><img src="/Style/newimg/laba.png"></div>
            <div class="rightcon" style="font-size: 15px;"><marquee><?php echo strip_tags(get_query_val('fn_setting','setting_kefu',array('roomid'=>$_SESSION['roomid']))); ?></marquee></div>
        </div>
    </div>

    <div class="gamelist" style="position: fixed;width: 100%;left: 0;top: 0;">
        <div class="last-show" style="display: none;height: 100%;">
            <?php foreach($list as $gm):
                if($gm['is_open']=='false') continue;
                ?>
                <div class="game-box" style="margin: 0.1rem 0.2rem 0 0.2rem;" id="game-id-<?php echo $gm['game_id'];?>" data-gid="<?php echo $gm['game_id'];?>" data-left="<?php echo $gm['opinfo']['letf_time'];?>">
                    <img src="/Style/newimg/gamebg.png">
                    <div class="game-info">
                        <li class="game-ico"><img src="/Style/newimg/game_<?php echo $gm['game_id'];?>.png"></li>
                        <li class="game-name"><img src="/Style/newimg/game_name_<?php echo $gm['game_id'];?>.png"></li>
                        <li class="game-sn"><b>第<span id="next_num_<?php echo $gm['game_id'];?>"><b>——</b></span>期</b></li>
                        <li class="game-time"><b>-</b><b>-</b><b>:</b><b>-</b><b>-</b></li>
                        <li class="game-num" id="current_num_<?php echo $gm['game_id'];?>">
                            <span class="sn-num aaa"></span>
                            <span class="n1">1</span>
                            <span class="n2">2</span>
                            <span class="n3">3</span>
                            <span class="n4">4</span>
                            <span class="n5">5</span>
                            <span class="n6">6</span>
                            <span class="n7">7</span>
                            <span class="n8">8</span>
                            <span class="n9">9</span>
                            <span class="n10">10</span>
                        </li>
                    </div>
                </div>
                <script type="text/javascript">gimdIDS.push(<?php echo $gm['game_id'];?>)</script>
            <?php endforeach;?>
        </div>
    </div>

    <div class="btn-bg" style="width:50px; height:50px"><img src="/Style/newimg/loginbt-dark.png"></div>
</div>
<div class="footer_menu2" style="bottom:10px;">
    <!--<li data-url="/action.php?do=kefu"><img src="/Style/newimg/m_icon_4.png"></li>-->
    <!--<li data-url="/Templates/user/"><img src="/Style/newimg/m_icon_2.png"></li>-->
    <!--<li data-url="#"><img src="/Style/newimg/m_icon_3.png"></li>-->
    <!--<li data-url="/Templates/user/setting.php"><img src="/Style/newimg/m_icon_1.png"></li>-->


    <li data-url="/Templates/user/codepay/index.php"><img src="/Style/newimg/m_icon_4.png"></li>
    <li data-url="/action.php?do=kefu"><img src="/Style/newimg/m_icon_3.png"></li>
    <li data-url="/Templates/user/"><img src="/Style/newimg/m_icon_5.png?v=1"></li>
    <li data-url="/action.php?do=guize"><img src="/Style/newimg/m_icon_2.png"></li>
    <li data-url="/Templates/user/setting.php"><img src="/Style/newimg/m_icon_1.png"></li>
</div>
<link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
<script type="text/javascript" src="/Style/pop/js/popups.js"></script>
<script type="text/javascript">
    window.addEventListener('resize',function (){
        manage();
    });
    function manage(){
        var centerHeight=$('.gamelist').outerHeight();
        var window_height = $(window).height();
        console.log($(".game-box").outerHeight());
        var max_height = window_height - $('.footer_menu2').outerHeight(true) - $('.notice').outerHeight(true) - $('.roomtop').outerHeight(true) - 10;
        $('.gamelist').css({
            'max-height':(max_height) + 'px','margin-top' : $('.roomtop').outerHeight(true) + $('.notice').outerHeight(true)
        });
        $('.gamelist .last-show').show();
        var box_height = $(".game-box").outerHeight(true);
        console.log(box_height);
        var count = parseInt(max_height / box_height);
        console.log(count);
        $('.gamelist').css('max-height',(count * box_height) + 'px');
    }
    setTimeout(function () {
        manage();
    },500);
    $('.footer_menu2 li').on('click',function () {
        var url=$(this).data('url');
        if(url == 1){
            jqalert({
                content:'在线充值暂未开放，请联系在线客服上分',
                yestext:'确定'
            })
        }else{
            window.location.href=url;
        }
    });
    $('#goback').on('click',function () {
        //window.history.back();
        window.location.href='/action.php?do=roomdoor';
    });
    $('.game-box').on('click',function () {
        var gmid=$(this).data('gid');
        if(gmid == 9){
            window.location.href='/lhc.html';
        }else{
            window.location.href='/action.php?do=room&game='+gmid;
        }
    });
    gimdIDS.forEach(function (item , index){
        showTimer(item);
    })
    function showTimer(id){
        $.ajax({
            url:"/action.php?do=upop",
            method:'get',
            dataType:'json',
            data:{'gid':[id]},
            success:function (res) {

                for (i=0;i<res.list.length;i++){
                    var row=res.list[i];
                    if(!row.current_sn) continue;
                    var timeStr=buildTime(row.letf_time,row.fp_time);
                    $('#game-id-'+row.gameid).find('.game-time').html(timeStr);
                    if(row.gameid == 3 || row.gameid == 4 || row.gameid == 6){
                        row.current_sn = row.current_sn.slice(3);
                        // row.next_sn = row.next_sn.slice(3);
                    }
                    var numArr=row.open_num.split(',');
                    var numHtml='<span class="sn-num">'+row.current_sn+'</span>';
                    for (j=0;j<numArr.length;j++){
                        if(row.gameid == 9){
                            if(j == 6){
                                numHtml+='<span>+</span>';
                            }
                            numHtml+='<span class="number-box n'+numArr[j]+'">'+numArr[j]+'</span>';
                        }else{
                            numHtml+='<span class="n'+numArr[j]+'">'+numArr[j]+'</span>';
                        }

                    }

                    $('#current_num_'+row.gameid).html(numHtml);
                    $('#next_num_'+row.gameid).html(row.next_sn);
                }
                setTimeout(function () {
                    showTimer(id);
                },1000)
            }
        })
    }
    //showTimer();

    function buildTime(leftSec,fpSec) {
        if(leftSec == '正在开奖'){
            return '<b class="fping">开奖中</b>';
        }
        var totalSec=parseInt(leftSec);
        if(totalSec<parseInt(fpSec)) return '<b class="fping">已封盘</b>';
        var day  =   Math.floor(totalSec/(60 * 60 * 24));
        var hours  =   Math.floor((totalSec%(60 * 60 * 24))/(60 * 60));
        var min  =   Math.floor((totalSec%(60*60)/60));
        var sec = totalSec%60;
        min=(min<10)?'0'+min:min+"";
        sec=(sec<10)?'0'+sec:sec+"";
        var lastStr='';
        if(day > 0){
            lastStr+='<b>'+day+'</b>';
//            lastStr+='<b>:</b>';
        }
        if(hours > 0){
            lastStr+='<b>'+hours+'</b>';
//            lastStr+='<b>:</b>';
        }
        if(min > 0){
            lastStr+='<b>'+min+'</b>';
            lastStr+='<b>:</b>';
        }
//        lastStr+='<b>'+min[1]+'</b>';
//        lastStr+='<b>:</b>';
        lastStr+='<b>'+sec+'</b>';
//        lastStr+='<b>'+sec[0]+'</b>';
//        lastStr+='<b>'+sec[1]+'</b>';
        return lastStr;
    }
</script>
<!--<div style="overflow: hidden;">-->
<!--    <audio src="/bgMusic.mp3" id="Jaudio" class="media-audio" autoplay preload loop="loop"></audio>-->
<!--</div>-->
<!--<script type="text/javascript">-->
<!--    function audioAutoPlay(id){-->
<!--        var audio = document.getElementById(id);-->
<!--        audio.play();-->
<!--        document.addEventListener("WeixinJSBridgeReady", function () {-->
<!--            audio.play();-->
<!--        }, false);-->
<!--        document.addEventListener('YixinJSBridgeReady', function() {-->
<!--            audio.play();-->
<!--        }, false);-->
<!--    }-->
<!--    audioAutoPlay('Jaudio');-->
<!--</script>-->
</body>
</html>