<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "function.php";
if(date('H') < 6){
    $date = date('Y-m-d' , strtotime('-1days'));
}else{
    $date = date('Y-m-d');
}
$info = getUserStatistics($_SESSION['userid'] , $date , $date);
$user = get_query_vals('fn_user','*',array('roomid'=>$_SESSION['roomid'],'userid'=>$_SESSION['userid']));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="user-scalable=no,width=device-width" />
    <meta name="baidu-site-verification" content="W8Wrhmg6wj" />
    <meta content="telephone=no" name="format-detection">
    <meta content="1" name="jfz_login_status">
    <link rel="stylesheet" type="text/css" href="/Templates/user/css/common.css?v=1.2" />
    <link rel="stylesheet" type="text/css" href="/Templates/user/css/new_cfb.css?v=1.2" />
    <script type="text/javascript" src="/Templates/user/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Templates/user/js/jquery-1.7.2.js?v=1.2"></script>
    <script type="text/javascript" src="/Templates/user/js/global.js?v=1.2"></script>
    <script type="text/javascript" src="/Templates/user/js/common.v3.js?v=1.2"></script>
    <script type="text/javascript" src="/Templates/user/js/jweixin-1.0.0.js"></script>
    <link rel="stylesheet" type="text/css" href="/Style/newcss/iconfont/iconfont.css?v=1.2" />
    <link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
    <script type="text/javascript" src="/Style/pop/js/popups.js"></script>
    <title>钱包中心</title>
    <script type="text/javascript" src="/Style/plus.js"></script>
    <style type="text/css">
        .user_info{display: flex;position: relative}
        .user_info img{height: 2rem;width: auto;}
        .user_info .back{position: absolute;left: 1rem;top: 1rem}
        .user_info .titlexb{flex-grow: 10;display: flex;justify-content: center;font-size: 1.6rem;color: white;font-weight: 700}

        .blockrow{display: flex;justify-content: space-between;padding: 1rem 2rem;box-sizing: border-box;background: white;border-bottom: 1px solid #DEDEDE;align-items: center}
        .blockrow li{list-style: none;flex-grow: 0;flex-shrink: 0;}
        .blockrow li.lefttitle{flex-grow: 10;font-size: 1.5rem;color: #333333}
        .headerup{margin-right: 0.5rem}
        .headerup img{width: 5rem;height: 5rem;}
        .rightarr img{height: 2rem;width: auto}
    </style>
</head>
<body style="background: #efefef;">
<div class="wx_cfb_container wx_cfb_account_center_container">
    <div class="wx_cfb_account_center_wrap">
        <!--入口-->
        <div class="wx_cfb_ac_fund_detail">
            <div class="user_info clearfix">
                <div class="back" onclick="location.href='/action.php?do=gamelist'"><img src="/Style/newimg/leftar.png"></div>
                <div class="titlexb">钱包中心</div>
            </div>
        </div>
        <div style="padding: 15px 10px;">
            <div class="wallet">
                <div class="statics">
                    <ul>
                        <li style="font-size: 20px;">总资产</li>
                    </ul>
                    <ul>
                        <li style="font-size: 28px;flex: 2"><?php  echo number_format($user['money'] , 2 , '.' , '');?></li>
                        <li>活动：<?php echo number_format($info['user']['huodong'] , 2 , '.' , '');?></li>
                    </ul>
                    <ul>
                        <li>流水：<?php echo number_format($info['user']['liu'] , 2 , '.' , '');?></li>
                        <li>返点：<?php echo number_format($info['user']['fandian'] , 2 , '.' , '');?></li>
                        <li>盈亏：<?php echo number_format($info['user']['yk'] , 2 , '.' , '');?></li>
                    </ul>
                    <p class="today_tip">今日统计</p>
                </div>
                <ul class="nav_list">
                    <li>
                        <a href="/Templates/user/codepay/index.php">
                            <img src="/Style/newimg/shangfen.png">
                            申请上分
                        </a>
                    </li>
                    <li>
                        <a href="/Templates/user/codepay/down.php">
                            <img src="/Style/newimg/xiafen.png">
                            申请下分
                        </a>
                    </li>
                    <li>
                        <a href="/Templates/user/bank.php">
                            <img src="/Style/newimg/shoukuan.png">
                            收款方式
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div style="padding: 15px 10px;">
            <dl class="tab_list">
                <?php if($user['isagent'] == 'true'):?>
                <dt>
                    <a href="/Templates/daili">
                        <span class="iconfont icon-dailizhongxin-T4"></span>
                        代理中心
                    </a>
                    <a href="shenqing.php">
                        <span class="iconfont icon-shenqingjilu-2"></span>
                        申请记录
                    </a>
                    <a href="orderinfo.php">
                        <span class="iconfont icon-youxijilu-hover"></span>
                        游戏记录
                    </a>

                </dt>
                <dt>
                    <a href="marklog.php">
                        <span class="iconfont icon-jiaoyijilu"></span>
                        资金记录
                    </a>
                    <a href="plstatement.php">
                        <span class="iconfont icon-gerenbaobiao-hover"></span>
                        个人报表
                    </a>
                    <a href="/Templates/user/setting.php">
                        <span class="iconfont icon-gerenxinxi"></span>
                        个人信息
                    </a>
                </dt>
                <dt>
                    <a href="/action.php?do=logout">
                        <span class="iconfont icon-tuichudenglu"></span>
                        退出登录
                    </a>
                </dt>
                <?php else:?>
                    <dt>

                        <a href="shenqing.php">
                            <span class="iconfont icon-shenqingjilu-2"></span>
                            申请记录
                        </a>
                        <a href="orderinfo.php">
                            <span class="iconfont icon-youxijilu-hover"></span>
                            游戏记录
                        </a>
                        <a href="marklog.php">
                            <span class="iconfont icon-jiaoyijilu"></span>
                            资金记录
                        </a>
                    </dt>
                    <dt>
                        <a href="plstatement.php">
                            <span class="iconfont icon-gerenbaobiao-hover"></span>
                            个人报表
                        </a>
                        <a href="/Templates/user/setting.php">
                            <span class="iconfont icon-gerenxinxi"></span>
                            个人信息
                        </a><a href="/action.php?do=logout">
                            <span class="iconfont icon-tuichudenglu"></span>
                            退出登录
                        </a>

                    </dt>
                <?php endif;?>
            </dl>
        </div>
    </div>
</div>

<style type="text/css">
    .wallet{

    }
    .statics{
        border-radius: 10px;
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
        border-radius: 15px;
        border-bottom-right-radius: 0;
        border-top-right-radius: 0;
        position: absolute;
        right: 0px;
        top: 12px;
        padding: 3px 15px;
    }
    .nav_list{
        padding: 15px 10px;
        display: flex;
        background: #fff;
        border-radius: 10px;
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
    }
    .nav_list li img{
        padding-bottom: 5px;
        display: block;
        width: 30px;
        margin: 0 auto;
        text-align: center;
    }
    .tab_list{
        border-radius: 10px;
        background: #fff;
        color: #777;
        position: relative;
    }
    .tab_list dt{
        display: flex;
        border-bottom: 1px solid #dfdfdf;
    }
    .tab_list dt:last-child{
        border: none;
    }
    .tab_list dt a{
        flex: 1;
        color: #777;
        text-align: center;
        padding: 25px 0;
        font-size: 16px;
        border-right: 1px solid #dfdfdf;
    }
    .tab_list dt a:last-child{
        border: 0;
    }
    .tab_list dt a .iconfont{
        display: block;
        font-size: 35px;
        height: 35px;
        line-height: 35px;
        margin-bottom: 2px;
    }
    .tab_list dt a .iconfont.icon-gerenxinxi{
        color: #b00926;
    }
    .tab_list dt a .iconfont.icon-tuichudenglu{
        color: #c7be07;
    }
    .tab_list dt a .iconfont.icon-dailizhongxin-T4{
        color: #f030c0;
    }
    .tab_list dt a .iconfont.icon-jiaoyijilu{
        color: #13227a;
    }
    .tab_list dt a .iconfont.icon-gerenbaobiao-hover{
        color: #11b51c;
    }
    .tab_list dt a .iconfont.icon-youxijilu-hover{
        color: #1296db;
    }
    .tab_list dt a .iconfont.icon-shenqingjilu-2{
        color: #e65a37;
    }
</style>
</body>
</html>

