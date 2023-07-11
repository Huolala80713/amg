<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="user-scalable=no,width=device-width" />
    <meta name="baidu-site-verification" content="W8Wrhmg6wj" />
    <meta content="telephone=no" name="format-detection">
    <meta content="1" name="jfz_login_status">
    <script type="text/javascript" src="/Templates/user/js/record.origin.js"></script>
    <link rel="stylesheet" type="text/css" href="/Templates/user/css/common.css?v=1.2" />
    <link rel="stylesheet" type="text/css" href="/Templates/user/css/new_cfb.css?v=1.2" />
    <script type="text/javascript" src="/Templates/user/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Templates/user/js/jquery-1.7.2.js?v=1.2"></script>
    <script type="text/javascript" src="/Templates/user/js/global.js?v=1.2"></script>
    <script type="text/javascript" src="/Templates/user/js/common.v3.js?v=1.2"></script>
    <script type="text/javascript" src="/Templates/user/js/jweixin-1.0.0.js"></script>
    <title>游戏规则</title>
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
<body>
<div class="wx_cfb_container wx_cfb_account_center_container">
    <div class="wx_cfb_account_center_wrap">

        <!--入口-->
        <?php if($_GET['game_id']){?>
            <div class="wx_cfb_ac_fund_detail">
                <div class="user_info clearfix">
                    <div class="back" onclick="window.history.back();"><img src="/Style/newimg/leftar.png"></div>
                    <div class="titlexb">游戏规则</div>
                </div>
            </div>
            <div style="padding: 15px;line-height: 25px;">
                <?php foreach ($list as $game){?>
                    <?php if($game['id'] == $_GET['game_id']){echo $game['rules'];}?>
                <?php }?>
            </div>
        <?php }else{?>
            <div class="wx_cfb_ac_fund_detail">
                <div class="user_info clearfix">
                    <div class="back" onclick="location.href='action.php?do=gamelist'"><img src="/Style/newimg/leftar.png"></div>
                    <div class="titlexb">游戏规则</div>
                </div>
            </div>
            <div class="wx_cfb_entry_list">
                <?php foreach ($list as $game){?>
                    <?php if($game['gameopen'] == 'false'){continue;}?>
                    <div class="blockrow" data-url="/action.php?do=guize&game_id=<?php echo $game['id'];?>">
                        <li class="lefttitle"><img style="height: 40px;width: auto" src="/Style/newimg/game_name_<?php echo $game['id'];?>.png"></li>
                        <li class="rightarr"><img src="/Templates/user/images/entry_arrow_ico.png"></li>
                    </div>
                <?php }?>
            </div>
        <?php }?>
    </div>
</div>
<link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
<script type="text/javascript" src="/Style/pop/js/popups.js"></script>
<script type="text/javascript">
    $('.blockrow').on('click' , function(){
        var url = $(this).data('url');
        location.href = url;
    });
</script>
</body>
</html>

