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
                <div class="back" onclick="window.history.back();"><img src="/Style/newimg/leftar.png"></div>
                <div class="titlexb">代理中心</div>
            </div>
        </div>
        <div class="user-main" style="padding-bottom: 0px;">
            <img src="images/img.png" alt="" width="100%">
            <div class="agentIntroDes">
                <em>当您能看到这个页面，说明您的账号即是玩家账号也是代理账号，即可以自己投注，也可以发展下级玩家，赚取返点佣金。</em>
                <h3><i class="iconfont icon-huangguan"></i>如何赚取返点？</h3>
                <div class="agentIntroP">
                    <p>可获得的返点，等于自身返点与下级返点的差值，如自身返点5，下级返点3，你将能获得下级投注金额2%的返点，如下级投注100元，你将会获得2元。
                    </p>
                    <p>点击下级开户，可查看自身返点，也可为下级设置返点。</p>
                </div>
                <h3><i class="iconfont icon-yonghukaihu"></i>如何为下级开户？</h3>
                <div class="agentIntroP">
                    <p>点击下级开户，先为您的下级设置返点，设置成功后会生成一条邀请码，将邀请码发送给您的下级注册，注册后他就是您的下级，点击会员管理，就能查看他注册的账号；</p>
                    <p>如果您为下级设置的是代理类型的账号，那么您的下级就能继续发展下级，如果设置的是玩家类型，那么您的下级只能投注，不能再发展下级，也看不到代理中心；</p>
                </div>
                <h3><i class="iconfont icon-aixin02-mian"></i>温馨提示：</h3>
                <div class="agentIntroP">
                    <p>返点不同赔率也不同，点击返点赔率表，可查看返点赔率；</p>
                    <p>返点越低，赔率就越低，建议为下级设置的返点不要过低；</p>
                    <p>可在代理报表、投注明细、交易明细查看代理的发展情况；</p>
                    <p>建议开设的下级也是代理类型，无论发展了几级，您都能获得返点。</p>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .user-main {
        color: #000;
        padding-bottom:20px;
    }
    .user-main>div {
        box-sizing: border-box;
        width: 100%;
        background-color: #fff;
        overflow: hidden;
    }
    .agentIntroDes {
        color: #333;
        padding: 10px 10px;
        line-height: 30px;
        font-size: 15px;
    }
    .agentIntroDes h3 {
        color: #dc3b40;
        font-size: 22px;
        line-height: 40px;
    }
    .agentIntroDes h3 .iconfont{
        font-size: 22px;
        padding-right: 5px;
    }
    .agentIntroDes p {
        padding-left: 25px;
        position: relative;
        white-space:normal;
        word-break:break-all;

    }
    .agentIntroDes p:before {
        content: "";
        position: absolute;
        width: 8px;
        height: 8px;
        background: #b9bcc2;
        border-radius: 50%;
        border: 2px solid #fff;
        -webkit-box-shadow: 0 0 0 1px #b9bcc2;
        box-shadow: 0 0 0 1px #b9bcc2;
        left: 4px;
        top: 5px;
        z-index: 1;
    }
    .agentIntroDes p:after {
        content: "";
        position: absolute;
        height: 86%;
        height: calc(100% - 8px);
        border-left: 1px dotted #b9bcc2;
        left: 9px;
        top: 15px;
    }
    .agentIntroP p:last-child:after{
        content: "";
        position: absolute;
        height: 0;
        border-left: 0;
    }
</style>
</body>
</html>

