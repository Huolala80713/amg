<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "function.php";
$user_id = $_GET['userid'] == "" ? $_SESSION['userid'] : $_GET['userid'];
$game_name = $_GET['game_name'] == "" ? '' : $_GET['game_name'];
if(date('H') < 6){
    $date = date('Y-m-d' , strtotime('-1days'));
}else{
    $date = date('Y-m-d');
}
if($_GET['day1'] && $_GET['day2']){
    $day1 = $_GET['day1'];
    $day2 = $_GET['day2'];
}else{
    $day1 = $day2 = $date;
}
$type = $_GET['type'];
$info = getUserStatistics($user_id, $day1 , $day2);
$game_list = getGameList();
?>
<!DOCTYPE html>
<html data-dpr="1" style="font-size: 48px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>个人报表</title>
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
        .total-table tr th,td{
            border-bottom:1px solid #ccc;padding: 8px;
        }
        .total-table th{
            background:#ddd;
            color: #000;
            font-weight: bold;
            font-size: 0.25rem;
        }
        .total-table td{
            color:#000;
            font-size: 0.25rem;

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
        .wallet{

        }
        .statics{
            /*border-radius: 8px;*/
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
            background: #5dccf9;
            color: #fff;
            padding: 15px 10px 10px 10px;
            position: relative;
        }
        .statics ul{
            display: flex;
        }
        .statics ul li{
            flex: 1;
            text-align: left;
            font-size: 16px;
            line-height: 35px;
        }
        .statics .today_tip{
            background: #ec971f;
            color: #fff;
            /*border-radius: 15px;*/
            border-bottom-right-radius: 0;
            border-top-right-radius: 0;
            position: absolute;
            right: 0px;
            top: 12px;
            padding: 3px 15px;
        }
        .nav_list{
            padding: 10px 10px;
            display: flex;
            background: #cfcfcf;
            /*border-radius: 10px;*/
            border-top-left-radius: 0px;
            border-top-right-radius: 0px;
        }
        .nav_list li a{
            color: #777;
        }
        .nav_list li{
            flex: 1;
            text-align: center;
            justify-content:center;
            font-size: 17px;
            color: #666;
        }
        .listul li{
            width: 33.3%;
        }
        .listul li{
            float: left;
            text-align: center;
            font-size: 0.30rem;
            border-bottom: 1px solid #d0d0d0;
            margin: 0;
            padding: 8px 0;
        }
        .listul li:nth-child(3n+1), .listul li:nth-child(3n+2) {
            border-right: 1px solid #d0d0d0;
        }
        .listul li span{
            display: block;
            color: #ff6818;
            padding-bottom: 3px;
        }
    </style>
</head>
<body>

<header class="m-navbar" style="">
    <a href="/Templates/user/" class="navbar-item">
        <img src="/Style/newimg/leftar.png" style="height: 0.5rem;">
    </a>
    <div class="navbar-center">
        <span class="navbar-title" style="">个人报表</span>
    </div>
    <a id="xiala_time_select" class="textMore dataType">
        <em><?php echo $type?$type:'今天';?></em>
        <span class="iconfont icon-xiala"></span>
    </a>
</header>
<div style="position: relative;display: none">
    <div class="m-cell" style="margin-top:5px;width: 75%;margin-bottom: 0;">
        <div class="cell-item" style="box-sizing: border-box;">
            <div class="cell-left">开始时间：</div>
            <div class="cell-right"><input type="text" class="cell-input" style="width: 100%;" id="day1" value="<?php echo $day1;?>" disabled="disabled"></div>
        </div>
        <div class="cell-item" style="box-sizing: border-box;">
            <div class="cell-left">结束时间：</div>
            <div class="cell-right"><input type="text" id="day2" class="cell-input" style="width: 100%;" value="<?php echo $day2;?>" disabled="disabled"></div>
        </div>
    </div>
    <input type="button" class="btn-block b-blue sub" value="查询" style="border-radius: 0;width:25%;margin: 0;position: absolute;height: 2rem;line-height: 2rem;right:0;top: 0;">
</div>
<div style="padding: 0px 0px;">
    <div class="wallet">
        <div class="statics">
            <ul>
                <li style="font-size: 20px;text-align: center">盈亏金额</li>
            </ul>
            <ul>
                <li style="font-size: 20px;text-align: center"><?php echo number_format($info['user']['yk'] , 2 , '.' , '');?></li>
            </ul>
        </div>
        <ul class="nav_list">
            <li style="text-align: left;font-size: 13px;">盈亏计算公式：中奖-投注+活动+返点</li>
        </ul>
    </div>
</div>
<div  style="overflow-y: auto;">
    <ul  class="listul">
        <li onclick="location.href='/Templates/user/orderinfo.php?back=1&day1=' + $('#day1').val() + '&day2=' + $('#day2').val() + '&type=<?php echo $type;?>'"><span  style="text-decoration: underline;"><?php echo $info['user']['liu'];?></span>投注金额</li>
        <li onclick="location.href='/Templates/user/orderinfo.php?back=1&day1=' + $('#day1').val() + '&day2=' + $('#day2').val() + '&type=<?php echo $type;?>&log_type=zj'" ><span style="text-decoration: underline;"><?php echo number_format($info['user']['zj'] , 2 , '.' , '');?></span>中奖金额</li>
        <li onclick="location.href='/Templates/user/marklog.php?back=1&day1=' + $('#day1').val() + '&day2=' + $('#day2').val() + '&type=<?php echo $type;?>&log_type=huodong'"><span style="text-decoration: underline;"><?php echo number_format($info['user']['huodong'] , 2 , '.' , '');?></span>活动礼金</li>
        <li onclick="location.href='/Templates/user/marklog.php?back=1&day1=' + $('#day1').val() + '&day2=' + $('#day2').val() + '&type=<?php echo $type;?>&log_type=fandian'"><span style="text-decoration: underline;"><?php echo number_format($info['user']['fandian'] , 2 , '.' , '');?></span>返点金额</li>
        <li onclick="location.href='/Templates/user/shenqing.php?back=1&day1=' + $('#day1').val() + '&day2=' + $('#day2').val() + '&type=<?php echo $type;?>&log_type=recharge'"><span style="text-decoration: underline;"><?php echo number_format($info['user']['sf'] , 2 , '.' , '');?></span>充值金额</li>
        <li onclick="location.href='/Templates/user/shenqing.php?back=1&day1=' + $('#day1').val() + '&day2=' + $('#day2').val() + '&type=<?php echo $type;?>&log_type=finance'"><span style="text-decoration: underline;"><?php echo number_format($info['user']['xf'] , 2 , '.' , '');?></span>提现金额</li>
        <?php foreach ($game_list as $key=>$value):?>
        <li  style="background: rgba(195, 197, 194, 0.33); border-right: 0px solid rgb(208, 208, 208); color: red; height: 50px; line-height: 40px;"><?php echo $value;?></li>
        <li  style="background: rgba(195, 197, 194, 0.33); border-right: 0px solid rgb(208, 208, 208); color: red; height: 50px; line-height: 40px;"></li>
        <li  style="background: rgba(195, 197, 194, 0.33); border-right: 0px solid rgb(208, 208, 208); color: red; height: 50px; line-height: 40px;"></li>
        <li onclick="location.href='/Templates/user/orderinfo.php?back=1&day1=' + $('#day1').val() + '&day2=' + $('#day2').val() + '&type=<?php echo $type;?>&game_name=<?php echo $key;?>'"><span style="text-decoration: underline;"><?php echo (isset($info['game'][$key]['touzhu']) && $info['game'][$key]['touzhu']) ? sprintf("%.2f",$info['game'][$key]['touzhu']) : 0.00;?></span>投注金额</li>
        <li onclick="location.href='/Templates/user/orderinfo.php?back=1&day1=' + $('#day1').val() + '&day2=' + $('#day2').val() + '&type=<?php echo $type;?>&game_name=<?php echo $key;?>&log_type=zj'"><span style="text-decoration: underline;"><?php echo (isset($info['game'][$key]['zhongjian']) && $info['game'][$key]['zhongjian']) ? sprintf("%.2f",$info['game'][$key]['zhongjian']) : 0.00;?></span>中奖金额</li>
        <li onclick="location.href='/Templates/user/marklog.php?back=1&day1=' + $('#day1').val() + '&day2=' + $('#day2').val() + '&type=<?php echo $type;?>&log_type=fandian&game_name=<?php echo $key;?>'"><span  style="text-decoration: underline;"><?php echo (isset($info['game'][$key]['fandian']) && $info['game'][$key]['fandian']) ?  sprintf("%.2f",$info['game'][$key]['fandian']) : 0.00;?></span>返点金额
        <?php endforeach;?>
    </ul>
</div>
<div id="popBox" class="_problemBox" style="">
    <div class="blackBg"></div>
    <div class="moreLayer">
        <ul>
            <li onclick="chagedate('今天' , this , '<?php echo $date;?>','<?php echo $date;?>')">
                <a>今天</a>
            </li>
            <li onclick="chagedate('昨天' , this , '<?php echo date("Y-m-d",strtotime($date . "- 1day"));?>','<?php echo date("Y-m-d",strtotime($date . "- 1day"));?>')">
                <a >昨天</a>
            </li>
            <li onclick="chagedate('本周' , this , '<?php echo date("Y-m-d",strtotime($date . " - " . (date('w')?(date('w') - 1):7) . " days"));?>','<?php echo $date;?>')">
                <a >本周</a>
            </li>
            <li onclick="chagedate('上周' , this , '<?php echo date("Y-m-d",strtotime($date . " -1 weeks - " . (date('w')?(date('w') - 1):7) . " days"));?>','<?php echo date("Y-m-d",strtotime($date . " -1 weeks + " . ( 7 - date('w')) . " days"));?>')">
                <a >上周</a>
            </li>
            <li onclick="chagedate('本月' , this , '<?php echo date("Y-m-01" , strtotime($date));?>','<?php echo date("Y-m-d",strtotime(date("Y-m-01" , strtotime($date)) . " + 1month -1day"));?>')">
                <a >本月</a>
            </li>
            <li onclick="chagedate('上月' , this , '<?php echo date("Y-m-01",strtotime($date . "- 1 month"));?>','<?php echo date("Y-m-d",strtotime(date("Y-m-01" , strtotime($date)) . "- 1 day"));?>')">
                <a >上月</a>
            </li>
        </ul>
        <ul>
            <li>
                <a onclick="closePopBox()">取消</a>
            </li>
        </ul>
    </div>
</div>
<script src="/Style/newweb/js/ydui.js"></script>
<script type="text/javascript">
    var lang = {
        title: '选择日期',
        cancel: '取消',
        confirm: '确定',
        year: '',
        month: '',
        day: '',
        hour: '',
        min: '',
        sec: ''
    };
    new Rolldate({
        el: '#day1',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        lang:lang
    });
    new Rolldate({
        el: '#day2',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        lang:lang
    });
    let game_name = '<?php echo $game_name?$game_name:'';?>';
    $(".sub").click(function(){
        var type = '<?php echo $type?$type:'今天';?>';
        search(type);
    });
    function search(type){
        var day1 = $("#day1").val();
        var day2 = $("#day2").val();
        d1 = new Date(day1.replace(/\-/g, "\/"));
        d2 = new Date(day2.replace(/\-/g, "\/"));
        if(d1>d2){
            var temp = day1;
            day1 = day2;
            day2 = temp;
        }
        window.location.href="?day1="+day1+"&day2="+day2+"&type="+type+"&game_name=" + game_name;
    }
    function chagedate(type,dom , day1,day2){
        $("#day1").val(day1);
        $("#day2").val(day2);
        search(type);
    }
    function changegame(game_type){
        game_name = game_type;
        var type = '<?php echo $type?$type:'今天';?>';
        search(type);
    }
    $(".log_type").on('click' , function () {
        if($(this).find('input[name="game_name"]:checked')){
            var type = '<?php echo $type?$type:'今天';?>';
            search(type);
        }
    });
</script>
<?php
include_once("../../Templates/public/menu.html");
?>
</body>
</html>