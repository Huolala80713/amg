<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
    <title>在线客服</title>
    <link rel="Stylesheet" type="text/css" href="/Style/newcss/common.css" />
    <link rel="Stylesheet" type="text/css" href="/Style/newcss/kefu.css" />
    <script type="text/javascript" src="/Style/newjs/jquery-1.10.1.min.js"></script>
    <link rel="Stylesheet" type="text/css" href="/Style/pop/css/style.css" />
    <script type="text/javascript" src="/Style/pop/js/popups.js"></script>
    <style type="text/css">
        .keyb-input textarea{background: none;border: 0px none;width: 100%;height: 100%;padding: 0rem 0.1rem;box-sizing: border-box;height: 0.6rem;outline: none;resize: none;line-height: 0.5rem}
    </style>
    <script type="text/javascript">
        var lastChartID=0;
    </script>
<script type="text/javascript" src="/Style/plus.js"></script>
</head>
<body>
<div class="mainbox max-width">
    <div style="position: fixed;top: 0px;left: 0px;width: 100%;z-index: 100;height: 0.8rem;background-color: white;">
        <div class="header-hegith roomtop" style="height: 0.8rem;padding-top: 0;padding-bottom: 0;">
            <div class="back" onclick="window.history.back();"><img src="/Style/newimg/leftar.png"> 在线客服</div>
        </div>
    </div>


    <div class="chat-content" style="padding-top: 1.5rem;padding-bottom: 1.5rem;width: 100%;">
        <div class="last-show" id="chat_list">

        </div>
        <div id="addSpan" style="margin-top: 5rem"></div>
        <div id="chatBt" style="height: 1px"></div>
    </div>

    <div style="position: fixed;bottom: 0px;left: 0px;width: 100%;">
        <div class="bottom-height chat-input">
            <li style="display: none" class="keyb-left" id="switchkb"><img src="/Style/newimg/switch.png"></li>
            <li class="keyb-input"><textarea id="msg"></textarea></li>
            <li class="keyb-right" type="add" id="butSend"><img src="/Style/newimg/send.png"></li>
        </div>
    </div>
</div>
<script type = "text/javascript">
    var headimg = "<?php echo $_SESSION['headimg'];?>";
    var nickname = "<?php echo $_SESSION['username']?>";
</script>
<script src="/Style/Old/js/kefu.js"></script>

</body>
</html>