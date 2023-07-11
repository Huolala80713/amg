<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "../user/function.php";
$info = getUserStatistics($_SESSION['userid'] , date('Y-m-d') , date('Y-m-d'));
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
    <title>代理中心</title>
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
                <div class="back" onclick="location.href='/Templates/user'"><img src="/Style/newimg/leftar.png"></div>
                <div class="titlexb">代理中心</div>
            </div>
        </div>
        <div style="padding: 15px 0px;">
            <div class="user-options">
                <a href="shuoming.php" class="active">
                    <span>代理说明</span>
                    <i class="fr"><span class="iconfont right"></span></i>
                </a>
<!--                <a href="agentreport.php" class="active">-->
<!--                    <span>代理报表</span>-->
<!--                    <i class="fr"><span class="iconfont right"></span></i>-->
<!--                </a>-->
                <a href="xiajibaobiao.php" class="active">
                    <span>下级报表</span>
                    <i class="fr"><span class="iconfont right"></span></i>
                </a>
                <a href="manageinvite.php" class="active">
                    <span>生成邀请码</span>
                    <i class="fr"><span class="iconfont right"></span></i>
                </a>
                <a href="invitecode.php" class="active">
                    <span>邀请码管理</span>
                    <i class="fr"><span class="iconfont right"></span></i>
                </a>
                <a href="manageinvite.php?type=kaihu" class="active">
                    <span>立即开户</span>
                    <i class="fr"><span class="iconfont right"></span></i>
                </a>
<!--                <a href="huiyuan.php" class="active">-->
<!--                    <span>会员管理</span>-->
<!--                    <i class="fr"><span class="iconfont right"></span></i>-->
<!--                </a>-->
                <a href="orderinfo.php" class="active">
                    <span>投注明细</span>
                    <i class="fr"><span class="iconfont right"></span></i>
                </a>
                <a href="marklog.php" class="active">
                    <span>交易明细</span>
                    <i class="fr"><span class="iconfont right"></span></i>
                </a>
            </div>
        </div>

    </div>
</div>

<style type="text/css">
    .user-options {
        padding: 0;
        background: #fff;
    }
    .user-options {
        padding: 0;
    }
    .user-options a {
        width: 100%;
        display: block;
        line-height: 30px;
        padding: 10px;
        color: #333;
        border-bottom: 1px solid #cfcfcf;
    }
</style>
</body>
</html>

