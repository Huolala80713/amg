<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "function.php";
$user_id = $_GET['userid'] == "" ? $_SESSION['userid'] : $_GET['userid'];
$game_name = $_GET['game_name'] == "" ? '' : $_GET['game_name'];
$info = getinfo($user_id);
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
$back = $_GET['back'];
$log_type = $_GET['log_type']?$_GET['log_type']:'all';
$list = orderlist($user_id, $day1 , $day2 , $game_name , $log_type , $_GET['p']>0?$_GET['p']:1 , 10);
$order_list = $list['list'];
$game_list = getGameList();
$page = getPageList($list['count'] , 10 , '/Templates/user/orderinfo.php');
?>
<!DOCTYPE html>
<html data-dpr="1" style="font-size: 48px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>投注记录</title>
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
    </style>
</head>
<body>

<header class="m-navbar" style="">
    <?php if($back):?>
    <a href="javascript:history.back(-1)" class="navbar-item">
    <?php else:?>
    <a href="/Templates/user" class="navbar-item">
    <?php endif;?>
        <img src="/Style/newimg/leftar.png" style="height: 0.5rem;">
    </a>
    <div class="navbar-center">
        <span class="navbar-title" style="">投注记录</span>
    </div>
    <a id="xiala_time_select" class="textMore dataType">
        <em><?php echo $type?$type:'今天';?></em>
        <span class="iconfont icon-xiala"></span>
    </a>
</header>
<div class="m-cell" style="margin-top:0.35rem;">
    <div class="cell-item redio_panel">
        <label class="cell-right log_type">
            <input type="radio" name="log_type" value="all" <?php if($log_type == 'all'):?>checked<?php endif;?>/>
            <i class="cell-checkbox-icon"></i>全部
        </label>
        <label class="cell-right log_type">
            <input type="radio" name="log_type" value="zj" <?php if($log_type == 'zj'):?>checked<?php endif;?>/>
            <i class="cell-checkbox-icon"></i>已中奖
        </label>
        <label class="cell-right log_type">
            <input type="radio" name="log_type" value="wzj" <?php if($log_type == 'wzj'):?>checked<?php endif;?>/>
            <i class="cell-checkbox-icon"></i>未中奖
        </label>
        <label class="cell-right log_type">
            <input type="radio" name="log_type" value="dkj" <?php if($log_type == 'dkj'):?>checked<?php endif;?>/>
            <i class="cell-checkbox-icon"></i>待开奖
        </label>
    </div>
    <div class="cell-item redio_panel">
        <div class="cell-left">游戏玩法：</div>
        <div class="cell-right">
            <div id="game_list" style="position: absolute;right: 10px;top: 0;min-height: 1.0rem;align-items: center;-webkit-box-align: center;display: flex;">
                <em><?php echo isset($game_list[$game_name])?$game_list[$game_name]:'全部';?></em>
                <span style="padding-left: 5px;" class="iconfont icon-xiala"></span>
            </div>
        </div>
    </div>
    <style>
        .cell-right{-webkit-justify-content:flex-start !important;justify-content:flex-start !important;font-size: 0.30rem;}
        .cell-right .cell-checkbox-icon{padding-right: 5px;}
        .redio_panel:after{border:0px !important;}
    </style>
    <div class="cell-item">
        <div class="cell-left">开始时间：</div>
        <div class="cell-right"><input type="text" class="cell-input" id="day1" value="<?php echo $day1;?>" disabled="disabled"></div>
    </div>
    <div class="cell-item">
        <div class="cell-left">结束时间：</div>
        <div class="cell-right"><input type="text" id="day2" class="cell-input" value="<?php echo $day2;?>" disabled="disabled"></div>
    </div>
</div>
<input type="button" class="btn-block b-blue sub" value="查询">
<div class="total-log">
    <table class="total-table" style="font-size: 0.3rem;">
        <tbody>
        <tr>
            <th>游戏</th>
            <th>时间</th>
            <th>期数</th>
            <th>玩法</th>
            <th>内容</th>
            <th>金额</th>
            <th>赔率</th>
            <th>盈亏</th>
        </tr>
        <?php
        $allmoney = $allyk = 0;
        if(!empty($order_list)):
            ?>
            <?php foreach ($order_list as $order){?>
            <tr>
                <td><?php echo $order['gamename'];?></td>
                <td><?php echo $order['add_time'];?></td>
                <td><?php echo $order['term'];?></td>
                <td><?php echo $order['mingci'];?></td>
                <td><?php echo str_replace(['chuanjiao','niannian','gufan'],['串角','年念','孤番'] , $order['content']);?></td>
                <td><?php echo $order['money'];?></td>
                <td><?php echo $order['peilv'];?></td>
                <td style="color: <?php echo $order['status'] < 0 ?'red':'';?>"><?php echo $order['status'];?></td>
            </tr>
            <?php
	    if($order['status'] != '已撤单'){
		 $allmoney += $order['money'];
	    }
            if($order['status'] > 0 && $order['status'] != '已撤单' && $order['status'] != '未结算'){
                $allyk += $order['status'];
            }
        }
            ?>
            <tr>
                <th>总计</th>
                <th>--</th>
                <th>--</th>
                <th>--</th>
                <th>--</th>
                <th><?php echo $list['all_touzhu'];?></th>
                <th>--</th>
                <th><?php echo round($list['all_paijian'] - $list['all_touzhu'] , 4);?></th>
            </tr>
        <?php else:?>
            <tr>
                <td colspan="8">暂无记录</td>
            </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>
<?php if($page):?>
    <div class="pages anim anim-3 yema">
        <div class="page_panel">
            <?php echo $page;?>
        </div>
    </div>
<?php endif;?>
<div id="gameBox" class="_problemBox" style="">
    <div class="blackBg"></div>
    <div class="moreLayer">
        <ul>
            <li onclick="changegame('')">
                <a>全部</a>
            </li>
            <?php foreach ($game_list as $key => $val):?>
                <li onclick="changegame('<?php echo $key;?>')">
                    <a><?php echo $val;?></a>
                </li>
            <?php endforeach;?>
        </ul>
        <ul>
            <li>
                <a onclick="closePopBox()">取消</a>
            </li>
        </ul>
    </div>
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
        window.location.href="?day1="+day1+"&day2="+day2+"&type="+type+"&game_name=" + game_name + "&log_type=" + $('input[name="log_type"]:checked').val();
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
        if($(this).find('input[name="log_type"]:checked')){
            var type = '<?php echo $type?$type:'今天';?>';
            search(type);
        }
    });
</script>
</body>
</html>