<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "../user/function.php";
$left_width = 120;
$game_name = $_GET['game_name'] == "" ? 1 : $_GET['game_name'];
$BetGame = getGameCodeById($game_name);
$table = '';
if ($BetGame == 'pk10') {
    $table = 'fn_lottery1';
} elseif ($BetGame == 'xyft') {
    $table = 'fn_lottery2';
} elseif ($BetGame == 'cqssc') {
    $table = 'fn_lottery3';
} elseif ($BetGame == 'xy28') {
    $table = 'fn_lottery4';
} elseif ($BetGame == 'jnd28') {
    $table = 'fn_lottery5';
} elseif ($BetGame == 'jsmt') {
    $table = 'fn_lottery6';
} elseif ($BetGame == 'jssc') {
    $table = 'fn_lottery7';
} elseif ($BetGame == 'jsssc') {
    $table = 'fn_lottery8';
}
$info = get_query_vals($table , '*' , ['roomid'=>$_SESSION['roomid']]);
if($game_name == 5){
    $wanfa_list = [
        '0027' => '00/27',
        '0225' => '02/25',
        '0423' => '04/23',
        '0621' => '06/21',
        '891819' => '8/9/18/19',
        '1215' => '1215',
        'jida' => '极大',
        'baozi' => '豹子',
        'shunzi' => '顺子',
        'dxds' => '大/小/单/双',
        'dxds_1314_1' => $info['dxds_zongzhu1'] . '@14大/' . $info['dxds_zongzhu1'] . '@14双/' . $info['dxds_zongzhu1'] . '@13小/' . $info['dxds_zongzhu1'] . '@13单',
        'dxds_1314_2' =>  $info['dxds_zongzhu2'] . '@14大/' . $info['dxds_zongzhu2'] . '@14双/' . $info['dxds_zongzhu2'] . '@13小/' . $info['dxds_zongzhu2'] . '@13单',
        'dxds_1314_3' => $info['dxds_zongzhu3'] . '@14大/' . $info['dxds_zongzhu3'] . '@14双/' . $info['dxds_zongzhu3'] . '@13小/' . $info['dxds_zongzhu3'] . '@13单',
        '0126' => '01/26',
        '0324' => '03/24',
        '0522' => '05/22',
        '0720' => '07/20',
        '10111617' => '10/11/16/17',
        '1314' => '13/14',
        'jixiao' => '极小',
        'duizi' => '对子',
        'dadan' => '大单',
        'dashuang' => '大双',
        'xiaodan' => '小单',
        'xiaoshuang' => '小双',
        'zuhe_1314_1' => $info['zuhe_zongzhu1'] . '@14大双/' . $info['zuhe_zongzhu1'] . '@13小单',
        'zuhe_1314_2' => $info['zuhe_zongzhu2'] . '@14大双/' . $info['zuhe_zongzhu2'] . '@13小单',
        'zuhe_1314_3' => $info['zuhe_zongzhu3'] . '@14大双/' . $info['zuhe_zongzhu3'] . '@13小单',
    ];
}else{
    $wanfa_list = [
        'da' => '大',
        'xiao' => '小',
        'dan' => '单',
        'shuang' => '双',
        'long' => '龙',
        'hu' => '虎',
        'dadan' => '大单',
        'xiaodan' => '小单',
        'dashuang' => '大双',
        'xiaoshuang' => '小双',
        'heda' => '和大',
        'hexiao' => '和小',
        'heshuang' => '和双',
        'hedan' => '和单',
        'he341819' => '和3/和4/和18/和19',
        'he561617' => '和5/和6/和16/和17',
        'he781415' => '和7/和8/和14/和15',
        'he9101213' => '和9/和10/和12/和13',
        'he11' => '和11',
        'tema' => '特码'
    ];
}
foreach ($wanfa_list as $key => $value){
    $value = explode('/' , $value);
    foreach ($value as $val){
        $wanfa_list[] = [
            'name' => $val,
            'key' => $key
        ];
    }
    unset($wanfa_list[$key]);
}
$title = '返点赔率表';
$game_list = getGameList();
$peilv_step = $info['peilv_step'];
$user = get_query_vals('fn_user' , '*' , ['roomid'=>$_SESSION['roomid'],'userid'=>$_SESSION['userid']]);
if(empty($user['agent'])){
    $fandian = $info['fandian'];
}else{
    $fandian = json_decode($user['fandian'] , true)[$game_name];
}
$default_fandian = $info['fandian'];
?>
<!DOCTYPE html>
<html data-dpr="1" style="font-size: 48px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $title;?></title>
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
        #peilv_panel{
            padding-top: 2rem;width: 100%;position: relative;
            font-size: 16px;
        }
        #peilv_panel .left_game_list{
            position: absolute;
            left: 0;
            top: 2rem;
            width: <?php echo $left_width;?>px;
            text-align: center;
            background: #fff;
            z-index: 100;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
        #peilv_panel .left_game_list dt{
            background: #efefef;
            font-weight: bold;
        }
        #peilv_panel .left_game_list dt:first-child{
            position: relative;
            height: 45px;
        }
        #peilv_panel .left_game_list dt em{
            position: absolute;
            bottom: 2px;
            left: 4px;
            width: 45px;
            display: block;
            height: 22.5px;
            line-height: 22.5px;
            font-weight: bold;
        }
        #peilv_panel .left_game_list dt i{
            position: absolute;
            top: 4px;
            right: 5px;
            width: 45px;
            display: block;
            height: 22.5px;
            line-height: 22.5px;
            font-weight: bold;
        }
        #peilv_panel .left_game_list dt:first-child::before {
            content: "";
            position: absolute;
            left: -20px;
            top: -29px;
            width: <?php echo $left_width;?>px;
            height: 45px;
            box-sizing: border-box;
            border-bottom: 1px solid #afafaf;
            transform-origin: bottom center;
            transform:rotateZ(20deg) scale(1.41);
            animation: slash 5s infinite ease;
        }
        #peilv_panel .left_game_list dt,#peilv_panel .left_game_list dd{
            line-height: 25px;width: <?php echo $left_width;?>px;text-align: center;padding: 10px 0;
        }
        #peilv_panel .left_game_list dd:nth-child(2n+1){
            background: #efefef;
        }
        #peilv_panel .left_game_list dd:nth-child(2n){
            background: #fff;
        }
        #peilv_panel .fandian_list{
            overflow: scroll;
            padding-left: <?php echo $left_width;?>px;
        }
        #peilv_panel .fandian_list dl{
            float: left;
        }
        #peilv_panel .fandian_list dt{
            font-weight: bold;
            background: #efefef;
        }
        #peilv_panel .fandian_list dl dd,#peilv_panel .fandian_list dl dt{
            line-height: 25px;width: <?php echo $left_width;?>px;text-align: center;padding: 10px 0;
        }
        #peilv_panel .left_gfandian_listame_list dd:nth-child(2n+1){
            background: #efefef;
        }
        #peilv_panel .fandian_list dd:nth-child(2n){
            background: #fff;
        }
    </style>
</head>
<body>
<div style="position: fixed;width: 100%;;top: 0;left: 0;z-index: 9999;">
    <header class="m-navbar" style="">
        <a href="javascript:history.back(-1)" class="navbar-item">
            <img src="/Style/newimg/leftar.png" style="height: 0.5rem;">
        </a>
        <div class="navbar-center">
            <span class="navbar-title" style=""><?php echo $title;?></span>
        </div>
    </header>
    <div class="m-cell" style="margin-bottom: 0">
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
    </div>
</div>
<div id="peilv_panel">
    <dl class="left_game_list">
        <dt><em>玩法</em><i>返点</i></dt>
        <?php foreach($wanfa_list as $key => $value):?>
            <dd><?php echo $value['name'];?></dd>
        <?php endforeach;?>
    </dl>
    <div class="fandian_list">
        <div style="width: <?php echo $left_width * ($fandian / 0.01 + 1);?>px;">
            <?php for($i=$fandian;$i>=0;$i-=0.01):?>
                <dl style="float: left;">
                    <dt><?php echo round($i,2);?>(%)</dt>
                    <?php foreach($wanfa_list as $key => $value):?>
                        <dd>
                        <?php
                            echo "赔率" . getManagePeilv($game_name , $_SESSION['roomid'] , $info[$value['key']] , $i , $value['key']);
//                            $peilv_step = round($info[$value['key']] / 10000 + $info[$value['key']] / 1000000 , 7);
//
//                            $peilv = $info[$value['key']] - ($fandian - $i)/0.01 * $peilv_step - ($default_fandian - $fandian)/0.01 * $peilv_step;
//
//                            echo "赔率" . ($peilv>=0?round(floor($peilv * 10000) / 10000 , 4):0);
                        ?>
                        </dd>
                    <?php endforeach;?>
                </dl>
            <?php endfor;?>
        </div>
    </div>
</div>

<div id="gameBox" class="_problemBox" style="">
    <div class="blackBg"></div>
    <div class="moreLayer">
        <ul>
            <?php foreach ($game_list as $key => $val):?>
                <li onclick="changegame('<?php echo $key;?>')">
                    <a><?php echo $val;?></a>
                </li>
            <?php endforeach;?>
        </ul>
        <ul>
            <li>
                <a>取消</a>
            </li>
        </ul>
    </div>
</div>
<script src="/Style/newweb/js/ydui.js"></script>
<link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
<script type="text/javascript" src="/Style/pop/js/popups.js"></script>
<script type="text/javascript">
    function changegame(game_type){
        window.location.href="?game_name=" + game_type;
    }
</script>
</body>
</html>