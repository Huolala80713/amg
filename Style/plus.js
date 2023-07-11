var is_first = true;
var is_scroll = false;
var user_money = 1000;
function addChatListData(message , username , headimg , fun , type){
    let date = new Date();
    let hour = date.getHours();              //返回指定日期时（0-23）      10      10时
    let minutes = date.getMinutes();         //返回指定日期分（0-59）      20      20分
    let seconds = date.getSeconds();
    if(hour < 9){
        hour = '0' + hour;
    }
    if(minutes < 9){
        minutes = '0' + minutes;
    }
    if(seconds < 9){
        seconds = '0' + seconds;
    }
    let datetime = hour + ':' + minutes + ':' + seconds;
    let rightHeader = '';
    let leftHeader = '';
    if(type == 1){
        var headerType='right-header';
        rightHeader='<img src="'+headimg+'">';
        var nickName = datetime +'  ' + username;
    }else{
        var headerType='left-header';
        leftHeader='<img src="'+headimg+'">';
        var nickName = username +'  ' + datetime;
    }
    var html = '<div class="'+headerType+'">\n' +
        '                <div class="lheader">' + leftHeader + '</div>\n' +
        '                <div class="content-box">\n' +
        '                    <div class="nick-name">'+nickName+'</div>\n' +
        '                    <div class="content-info">\n' +
        '                        <div class="chatnarr"></div>\n' +
        '                        <div class="info-content">'+message+'</div>\n' +
        '                    </div>\n' +
        '                </div>\n' +
        '                <div class="rheader">'+rightHeader+'</div>\n' +
        '            </div>';
    $(html).appendTo('#chat_list');
    if(typeof (fun) == 'function'){
        fun();
    }
}
function manageContent(message , fun , username , headimg , type , money){
    let old_message = message;
    let return_content = [];
    let mingci = [];
    let content = [];
    let all_in = false;
    if(message.indexOf('上分') > -1 || message.indexOf('下分') > -1 || message.indexOf('取消') > -1){
        let data = {
            message:message
        };
        return JSON.stringify(data);
    }
    if(type == 'xinyong'){
        message.forEach(function (value , index) {
            value = value.split('/');
            return_content.push({
                mingci:value[0],
                content:value[1],
                count:1
            });
            if(mingci.indexOf(value[0]) == '-1'){
                mingci.push(value[0])
            }
            if(content.indexOf(value[1]) == '-1'){
                content.push(value[1])
            }
        });
        message = mingci.join('') + '/' + content.join('') + '/' + money;
        let data = {
            list:return_content,
            money:money,
            message:message
        };
        return JSON.stringify(data);
    }
    if(message.indexOf(' ') >= 0){
        addChatListData(old_message , username , headimg , fun , 1);
        addChatListData('投注内容不能含有空格' , '机器人' , '/upload/202304281682645358.php ' , fun , 2);
        return false;
    }
    message = message.replace('冠亚和' , "和");
    message = message.replace('冠亚' , "和");
    message = message.replace('冠军' , "1/");
    message = message.replace('亚军' , "2/");
    message = message.replace('冠' , "1/");
    message = message.replace('亚' , "2/");
    message = message.replace("一", "1/");
    message = message.replace("二", "3/");
    message = message.replace("三", "4/");
    message = message.replace("四", "4/");
    message = message.replace("五", "5/");
    message = message.replace("六", "6/");
    message = message.replace("七", "7/");
    message = message.replace("八", "8/");
    message = message.replace("九", "9/");
    message = message.replace("十", "0/");
    message = message.replace(".", "/");
    message = message.replace(/[位名各-]/u, "/");
    message = message.replace(/(和|合|H|h)\//u, "$1");
    message = message.replace(/[和合Hh]/u, "和/")
    message = message.replace(/(大单|小单|大双|小双|大|小|单|双|龙|虎)\//u , "$1")
    message = message.replace(/\/(大单|小单|大双|小双|大|小|单|双|龙|虎)/u , "$1")
    message = message.replace(/(大单|小单|大双|小双|大|小|单|双|龙|虎)/u , "/$1/")
    message = message.replace(/(梭哈)\//u , "$1")
    message = message.replace(/\/(梭哈)/u , "$1")
    message = message.replace(/(梭哈)/u , "/$1");
    if (message == ''){
        // addChatListData(message , username , headimg , fun , 1);
        addChatListData('投注内容不能为空' , '机器人' , '/upload/202304281682645358.php ' , fun , 2);
        return false;
    }
    if (message.substr(0, 1) == '/'){
        message = '1/' + message;
    }
    let messages = message.split('/');
    if(messages.length == 2){
        message = '1/' + message;
        messages = message.split('/');
    }
    if (messages.length != 3 || messages[0] == "" || messages[1] == ""){
        addChatListData(old_message , username , headimg , fun , 1);
        addChatListData('投注格式错误' , '机器人' , '/upload/202304281682645358.php ' , fun , 2);
        return;
    }
    if(parseInt(messages[2]) < 1 && messages[2] != '梭哈'){
        addChatListData(old_message , username , headimg , fun , 1);
        addChatListData('下注格式出错,投注金额必须大于1元' , '机器人' , '/upload/202304281682645358.php ' , fun , 2);
        return;
    }
    if (messages[1] == '和') {
        addChatListData(old_message , username , headimg , fun , 1);
        addChatListData('下注格式出错！冠亚和值下注格式为:和3/100' , '机器人' , '/upload/202304281682645358.php ' , fun , 2);
        return;
    }
    let error = '';
    if(messages[0] == '和'){
        mingci.push('和');
        if (messages[1] == "大单" || messages[1] == "大双" || messages[1] == "小双" || messages[1] == "小单") {
            addChatListData(old_message , username , headimg , fun , 1);
            addChatListData('下注格式出错！冠亚和值无此类型下注！' , '机器人' , '/upload/202304281682645358.php ' , fun , 2);
            return ;
        }else if (messages[1] == "大" || messages[1] == "小" || messages[1] == "单" || messages[1] == "双") {
            content.push(messages[1]);
        }else{
            let hezhi = messages[1].split('');
            let new_data = [];
            let ii_1_b = true;
            let ii_1 = true;
            $.each(hezhi , function(index,val){
                if (!ii_1_b && ii_1 == "1"){
                    val = "1" + val
                }
                ii_1 = val;
                if (ii_1_b){
                    ii_1_b = false
                }
                if(val != '1'){
                    new_data.push(val)
                }
            })
            $.each(new_data , function(index,val){
                if(val > 19 || val < 3){
                    error = '冠亚和投注格式错误，冠亚和值为3 - 19！';
                }else{
                    content.push(val);
                }
            })

        }
    }else{
        let mingci_list = messages[0].split('');
        let content_list = messages[1].split('');
        mingci_list.find(function(value,index,arr){
            if(['0','6','7','8','9'].indexOf(value) != -1 && (messages[1] == "龙" || messages[1] == "虎")){
                error = '龙虎投注仅限1~5名！';
            }else{
                mingci.push(value);
            }
        })

        content_list.find(function(value,index,arr){
            content.push(value);
        })
    }
    if(error){
        addChatListData(old_message , username , headimg , fun , 1);
        addChatListData(error , '机器人' , '/upload/202304281682645358.php ' , fun , 2);
        return ;
    }
    let mingci_manage_list = {};
    let content_manage_list = {};
    //统计计算注数
    mingci.forEach(function(val){
        if(mingci_manage_list[val.toString()]){
            mingci_manage_list[val.toString()] += 1;
        }else{
            mingci_manage_list[val.toString()] = 1;
        }
    });
    content.forEach(function(val){
        if(content_manage_list[val.toString()]){
            content_manage_list[val.toString()] += 1;
        }else{
            content_manage_list[val.toString()] = 1;
        }
    });
    $.each(mingci_manage_list , function(mingci,value){
        $.each(content_manage_list , function(content,val){
            return_content.push({
                mingci:mingci,
                content:content,
                count:value * val
            });
        });
    });
    let data = {
        list:return_content,
        money:messages[2],
        message:old_message
    };
    return JSON.stringify(data);
}
$(function (){
    $("#map").css('bottom' , ($("#keyboard-panel").outerHeight(true) + 10) + 'px');
    $("#map").on('click' , function (){
        $('.chat-content').scrollTop($('#chat_list').height());
        $("#map").hide();
        is_scroll = false;
    });
    $('.chat-content').on('scroll' , function (e){
        if($('#chat_list').height() - ($('.chat-content').scrollTop() + $('.chat-content').height()) > 100){
            is_scroll = true;
            $("#map").show();
        }else{
            is_scroll = false;
            $("#map").hide();
        }
    });
    let touzhu_list = [];
    let touzhu_count = 0;
    let money = 0;
    $("#xinyong_touzhu").on('click' , function (){
        $.ajax({
            url:'/action.php?do=xinyong_touzhu',
            method:'get',
            dataType:'JSON',
            success:function (res){
                $('#keyboard-panel').hide();
                $('#xinyong_touzhu_panel').height($(window).height() - $("#room-header").outerHeight(true));
                $('#xinyong_touzhu_panel').html(res.html).show();
                $("#money").html(money * touzhu_count);
                $("#count").html(touzhu_count);
            }
        });
    })

    $("#xinyong_touzhu_panel").on('click' , '.touzhu-panel-left li' , function (){
        $("#xinyong_touzhu_panel").find('.touzhu-panel-left li').removeClass('checked');
        $(this).addClass('checked');
        $("#kuaijie").hide();
        $("#mingci").hide();
        $("#liangmian").hide();
        $("#guanyahe").hide();
        $("#" + $(this).attr('data-id')).show();
    });
    $("#xinyong_touzhu_panel").on('click' , '#mingci li,#guanyahe li,#liangmian li' , function (){
        if($(this).hasClass('checked')){
            $(this).removeClass('checked');
        }else{
            $(this).addClass('checked');
        }
        manangeTouZhu($(this).attr('data-type'));
    });
    $("#xinyong_touzhu_panel").on('click' , '#kuaijie li' , function (){
        if($(this).hasClass('checked')){
            $(this).removeClass('checked');
        }else{
            $(this).addClass('checked');
        }
        kuaijieManangeTouZhu();
    });
    $("#xinyong_touzhu_panel").on('click' , '.money_panel li' , function (){
        money = parseInt($(this).html());
        $("#money").html(money * touzhu_count);
        $("#count").html(touzhu_count);
        $("#money_input").val(money);
    });
    $("#xinyong_touzhu_panel").on('input' , '#money_input' , function (){
        money = parseInt($(this).val());
        $("#money").html(money * touzhu_count);
        $("#count").html(touzhu_count);
        $("#money_input").val(money);
    });
    $("#xinyong_touzhu_panel").on('click' , '#mingci p,#liangmian p' , function (){
        let mingci_id = $(this).attr('data-mingci');
        if($(this).find('i').hasClass('icon-img-up')){
            $(this).find('i').addClass('icon-img');
            $(this).find('i').removeClass('icon-img-up');
            $('.mingci' + mingci_id).show();
        }else{
            $(this).find('i').removeClass('icon-img');
            $(this).find('i').addClass('icon-img-up');
            $('.mingci' + mingci_id).hide();
        }
    });
    $('#xinyong_touzhu_panel').on('click' , '#cancel' , function(){
        $('#keyboard-panel').show();
        $('#xinyong_touzhu_panel').html('').hide();
    })
    $('#xinyong_touzhu_panel').on('click' , '#chongzhi' , function(){
        touzhu_list = [];
        touzhu_count = 0;
        money = 0;
        $("#money").html(money * touzhu_count);
        $("#count").html(touzhu_count);
        $("#money_input").val('');
        $("#xinyong_touzhu_panel").find('.touzhu-panel-right li.checked').removeClass('checked');
    })
    $('#xinyong_touzhu_panel').on('click' , '#xiazhu' , function(){
        let username = $(this).attr('data-username');
        let headimg = $(this).attr('data-headimg');
        if(touzhu_count==0) return layer.open({
            content: '请选择投注内容'
            ,skin: 'msg'
            ,time: 3 //2秒后自动关闭
        });
        if(money==0) return layer.open({
            content: '请输入投注金额'
            ,skin: 'msg'
            ,time: 3 //2秒后自动关闭
        });
        var content = [];
        $.each(touzhu_list , function (index,val){
            if(val['mingci'] == '冠亚和'){
                content.push(val['mingci'] + '/' + (val['content']) + '/' + money);
            }else{
                content.push(val['mingci'] + '/' + (val['content']==10?0:val['content']) + '/' + money);
            }
        });
        content = manageContent(content , scrollToBt , username , headimg , 'xinyong' , money);
        if(!content){
            return ;
        }else{
            lastSendInfo = JSON.parse(content)['message'];
        }
        console.log(lastSendInfo);
        $('.keyb-input textarea').val('');
        $('#keyboard-panel').show();
        $('#xinyong_touzhu_panel').html('').hide();
        $.ajax({
            url: '/Application/ajax_chat.php?type=send',
            method: 'post',
            dataType: 'json',
            data:{'content':content,game:game,submit_type:1},
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
    })
    function kuaijieManangeTouZhu(){
        touzhu_list = [];
        touzhu_count = 0;
        $.each($("#kuaijie").find('.mingci li.checked') , function (index,mingci) {
            $.each($("#kuaijie").find('.content li.checked') , function (index,content) {
                touzhu_list.push({
                    mingci:$(mingci).attr('data-mingci'),
                    content:$(content).attr('data-content')
                });
                touzhu_count += 1;
            });
        });
        $("#money").html(money * touzhu_count);
        $("#count").html(touzhu_count);
    }

    function manangeTouZhu(type){
        touzhu_list = [];
        touzhu_count = 0;
        $.each($("#" + type).find('.content li.checked') , function (index,content) {
            touzhu_list.push({
                mingci:$(content).attr('data-mingci'),
                content:$(content).attr('data-content')
            });
            touzhu_count += 1;
        });
        $("#money").html(money * touzhu_count);
        $("#count").html(touzhu_count);
    }
    $("#chat_list").on('click' , "img.openhistory" , function(){
        //location.href = $(this).attr('src');
        layer.open({
            type: 1
            ,content: '<img style="width: 100%" src="' + $(this).attr('src') + '" />'
            ,anim: 'up'
            //,style: 'position:fixed; top:50%; left:0; width: 100%; height: 200px; padding:10px 0; border:none;'
        });
    });
})
// callback 参数要传一个function
var plusReady = function (callback) {
    if (window.plus) {
        callback();
    } else {
        document.addEventListener('plusready', callback);
    }
};
//plusReady是一个function
plusReady(function () {
    var firstBack = 0;
    var handleBack = function () {
        var currentWebview = plus.webview.currentWebview();
        var topWebview = plus.webview.getTopWebview();
        var now = Date.now || function () {
            return new Date().getTime();
        };
        currentWebview.canBack(function (evt) {
            /**
             * 有可后退的历史记录，则后退。
             * 否则，关闭当前窗口。
             * 如果当前窗口是入口页，那么执行退出的逻辑。
             */
            history.back();
            /*if (currentWebview.id === plus.runtime.appid) {
                if (!firstBack) {
                    firstBack = now();
                    plus.nativeUI.toast('再按一次退出应用');

                    // 定时俩秒内
                    setTimeout(function () {
                        firstBack = 0;
                    }, 2000);
                } else if (now() - firstBack < 2000) {
                    // 退出应用
                    plus.runtime.quit();
                }
            } else {
                // canBack: 查询Webview窗口是否可后退
                if (evt.canBack) {
                    history.back();
                } else {
                    currentWebview.close('auto');
                }
            }*/
        });
    };
    // backbutton判断物理返回键
    plus.key.addEventListener('backbutton', handleBack);
});