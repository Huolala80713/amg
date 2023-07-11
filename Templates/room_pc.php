<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>【<?php echo get_query_val("fn_room", "roomname", array("roomid" => $_SESSION['roomid']));
?>】房间</title>
    <link rel="Stylesheet" type="text/css" href="/Style/newcss/common.css" />
    <link rel="Stylesheet" type="text/css" href="/Style/newcss/chatroom.css" />
    <script type="text/javascript" src="/Style/newjs/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="/Style/newjs/mobile/layer.js" />
    <link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
    <script type="text/javascript" src="/Style/pop/js/popups.js"></script>
    <style type="text/css">
        .chat-input .keyb-input:empty:before{
            content: '大小单双/金额';
            color: gray;
        }
        .jump_img
		 {position:absolute;
			 z-index:99999;
			 width:50%;
			 height:20%;
			 top:92%;
			 left:50%;
			 margin:-50% 0 0 -12%;
		 }
		 .btn1{
		    width: 50px;
            height: 31px;
            color: #eee;
            background: #54aff6;
            border-radius: 10%;
            border: none;
            font-size: 11pt;
		 }
        #div {
            display: none;
            z-index: 10000;
            width: 130px;
            height: 300px;
            position: fixed;
            margin: 38% 67%;
            background: white;
            border-radius: 5%;
        }
        #div ul>li{
            font-size: 0.3rem;
            padding: 0 0 0 0.1rem;
            margin-top: 10px;
        }
        .imgst{
            width: 30px;
            height: 30px;
            margin: 0px 4px -5px 0px;
        }
        
    </style>


    <style type="text/css">
        .keyb-input textarea{background: none;border: 0px none;width: 100%;height: 100%;padding: 0rem 0.1rem;box-sizing: border-box;height: 0.6rem;outline: none;resize: none;line-height: 0.5rem}
    </style>
<script type="text/javascript" src="/Style/plus.js"></script>
 <script type="text/javascript">
     // 禁用双指放大
     document.documentElement.addEventListener('touchstart', function (event) {
         if (event.touches.length > 1) {
             event.preventDefault();
         }
     }, {
         passive: false
     });

     // 禁用双击放大
     var lastTouchEnd = 0;
     document.documentElement.addEventListener('touchend', function (event) {
         var now = Date.now();
         if (now - lastTouchEnd <= 300) {
             event.preventDefault();
         }
         lastTouchEnd = now;
     }, {
         passive: false
     });
    // function show (event) {
    //     //取消冒泡
    //     let oevent = event || window.event
    //     if (document.all) {
    //         oevent.cancelBubble = true
    //     } else {
    //         oevent.stopPropagation()
    //     }
    //     if (document.getElementById('div').style.display === 'none' || document.getElementById('div').style.display === '') {
    //         document.getElementById('div').style.display = 'block'
    //     } else {
    //         document.getElementById('div').style.display = 'none'
    //     }
    // }
    // document.onclick = function () {
    //     document.getElementById('div').style.display = 'none'
    // }
    // $("#div").on('click' , function (event){
    //     let oevent = event || window.event
    //     oevent.stopPropagation()
    // });
    
 </script>
</head>
<body>
    
    <div id="div">
       <ul>
           <li style="display: none;"><a href="#" style="color: #228fff;"><img src="/qhuan.png"></a></li>
           <li><img src="/Style/newimg/game_4.png" class="imgst"><a href="/action.php?do=room&game=4" style="color: #228fff;"><img src="/log/game_name_4 (1).png"></a></li></a></li>
           <li><img src="/Style/newimg/game_6.png" class="imgst"><a href="/action.php?do=room&game=6" style="color: #228fff;"><img src="/log/game_name_6 (1).png"></a></li></a></li>
           <li><img src="/Style/newimg/game_3.png" class="imgst"><a href="/action.php?do=room&game=3" style="color: #228fff;"><img src="/log/game_name_3 (1).png"></a></li></a></li>
           <li><img src="/Style/newimg/game_5.png" class="imgst"><a href="/action.php?do=room&game=5" style="color: #228fff;"><img src="/log/game_name_5 (1).png"></a></li></a></li>
           <li><img src="/Style/newimg/game_2.png" class="imgst"><a href="/action.php?do=room&game=2" style="color: #228fff;"><img src="/log/game_name_2 (1).png"></a></li>
           <li><img src="/Style/newimg/game_1.png" class="imgst"><a href="/action.php?do=room&game=1" style="color: #228fff;"><img src="/log/game_name_1 (1).png"></a></li>
       </ul>     
    </div>
    
<!--    <div style="position: fixed;-->
<!--right: 46px;-->
<!--bottom:295px;-->
<!--color: gold;-->
<!--border: 0px solid gold;-->
<!--border-radius: 100px;-->
<!--padding: 5px;-->
<!--padding-left: 10px;-->
<!--text-align: left;-->
<!--width: 0px;-->
<!--font-size: 13px;" onclick="show()"><a style="color: gold;text-decoration:none;font-size: 13px;" href="#"><img src="/GameMenu3.1.png" style="left:0px; position: relative; top:90px" /></div></a> -->

            
         
           
<div style="position: fixed;
right: 50px;
bottom: 270px;
color: gold;
border: 0px solid gold;
border-radius: 100px;
padding: 1px;
padding-left: 10px;
text-align: left;
width: 0px;
font-size: 13px;"><a style="color: gold;text-decoration:none;font-size: 13px;" href="/action.php?do=kefu"> <img src="/SwitchTable.png" style="left:0px; position: relative; top:0px" /></div></a> <!--客服-->

<!--<div style="position: fixed;-->
<!--right: 50px;-->
<!--bottom: 330px;-->
<!--color: gold;-->
<!--border: 0px solid gold;-->
<!--border-radius: 100px;-->
<!--padding: 1px;-->
<!--padding-left: 10px;-->
<!--text-align: left;-->
<!--width: 0px;-->
<!--font-size: 13px;"><a style="color: gold;text-decoration:none;font-size: 13px;" href="/action.php?do=room&game=6"> <img src="/SwitchTable2.png" style="left:0px; position: relative; top:0px" /></div></a>
-->


<div style="position: fixed;
right: 46px;
bottom:480px;
color: gold;
border: 0px solid gold;
border-radius: 100px;
padding: 5px;
padding-left: 10px;
text-align: left;
width: 0px;
font-size: 13px;" ><a style="color: gold;text-decoration:none;font-size: 13px;" href="/action.php?do=gamelist"><img src="/SwitchTable3.png" style="left:0px; position: relative; top:0px" /></div></a> <!--切换/action.php?do=room&game=1-->



            
<div class="mainbox max-width">
    <div style="position: fixed;top: 0px;left: 0px;width: 100%;z-index: 100;height: 2.1rem;background-color: white;">
                <div class="header-hegith roomtop" style="height: 0.7rem"> 
            <div class="back"><img src="/Style/newimg/leftar.png"> <?php echo getGameTxtNameByCode($_COOKIE['game']);?></div>
            <div class="show-info">
                <li>　　积分:<span id="user_money">0.00</span></li>
                <li>　输赢:<span id="user_earn">0.00</span></li>
                <li>　　回水:<span id="user_hs">0.00</span></li>
                <li>　流水:<span id="user_ls">0.00</span></li>
            </div>
        </div>
        
  
        
        <div class="header-hegith newest-sn">
            <li class="sn-num" id="next_sn">06032</li>
            <li class="left-time" id="left_time"><b>0</b><b>8</b><b>:</b><b>0</b><b>0</b></li>
            
            <!--<li class="tvimg" onclick="window.open('https://kj.1680210.com/view/video/PK10/video.html?10012?1682018.co')"><img src="/GameMenu3.png"></li>-->
            

            <!--li class="bgzimg" onclick="popShow('pop-zhudan')">
                <!--<img src="/Style/newimg/zd.png">-->
                <!--input type="button" value="注单" class="btn1"/>
                </li>
            <!--<li class="bgzimg" onclick="window.open('Templates/zoushi_pc.php','','');">-->
                <!--li class="bgzimg" onclick="popShow('zoushi-pc')">
                <!--<img src="/Style/newimg/cl.png">-->
                <!--input type="button" value="走势" class="btn1"/>
            </li>
            <li class="bgzimg" onclick="popShow('forecast-pc')">
                <input type="button" value="预测" class="btn1"/>
                <!--<img src="/Style/newimg/yc.png">--> 
                </li>
            <li class="bgzimg" onclick="popShow('pop-zhudan')"><img src="/Style/newimg/zd.png"></li>
            <li class="bgzimg" onclick="popShow('zoushi-pc')"><img src="/Style/newimg/cl.png"></li>
            <li class="bgzimg" onclick="popShow('forecast-pc')"><img src="/Style/newimg/yc.png"></li>
        </div>
        <div class="header-hegith current-sn">
            <li class="sn-num" id="current_sn">06031</li>
            <li class="num-sn" id="current_num">
                <span class="n1">1</span>
                <span class="n2">2</span>
                <span class="n3">3</span>
            </li>
            <li class="gyd" onclick="popShow('pop-history-pc')"><p>大小单双</p><p id="gyhs">9 小 单</p></li>
            <li class="downimg" onclick="popShow('pop-history-pc')"><img src="/Style/newimg/arrdown.png"></li>
        </div>
        <div id="iframeBox">

        </div>
    </div>


    <div class="chat-content" style="padding-top: 2.5rem;padding-bottom: 1.5rem;width: 100%;">
        <div class="last-show" id="chat_list">

        </div>
        <div id="addSpan" style="margin-top: 5rem"></div>
        <div id="chatBt" style="height: 1px"></div>
    </div>

    <div style="z-index: 10000;position: fixed;bottom: 0px;left: 0px;width: 100%;">
        <div class="bottom-height chat-input">
            <li class="keyb-left" id="switchkb"><img src="/Style/newimg/switch.png"></li>
            <li class="keyb-input"><textarea id="msg" readonly></textarea></li>
            <li class="keyb-right" type="add" id="sendbtn"><img src="/Style/newimg/add.png"></li>
        </div>
        <div style="display: none" id="keyboard2" class="keybaord">
            <div class="fast-keyboard-url">
                <li data-txt="充值指引"><p><img src="/Style/newimg/sxf.png"></p><p>充值指引</p></li>
                <li data-url="/Templates/user/paylog.php"><p><img src="/Style/newimg/sqjl.png"></p><p>申请记录</p></li>
                <li data-url="/Templates/user/orderinfo.php"><p><img src="/Style/newimg/yxjl.png"></p><p>游戏记录</p></li>
                <li data-url="/Templates/user/orderinfo.php"><p><img src="/Style/newimg/jcbb.png"></p><p>竞猜报表</p></li>
            </div>
        </div>
        
        <div style="display: none" id="keyboard1" class="bottom-height keybaord">

            <div class="fast-keyboard">
                <li>上分</li>
                <li>取消</li>
                <li>梭哈</li>
                <li>重复</li>
                <li>下分</li>
            </div>
            <div class="numbaord">
                <li>大</li>
                <li class="rowstart">小</li>
                <li>1</li>
                <li>2</li>
                <li>3</li>
                <li>单</li>
                <li class="rowstart">双</li>
                <li>4</li>
                <li>5</li>
                <li>6</li>
                
                <li>小单</li>
                <li class="rowstart">小双</li>
                <li>7</li>
                <li>8</li>
                <li>9</li>
                <li>大单</li>
                <li class="rowstart">大双</li>
                <li>/</li>
                <!--<li ktype="sp">空格</li>-->
                <li>0</li>
                <li tabindex="0" onkeydown="alert('test')" class="delimg" ktype="del"><img src="/Style/newimg/del.png"></li>
                <li>极小</li>
                <li class="rowstart">极大</li>
                <li class="zlong">对子</li>
                <li class="zhu">顺子</li>
                <li class="zgyh">豹子</li>
            </div>
        </div>
    </div>
</div>

<!--<div style="overflow: hidden;">-->
<!--            <audio src="/bgMusic.mp3" id="Jaudio" class="media-audio" autoplay preload loop="loop"></audio>-->
<!--        </div>-->
<!--        <script type="text/javascript">-->
<!--            function audioAutoPlay(id){-->
<!--                var audio = document.getElementById(id);-->
<!--                audio.play();-->
<!--                document.addEventListener("WeixinJSBridgeReady", function () {-->
<!--                    audio.play();-->
<!--                }, false);-->
<!--                document.addEventListener('YixinJSBridgeReady', function() {-->
<!--                    audio.play();-->
<!--                }, false);-->
<!--            }-->
<!--        audioAutoPlay('Jaudio');-->
<!--        </script>-->
        
<script type="text/javascript">
    var lastSendInfo='';
    var lastChartID=0;
    var showKeyBord=false;
    var loading = layer.open({type: 2,shadeClose:false});
    getNewChartInfo();
    function getNewChartInfo(){
        setTimeout(function () {
            getChatInfo();
        },1000)
    }
    function getChatInfo() {
        var sendData={};
        sendData['type']=(lastChartID>0)?'update':'first';
        sendData['id']=lastChartID;
        $.ajax({
            url:'/Application/ajax_chat.php',
            method:'get',
            dataType:'json',
            data:sendData,
            success:function (res) {
                var chatList=res.chat_list;
                var openInfo=res.open_info;
                $('#user_money').html(res.user_money);
                $('#user_earn').html(res.user_earn);
                $('#user_ls').html(res.user_ls);
                if(chatList.length>0){
                    var chatHtml='';
                    for (i=0;i<chatList.length;i++){
                        var row=chatList[i];
                        if(parseInt(row['id'])>lastChartID) lastChartID=parseInt(row['id']);
                        //var headerType=row.type.substr(0,1)!="U"?'left-header':'right-header';
                        var headerType=res.username!=row['nickname']?'left-header':'right-header';
                        var leftHeader=headerType=='left-header'?'<img src="'+row.headimg+'">':'';
                        var rightHeader=headerType=='right-header'?'<img src="'+row.headimg+'">':'';
                        var nickName='';
                        if(headerType=='left-header') nickName=row.nickname+'  '+row.addtime;
                        else nickName=row.addtime+'  '+row.nickname;
                        var html = '';
                        chatHtml += html = '<div id="msg'+row.id+'" class="'+headerType+'">\n' +
                            '                <div class="lheader">'+leftHeader+'</div>\n' +
                            '                <div class="content-box">\n' +
                            '                    <div class="nick-name">'+nickName+'</div>\n' +
                            '                    <div class="content-info">\n' +
                            '                        <div class="chatnarr"></div>\n' +
                            '                        <div class="info-content">'+row.content+'</div>\n' +
                            '                    </div>\n' +
                            '                </div>\n' +
                            '                <div class="rheader">'+rightHeader+'</div>\n' +
                            '            </div>';
                        $(html).appendTo('#chat_list');
                        if($("#msg" + row.id).find('img.openhistory').length){
                            var img = new Image();
                            img.src = $("#msg" + row.id).find('img.openhistory').attr('src');
                            img.onload = function () {
                                scrollToBt(sendData['type']);
                            }
                        }else{
                            scrollToBt(sendData['type']);
                        }
                    }
                    // $(chatHtml).appendTo('#chat_list');
                    // scrollToBt(sendData['type']);
                }
                if(openInfo){
                    showOpenInfo(openInfo);
                    if(currentShow=='forecast-pc') popShow('forecast-pc',true);
                }
                if(loading !== ''){
                    layer.close(loading);
                }
                getNewChartInfo();
            }
            
        })
    }
    function showOpenInfo(row) {
        $('#next_sn').html(row.next_sn);
        $('#current_sn').html(row.current_sn);
        var numArr=row.open_num.split(',');
        var numHtml='';
        
        for (i=0;i<numArr.length;i++){
            numHtml+='<span class="n'+numArr[i]+'">'+numArr[i]+'</span>';
        }
        // alert(numHtml);
        $('#current_num').html(numHtml);
        var leftTimeShow=buildTime(row.letf_time,row.fp_time);
        $('#left_time').html(leftTimeShow);
        $('#gyhs').html(row.gyh);
    }
    function buildTime(leftSec,fpSec) {
        if(leftSec == '正在开奖'){
            return '<b class="fping">开奖中</b>';
        }
        var totalSec=parseInt(leftSec);
        if(totalSec<parseInt(fpSec)) return '<b class="fping">封盘中</b>';
        var min  =   Math.floor(totalSec/60);
        var sec = totalSec%60;
        min=(min<10)?'0'+min:min+"";
        sec=(sec<10)?'0'+sec:sec+"";
        var lastStr='';
        lastStr+='<b>'+min[0]+'</b>';
        lastStr+='<b>'+min[1]+'</b>';
        lastStr+='<b>:</b>';
        lastStr+='<b>'+sec[0]+'</b>';
        lastStr+='<b>'+sec[1]+'</b>';
        return lastStr;
    }
    function sendMsg(content) {
        if(content=="") return layer.open({
            content: '发送内容不能为空'
            ,skin: 'msg'
            ,time: 3 //2秒后自动关闭
        });
        lastSendInfo=content;
        $('.keyb-input textarea').val('');
        $.ajax({
            url: '/Application/ajax_chat.php?type=send',
            method: 'post',
            dataType: 'json',
            data:{'content':content,game:"<?php echo $_GET['game'];?>"},
            success: function (res) {
                if(!res.success){
                    if(typeof res.msg!="undefined") layer.open({
                        content: res.msg
                        ,skin: 'msg'
                        ,time: 3 //2秒后自动关闭
                    });
                }
            }
        });
    }
    
    // popup('内容',3000);
    function scrollToBt(type) {
        if(showKeyBord) $('#addSpan').show();
        else $('#addSpan').hide();
        // if(type == 'first'){
        //     $('.chat-content').scrollTop($('#chat_list')[0].scrollHeight + $('img.openhistory').length * 200 +  $('.chat-content').height());
        // }else{
        //     setTimeout(function () {
        //         $('.chat-content').scrollTop($('#chat_list')[0].scrollHeight + $('img.openhistory').length * 200 +  $('.chat-content').height());
        //     },200)
        // }
        document.getElementById('chatBt').scrollIntoView();
        // $('#chatBt')[0].scrollIntoView(false);
        if(showKeyBord){
            setTimeout(function () {
                $('#addSpan').hide();
            },100)
        }
    }
    $('#msg').on('click',function () {
        $('#switchkb').trigger('click');
    })
</script>
<script type="text/javascript">
$('#switchkb').on('click',function () {
    $('#keyboard2').hide();
    hideIframe();
    if($('#keyboard1').is(":hidden")){
        showKeyBord=true;
        $('.chat-content').css('padding-bottom','5.5rem');
        $('#keyboard1').show();
        $('.keyb-input textarea').attr('readonly',true);
        scrollToBt();
    }else{
        $('.keyb-input textarea').attr('readonly',false);
        hideKeyBordMain();
    }
});
$(function () {
    var centerHeight=$('.chat-content').outerHeight();
    $('.chat-content').css('max-height',centerHeight);
    $('.chat-content .last-show').removeAttr('style');
});
$('.back').on('click',function () {
    //window.history.back();
    window.location.href='/action.php?do=gamelist';
});
function hideKeyBordMain() {
    showKeyBord=false;
    $('.chat-content').css('padding-bottom','1.5rem');
    $('#keyboard1').hide();
}
</script>
<script type="text/javascript">
    var currentInputKey='';
    var longPressTimer;
    var mouseDownType='ontouchstart' in document.documentElement?'touchstart':'mousedown';
    var mouseUpType='ontouchstart' in document.documentElement?'touchend':'mouseup';
    $('.last-show').on('click',function () {
        hideKeyBordMain();
    });
    $('.numbaord li,.fast-keyboard li').on(mouseDownType,function (e) {
        console.log(mouseDownType,e);
        e.preventDefault();
        var oldValue=$('.keyb-input textarea').val();
        var newValue=oldValue;
        var type=$(this)[0].hasAttribute('ktype')?$(this).attr('ktype'):$(this).html();
        if(type=='del'){
            newValue=newValue.substr(0,newValue.length-1);
            currentInputKey=type;
        }else if(type=='sp'){
            newValue=newValue+" ";
        }else if(type=='发送'){
            sendMsg(newValue);
        }else if(type=='重复'){
            newValue=lastSendInfo;
        }
        else{
            newValue=newValue+type;
        }
        $('.keyb-input textarea').val(newValue);
        checkInputValue();
        startLongPress();

    }).on(mouseUpType,function (e) {
        currentInputKey='';
        clearInterval(longPressTimer);
    });
    $('.keyb-right').on('click',function () {
        var type=$(this).attr('type');
        var newValue=$('.keyb-input textarea').val();
        if(type=='send'){
            sendMsg(newValue);
        }else{
            hideIframe();
            $('#keyboard1').hide();
            if($('#keyboard2').is(":hidden")){
                $('#keyboard2').show();
                showKeyBord=true;
                $('.chat-content').css('padding-bottom','2.5rem');
                scrollToBt();
            }
            else{
                showKeyBord=false;
                $('.chat-content').css('padding-bottom','1.5rem');
                $('#keyboard2').hide();
            }

        }
    });
    $('.keyb-input').on('input',function () {
        checkInputValue();
    });
    $('.fast-keyboard-url li[data-url]').on('click',function () {
        var url=$(this).data('url');
        window.location.href=url;
    });
    $('.fast-keyboard-url li[data-txt]').on('click',function () {
        var txt=$(this).data('txt');
        sendMsg(txt);
    });
    function checkInputValue() {
        var val=$('.keyb-input textarea').val();
        if(val!==""){
            $('#sendbtn img').attr('src','/Style/newimg/send.png');
            $('#sendbtn').attr('type','send');
        }else{
            $('#sendbtn img').attr('src','/Style/newimg/add.png');
            $('#sendbtn').attr('type','add');
        }
        $('.keyb-input textarea')[0].scrollTop = $('.keyb-input textarea')[0].scrollHeight;
    }
    function startLongPress() {
        longPressTimer=setInterval(function () {
            if(currentInputKey=='del'){
                var oldValue=$('.keyb-input textarea').val();
                var newValue=oldValue.substr(0,oldValue.length-1);
                $('.keyb-input textarea').val(newValue);
            }
        },100);
    }
</script>
<script type="text/javascript">
    var currentShow='';
    function popShow(type,dontShow) {
        var dontShow=dontShow||false;
        $.ajax({
            url:'/action.php?do='+type,
            method:'get',
            dataType:'JSON',
            success:function (res) {
                if(dontShow) $('#iframeBox').html(res.html);
                else showIframe(res.html,type)
            }
        })
    }
    function showIframe(content,type) {
        if(currentShow==type){
            currentShow='';
            $('#iframeBox').hide();
        }else{
            currentShow=type;
            $('#iframeBox').html(content).show();
        }

    }
    function hideIframe() {
        currentShow='';
        $('#iframeBox').hide();
    }
    
</script>
</body>
</html>