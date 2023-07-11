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
    <link rel="stylesheet" type="text/css" href="css/common.css?v=1.2" />
    <link rel="stylesheet" type="text/css" href="css/new_cfb.css?v=1.2" />
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-1.7.2.js?v=1.2"></script>
    <script type="text/javascript" src="js/global.js?v=1.2"></script>
    <script type="text/javascript" src="js/common.v3.js?v=1.2"></script>
    <script type="text/javascript" src="js/jweixin-1.0.0.js"></script>
    <link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
    <script type="text/javascript" src="/Style/pop/js/popups.js"></script>
    <title>个人中心</title>
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
<script type="text/javascript" src="/Style/plus.js"></script>
</head>
<body>

    <div class="wx_cfb_container wx_cfb_account_center_container">
        <div class="wx_cfb_account_center_wrap">
            <div class="wx_cfb_ac_fund_detail">
                <div class="user_info clearfix">
                    <div class="back" onclick="window.history.back();"><img src="/Style/newimg/leftar.png"></div>
                    <div class="titlexb">个人信息</div>
                </div>
            </div>
                <!--入口-->
            <div class="wx_cfb_entry_list">
                <div class="blockrow">
                    <li class="lefttitle"><?php echo $_SESSION['username'];?></li>
                    <li class="headerup"><img src="<?php echo $_SESSION['headimg'];?>"></li>
                    <li class="rightarr"><img src="/Templates/user/images/entry_arrow_ico.png"></li>
                </div>
                <div class="blockrow" data-url="/Templates/user/set-userid.php">
                    <li class="lefttitle">修改昵称</li>
                    <li class="rightarr"><img src="/Templates/user/images/entry_arrow_ico.png"></li>
                </div>
                <div class="blockrow" data-url="/Templates/user/set-pass.php">
                    <li class="lefttitle">修改密码</li>
                    <li class="rightarr"><img src="/Templates/user/images/entry_arrow_ico.png"></li>
                </div>
            </div>
        </div>
    </div>
    <input type="file" style="visibility: hidden;height: 1px;width: 1px;" name="userheader" value="" id="userheader">
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

<script type="text/javascript">
    $('#userheader').on('change',function () {
        var formData = new FormData();
        var img = $(this).val();
        if (img) {
            formData.append("file", $(this)[0].files[0]);
            formData.append("field", 'img');
            jqtoast("上传中...");
            $.ajax({
                url: "/action.php?do=uploader",
                type: "post",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                async: false,
                success: function (res) {
                    res=JSON.parse(res);
                    $('.jq-toast').remove();
                    console.log(res);
                    if (res.status != "1") {
                        jqtoast(res.error);
                    } else {
                        $('.headerup img').attr('src',res.header);
                        jqtoast("上传成功");
                    }
                },
                error: function (e) {
                    alert("网络错误,请重试！！");
                }
            });
        }
    });
    $('.headerup').on('click',function () {
        $('#userheader').click();
    })
    $('.blockrow[data-url]').on('click',function () {
        var url=$(this).data('url');
        window.location.href=url;
    })
</script>
</body>
</html>