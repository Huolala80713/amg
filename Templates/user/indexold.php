<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "function.php";
$info = getinfo($_SESSION['userid']);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="user-scalable=no,width=device-width" />
    <meta name="baidu-site-verification" content="W8Wrhmg6wj" />
    <meta content="telephone=no" name="format-detection">
    <meta content="1" name="jfz_login_status">
    <script type="text/javascript" src="js/record.origin.js"></script>
    <link rel="stylesheet" type="text/css" href="css/common.css?v=1.2" />
    <link rel="stylesheet" type="text/css" href="css/new_cfb.css?v=1.2" />
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-1.7.2.js?v=1.2"></script>
    <script type="text/javascript" src="js/global.js?v=1.2"></script>
    <script type="text/javascript" src="js/common.v3.js?v=1.2"></script>
    <script type="text/javascript" src="js/jweixin-1.0.0.js"></script>
    <title>个人中心</title>
<script type="text/javascript" src="/Style/plus.js"></script>
</head>
<body>

    <div class="wx_cfb_container wx_cfb_account_center_container">
        <div class="wx_cfb_account_center_wrap">
            <div class="wx_cfb_ac_fund_detail">
                <div class="user_info clearfix" style="position: relative;">
                    <img src="/Style/newimg/leftar.png" style="position: absolute;left: 1rem;top:50%;margin-top: -1rem;height: 2rem;width: auto" onclick="window.history.back()">
                    <div class="user_photo" style="margin-left: 2rem"><img src="<?php echo $_SESSION['headimg'];
?>" style="width:45px; height:45px; "></div>
                    <div class="user_txt">
                        <div class="p1">
                            <?php echo $_SESSION['username'];
?>
                        </div>
                        <div class="p2">欢迎光临【
                            <?php echo get_query_val("fn_room", "roomname", array("roomid" => $_SESSION['roomid']));
?>】中心</div>
                    </div>
                </div>
                <div class="fund_info">
                    <div class="kv_tb_list clearfix">
                        <div class="kv_item">
                            <span class="val"><?php echo get_query_val("fn_user", "money", array("roomid" => $_SESSION['roomid'], 'userid' => $_SESSION['userid']));
?></span>
                            <span class="key">我的钱包</span>
                        </div>
                        <div class="kv_item">
                            <span class="val"><?php echo $info['yk'];
?></span>
                            <span class="key">今日盈亏</span>
                        </div>
                        <div class="kv_item">
                            <span class="val"><?php echo $info['liu'];
?></span>
                            <span class="key">今日流水</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--入口-->
            <div class="wx_cfb_entry_list">
                <div class="space_5"></div>
                <a href="orderinfo.php" data-toggle="modal" data-target="#wxtipsDialog" class="entry_item clearfix">
                    <div class="entry_name">
                        <span class="ui_ico_size_60 ui_entry_ico ui_entry_ico_3"></span>
                        <span class="name">投注信息</span>
                    </div>
                    <div class="entry_tips">
                        <span class="ui_entry_arrow"></span>
                        <span class="tips"></span>
                    </div>
                </a>

                <div class="space_10"></div>

                <a href="paylog.php" data-toggle="modal" data-target="#wxtipsDialog" class="entry_item clearfix">
                    <div class="entry_name">
                        <span class="ui_ico_size_60 ui_entry_ico ui_entry_ico_5"></span>
                        <span class="name">充值记录</span>
                    </div>
                    <div class="entry_tips">
                        <span class="ui_entry_arrow"></span>
                        <span class="tips"></span>
                    </div>
                </a>
                <div class="space_10"></div>
                <a href="marklog.php" class="entry_item entry_item_no_border clearfix">
                    <div class="entry_name">
                        <span class="ui_ico_size_60 ui_entry_ico ui_entry_ico_6"></span>
                        <span class="name">交易明细</span>
                    </div>
                    <div class="entry_tips">
                        <span class="ui_entry_arrow"></span>
                        <span class="tips"></span>
                    </div>
                </a>
                <div class="space_10"></div>
               <!--地址--> <!--a href="#" class="entry_item entry_item_no_border clearfix"-->
                    <a class="entry_item entry_item_no_border clearfix" onclick="alert(&#39;在线充值暂未开放，请联系在线客服上分！&#39;)">
                    <div class="entry_name">
                        <span class="ui_ico_size_60 ui_entry_ico ui_entry_ico_1"></span><!--图标-->
                        <span class="name">在线充值</span>
                    </div>
                    <div class="entry_tips">
                        <span class="ui_entry_arrow"></span>
                        <span class="tips"></span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="wx_cfb_fixed_btn_box" style="display: none">
        <div class="wx_cfb_fixed_btn_wrap">
            <div class="btn_box clearfix">
                <a href="/action.php?do=room&rid=<?php echo $_SESSION['roomid'];
?>" class="btn tel_btn clearfix">
                    <em class="ico ui_ico_size_40 ui_tel_ico"></em><span class="txt">返回游戏</span>
                </a>
            </div>
        </div>
    </div>

    </div>
</body>
</html>