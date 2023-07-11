<?php
include(dirname(dirname((preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
include_once("adminfunction.php");
//print_r($_SESSION);exit;
$admin = [];
if($_SESSION['agent_user'] != "" && $_SESSION['agent_pass'] != "" && $_SESSION['agent_room'] != ""){
    $sql = get_query_vals('fn_room', '*', array('roomid' => $_SESSION['agent_room']));
    if(!$sql){
        $_SESSION['agent_user'] = "";
        $_SESSION['agent_pass'] = "";
        $_SESSION['agent_room'] = "";
        echo '<script>top.location.href="ypsl7jdSWL.php";</script>';
        exit();
    }
    $admin = get_query_vals('fn_admin', '*', array('roomid' => $_SESSION['agent_room'],'roomadmin' => $_SESSION['agent_user'],'roompass' => $_SESSION['agent_pass']));
    if(empty($admin)){
        $_SESSION['agent_user'] = "";
        $_SESSION['agent_pass'] = "";
        $_SESSION['agent_room'] = "";
        echo '<script>top.location.href="ypsl7jdSWL.php";</script>';
        exit();
    }
    //如果缓存的sseion token不存咋，则推出 20230701
    if($admin['token'] && $_SESSION['token'] != $admin['token']){
        $_SESSION['agent_user'] = "";
        $_SESSION['agent_pass'] = "";
        $_SESSION['agent_room'] = "";
        echo '<script>alert("该账号在其他地方登录了")</script>';
        echo '<script>top.location.href="ypsl7jdSWL.php";</script>';
        exit();
    }
}else{
    $_SESSION['agent_user'] = "";
    $_SESSION['agent_pass'] = "";
    $_SESSION['agent_room'] = "";
    echo '<script>top.location.href="ypsl7jdSWL.php";</script>';
    exit();
}
$admin['auth'] = array_unique(array_filter(explode(',', $admin['auth']) , function($val){
    if($val !== ''){
        return true;
    }else{
        return false;
    }
}));
$version = get_query_val('fn_room', 'version', array('roomid' => $_SESSION['agent_room']));
if($_GET['m'] == ''){
    $page = '首页';
}elseif($_GET['m'] == 'update_query'){
    $page = '游戏设置';
}elseif($_GET['m'] == 'setting'){
    $page = '系统设置';
}elseif($_GET['m'] == 'user'){
    $page = '用户管理';
}elseif($_GET['m'] == 'userjia'){
    $page = '假人管理';
}elseif($_GET['m'] == 'userdata'){
    $page = '用户报表';
}elseif($_GET['m'] == 'termcount'){
    $page = '当期统计';
}elseif($_GET['m'] == 'ban'){
    $page = '禁言管理';
}elseif($_GET['m'] == 'report'){
    $page = '报表查询';
}elseif($_GET['m'] == 'chat'){
    $page = '聊天管理';
}elseif($_GET['m'] == 'robot'){
    $page = '自动拖管理';
}elseif($_GET['m'] == 'extend'){
    $page = '代理系统';
}elseif($_GET['m'] == 'share'){
    $page = '分享房间';
}elseif($_GET['m'] == 'buy'){
    $page = '套餐续费';
}elseif($_GET['m'] == 'flyorder'){
    $page = '飞单系统';
}elseif($_GET['m'] == 'clean'){
    $page = '数据清除';
}elseif($_GET['m'] == 'kaijiang'){
    $page = '开奖管理';
}elseif($_GET['m'] == 'manage'){
    $page = '分数管理';
}elseif($_GET['m'] == 'admin'){
    $page = '管理员管理';
}elseif($_GET['m'] == 'dolog'){
    $page = '管理员操作日志';
}
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $console; ?>娱乐管理系统| <?php echo $page;?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="dist/css/font-awesome.min.css">
        <link rel="stylesheet" href="dist/css/ionicons.min.css">
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
        <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="dist/js/app.min.js"></script>
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <style type="text/css">
        #tableList td{
            text-align: center !important;
        }
        #tableList th{
            text-align: center !important;
        }
    </style>
    <body class="hold-transition skin-blue sidebar-mini">
        <audio id="mp3">
            <source = src="dist/audio.mp3" type="audio/mp3">
        </audio>
        <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">
            <!-- Logo -->
            <a href="index.html" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">
                  <b><?php echo mb_substr($console, 0, 1, 'utf-8'); ?></b>
                  <?php echo mb_substr($console, 1, 1, 'utf-8'); ?>
                </span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b><?php echo $console;?>管理系统</b></span>
            </a>
            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="dropdown messages-menu">
                            <!-- Menu toggle button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope-o"></i><span class="label label-success fade sf">0</span></a>
                            <ul class="dropdown-menu">
                                <li class="header">你收到<span class="sf">3</span>条上分消息</li>
                                <li>
                                    <!-- inner menu: contains the messages -->
                                    <ul class="menu">
                                        <li id="sfdata">

                                        </li>
                                    </ul>
                                <!-- /.menu -->
                                </li>
                                <li class="footer"><a href="zb9n8rUvp0.php?m=manage&a=up">查看全部消息</a></li>
                            </ul>
                        </li>
                        <!-- /.messages-menu -->
                        <li class="dropdown messages-menu">
                            <!-- Menu toggle button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell-o"></i><span class="label label-warning fade xf">0</span></a>
                            <ul class="dropdown-menu">
                                <li class="header">你收到<span class="xf">3</span>条下分消息</li>
                                <li>
                                    <!-- inner menu: contains the messages -->
                                    <ul class="menu">
                                        <li id="xfdata">

                                        </li>
                                        <!-- end message -->
                                    </ul>
                                    <!-- /.menu -->
                                </li>
                                <li class="footer"><a href="zb9n8rUvp0.php?m=manage&a=down">查看全部消息</a></li>
                            </ul>
                        </li>
                        <!-- /.messages-menu -->
                        <li class="dropdown messages-menu">
                            <!-- Menu toggle button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flag-o"></i><span class="label label-danger fade pay">0</span></a>
                            <ul class="dropdown-menu">
                                <li class="header">你收到<span class="pay">0</span>条充值消息</li>
                                <li>
                                    <!-- inner menu: contains the messages -->
                                    <ul class="menu">
                                        <li id="paydata"></li>
                                        <!-- end message -->
                                    </ul>
                                    <!-- /.menu -->
                                </li>
                                <li class="footer"><a href="#">查看全部消息</a></li>
                            </ul>
                        </li>
                        <!-- /.messages-menu -->
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs"><?php echo $_SESSION['agent_user']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo $_SESSION['agent_user']; ?> -
                                        <?php echo get_query_val("fn_room", "version", array("roomid" => $_SESSION['agent_room'])); ?>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <?php if($version != '尊享版'){?>
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">升级版本</a>
                                    </div>
                                    <?php }?>
                                    <div class="pull-right">
                                        <a href="javascript:logout();" class="btn btn-default btn-flat">安全退出</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <!--右侧面板按钮 li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li-->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo $_SESSION['agent_user']; ?></p>
                        <!-- Status -->
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <ul class="sidebar-menu">
                    <li class="header">导航</li>
                    <!-- Optionally, you can add icons to the links -->
                    <li class="<?php if($_GET['m'] == "")echo 'active';?>"><a href="zb9n8rUvp0.php"><i class="fa fa-dashboard"></i> <span>控制台</span></a></li>
                    <?php if($admin['auth_type'] == 1){ ?>
                    <li class="<?php if($_GET['m'] == "admin_g_setting")echo 'active';?>"><a href="/zb9n8rUvp0.php?m=admin_g_setting&g=1">
                            <i class="fa fa-dashboard"></i> <span>最大赔率设置</span>
                        </a>
                    </li>
                    <?php }?>
                    <?php
                        $menulist = getMenuList();
                        foreach ($menulist as $k => $value){
                            if(!in_array($value['id'] , $admin['auth']) && $admin['auth_type'] != 1)continue;
                    ?>
                        <li class="treeview <?php if($_GET['m'] == $value['m'])echo 'active'; ?>">
                            <a href="<?php echo $value['url'];?>">
                                <i class="<?php echo $value['fa'];?>"></i>
                                <span><?php echo $value['name'];?></span>
                                <?php if(count($value['child_list']) || $value['m'] == 'g_setting' || $value['m'] == 'zhudan'):?>
                                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                                <?php endif;?>
                                <?php if($value['m'] == 'manage'):?>
                                    <span class="pull-right-container" style="margin-right: 25px;">
                                        <span class="label pull-right bg-red fade sf">3</span>
                                        <span class="label pull-right bg-blue fade xf">3</span>
                                    </span>
                                <?php endif;?>
                                <?php if($value['m'] == 'chat'):?>
                                    <span class="label pull-right bg-purple fade msg" style="margin-right: 25px;">3</span>
                                <?php endif;?>

                            </a>
                            <?php if(count($value['child_list']) || $value['m'] == 'g_setting' || $value['m'] == 'zhudan'):?>
                            <ul class="treeview-menu">
                                <?php if($value['m'] == 'g_setting'):?>
                                    <?php $game_list = getGameList();?>
                                    <?php foreach ($game_list as $key => $game):?>
                                        <li class="<?php if($_GET['g'] == $key)echo 'active';?>">
                                            <a href="<?php echo 'zb9n8rUvp0.php?m=' . $value['m'] . '&g=' . $key;?>">
                                                <i class="fa fa-circle-o"></i><?php echo $game;?>
                                            </a>
                                        </li>
                                    <?php endforeach;?>
                                <?php elseif($value['m'] == 'zhudan'):?>
                                    <?php $game_list = getGameList();?>
                                    <?php foreach ($game_list as $key => $game):?>
                                        <li class="<?php if($_GET['g'] == $key)echo 'active';?>">
                                            <a href="<?php echo 'zb9n8rUvp0.php?m=' . $value['m'] . '&g=' . $key;?>">
                                                <i class="fa fa-circle-o"></i><?php echo $game;?>

                                            </a>
                                        </li>
                                    <?php endforeach;?>
                                <?php else:?>
                                    <?php foreach ($value['child_list'] as $key => $child):?>
                                        <?php if(!in_array($child['id'] , $admin['auth']) && $admin['auth_type'] != 1)continue;?>
                                        <li class="<?php if($_GET['a'] == $child['a'])echo 'active';?>">
                                            <a href="<?php echo $child['url'];?>">
                                                <i class="fa fa-circle-o"></i><?php echo $child['name'];?>
                                                <?php if($value['m'] == 'manage'):?>
                                                    <?php if($child['a'] == 'down'):?>
                                                        <span class="label pull-right bg-blue fade xf" >3</span>
                                                    <?php endif;?>
                                                    <?php if($child['a'] == 'up'):?>
                                                        <span class="label pull-right bg-red fade sf" >3</span>
                                                    <?php endif;?>
                                                <?php endif;?>
                                                <?php if($value['m'] == 'chat'):?>
                                                    <?php if($child['a'] == 'custom'):?>
                                                        <span class="label pull-right bg-purple fade msg">3</span>
                                                    <?php endif;?>
                                                <?php endif;?>

                                            </a>
                                        </li>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </ul>
                            <?php endif;?>
                        </li>
                    <?php }?>




<!--                    <li class="--><?php //if($_GET['m'] == 'extend')echo 'active'; ?><!--">-->
<!--                        <a href="zb9n8rUvp0.php?m=extend"><i class="fa fa-user-plus"></i> <span>代理系统</span></a>-->
<!--                    </li>-->
<!--                    <li class="treeview --><?php //if($_GET['m'] == 'flyorder')echo 'active'; ?><!--">-->
<!--                        <a href="#">-->
<!--                            <i class="fa fa-commenting-o"></i>-->
<!--                            <span>飞单设置</span>-->
<!--                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>-->
<!--                        </a>-->
<!--                        <ul class="treeview-menu">-->
<!--                            <li class="--><?php //if($_GET['f'] == "fly")echo 'active'; ?><!--">-->
<!--                                <a href="zb9n8rUvp0.php?m=flyorder&f=fly"><i class="fa fa-circle-o"></i>飞单设置</a>-->
<!--                            </li>-->
<!--                            <li class="--><?php //if($_GET['f'] == "old")echo 'active'; ?><!--">-->
<!--                                <a href="zb9n8rUvp0.php?m=flyorder&f=old"><i class="fa fa-circle-o"></i>飞单历史</a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!--                    <li class="--><?php //if($_GET['m'] == 'buy')echo 'active'; ?><!--">-->
<!--                        <a href="zb9n8rUvp0.php?m=buy"><i class="fa fa-cog"></i> <span>修改密码</span></a>-->
<!--                    </li>-->
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        <div id="callout" class="callout" style="position:fixed;width:350px;top:20px;z-index:9999999;right:10px;display:none">
            <h4 id="title">收到一笔上分消息</h4>
            <p id="cont">来自XXXX的上分消息</p>
        </div>
        <div id="flycallout" class="callout callout-info" style="position:fixed;width:350px;top:130px;z-index:9999999;right:10px;display:none">
            <h4>飞单成功</h4>
            <p>本期飞单指令已经提交</p>
        </div>
        <!-- Content Wrapper. Contains page content -->
        <?php
            if($_GET['m'] == ''){
                require 'templates/dashboard.html';
            }elseif($_GET['m'] == 'setting'){
                $t = $_GET['a'];
                if($t == 'setting'){
                    require 'templates/roomsetting.html';
                }else{
                    require 'templates/agentsetting.html';
                }
            }elseif($_GET['m'] == 'admin'){
                require 'templates/admin/index.html';
            }elseif($_GET['m'] == 'g_setting'){
                $gameID=$_GET['g'];
                if($gameID == '5'){
                    require 'templates/gamesetting/jnd28.html';
                }if($gameID == '9'){
                    require 'templates/gamesetting/lhc.html';
                }else{
                    require 'templates/gamesetting/xyft.html';
                }
            }elseif($_GET['m'] == 'admin_g_setting'){//超级管理员设置最大值 20230630
                $game_arr = getGameList();
                $gameID=$_GET['g'];
                if($gameID == '5'){
                    require 'templates/admingamesetting/jnd28.html';
                }if($gameID == '9'){
                    require 'templates/admingamesetting/lhc.html';
                }else{
                    require 'templates/admingamesetting/xyft.html';
                }
            }elseif($_GET['m'] == 'zhudan'){
                $gameID=$_GET['g'];
                if($gameID == '5'){
                    require 'templates/zhudan/jnd28.html';
                }else{
                    require 'templates/zhudan/xyft.html';
                }
            }elseif($_GET['m'] == 'user'){
                require 'templates/user.html';
            }elseif($_GET['m'] == 'userjia'){
                require 'templates/userjia.html';
            }elseif($_GET['m'] == 'userdata'){
                require 'templates/userdata.html';
            }elseif($_GET['m'] == 'termcount'){
                require 'templates/termcount.html';
            }elseif($_GET['m'] == 'ban'){
                require 'templates/ban.html';
            }elseif($_GET['m'] == 'report'){
                $a=$_GET['a'];
                switch ($a){
                    case 'huizong':
                        $t = $_GET['t'];
                        if($t=='user'){
                            require 'templates/markreport/huizong_user.html';
                        }else{
                            require 'templates/markreport/huizong.html';
                        }
                        break;
                    case 'up':
                        require 'templates/markreport/upmark.html';
                        break;
                    case 'term':
                        require 'templates/markreport/termmark.html';
                        break;
                    case 'none':
                        require 'templates/markreport/none.html';
                        break;
                    case 'baobiao':
                        require 'templates/markreport/baobiao.html';
                        break;
                    case 'jishi':
                        require 'templates/markreport/jishi.html';
                        break;
                }
            }elseif($_GET['m'] == 'manage'){
                $a=$_GET['a'];
                switch ($a){
                    case 'up':
                        require 'templates/manage/up.html';
                        break;
                    case 'down':
                        require 'templates/manage/down.html';
                        break;
                }
            }elseif($_GET['m'] == 'tui'){
                require 'templates/tui.html';
            }elseif($_GET['m'] == 'chat'){
                $a=$_GET['a'];
                switch ($a){
                    case 'room':
                        require 'templates/chat/room.html';
                        break;
                    case 'custom':
                        require 'templates/chat/custom.html';
                        break;
                }
            }elseif($_GET['m'] == 'robot'){
                $a=$_GET['a'];
                switch ($a){
                    case 'plan':
                        require 'templates/robots/plan.html';
                        break;
                    case 'robots':
                        require 'templates/robots/robot.html';
                        break;
                }
            }elseif($_GET['m'] == 'extend'){
                $a=$_GET['a'];
                switch ($a){
                    case 'list':
                        require 'templates/extend.html';
                        break;
                    case 'agent':
                        require 'templates/agentsetting.html';
                        break;
                }
            }elseif($_GET['m'] == 'share'){
                require 'templates/share.html';
            }elseif($_GET['m'] == 'buy'){
                require 'templates/buy.html';
            }elseif($_GET['m'] == 'flyorder' && $_GET['f'] == 'fly'){
                require 'templates/flyorder/fly.html';
            }elseif($_GET['m'] == 'flyorder' && $_GET['f'] == 'old'){
                require 'templates/flyorder/old.html';
            }elseif($_GET['m'] == 'clean'){
                require 'templates/clean.html';
            }elseif($_GET['m'] == 'addterm'){
                require 'templates/error.html';
            }elseif($_GET['m'] == 'kaijiang'){
                require 'templates/kaijiang.html';
            }elseif($_GET['m'] == 'dolog'){
                require 'templates/dologlist.html';
            }
        ?>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->
                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <h4 class="control-sidebar-subheading">Custom Template Design<span class="pull-right-container"><span class="label label-danger pull-right">70%</span></span></h4>
                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->
            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>
                    <div class="form-group">
                        <label class="control-sidebar-subheading">Report panel usage<input type="checkbox" class="pull-right" checked></label>
                        <p>Some information about this general settings option</p>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placedimmediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
<script>
    parent.document.title = document.title;
    function logout(){
        $.get('Application/ajax_index.php',{t: 'logout'},function(data){
            if(data.success){
                alert('退出系统成功！');
                window.location.reload();
            }
        },'json');
    }
</script>
    <script>
        var isNewsf = 0;
        var isNewxf = 0;
        var isNewpay = 0;
        var isNewmsg = 0;
        var isFinance = 0 ;
        var audio = document.getElementById("mp3");
        function updateNew() {
            if (isNewsf == '0') {
                $('.sf').addClass('fade');
                $('.sf').text(isNewsf);
            } else {
                $('.sf').removeClass('fade');
                $('.sf').text(isNewsf);
            }

            if (isNewxf == '0') {
                $('.xf').addClass('fade');
                $('.xf').text(isNewxf);
            } else {
                $('.xf').removeClass('fade');
                $('.xf').text(isNewxf);
            }

            if (isNewpay == '0') {
                $('.pay').addClass('fade');
                $('.pay').text(isNewpay);
            } else {
                $('.pay').removeClass('fade');
                $('.pay').text(isNewpay);
            }

            if (isNewmsg == '0') {
                $('.msg').addClass('fade');
                $('.msgdown').removeClass('fade');
                $('.msg').text(isNewmsg);
            } else {
                $('.msg').removeClass('fade');
                $('.msgdown').addClass('fade');
                $('.msg').text(isNewmsg);
            }

        }
        getNewMsg();
        var times = 0;
        function getNewMsg() {
            $.ajax({
                url: 'Application/ajax_getnew.php',
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    if (data.length < 0 || data == null) {
                        return;
                    } else {
                        if (isNewsf != data.newsf) {
                            isNewsf = data.newsf;
                            if (isNewsf == '0') {
                                $('.sf').addClass('fade');
                                $('.sf').text(data.newsf);
                                return;
                            }
                            $('.sf').removeClass('fade');
                            $('.sf').text(data.newsf);
                            if(times){
                                audio.play();
                                addData(1, data.sfdata);
                            }
                        }
                        if (isNewxf != data.newxf) {
                            isNewxf = data.newxf;
                            if (isNewxf == '0') {
                                $('.xf').addClass('fade');
                                $('.xf').text(data.newxf);
                                return;
                            }
                            $('.xf').removeClass('fade');
                            $('.xf').text(data.newxf);
                            if(times){
                                audio.play();
                                addData(2, data.xfdata);
                            }
                        }
                        times++;
                        // if (isNewpay != data.newpay) {
                        //     isNewpay = data.newpay;
                        //     if (isNewpay == '0') {
                        //         $('.pay').addClass('fade');
                        //         $('.pay').text(data.newpay);
                        //         return;
                        //     }
                        //     $('.pay').removeClass('fade');
                        //     $('.pay').text(data.newpay);
                        //     audio.play();
                        // }
                        // if (isNewmsg != data.newmsg) {
                        //     isNewmsg = data.newmsg;
                        //     if (isNewmsg == '0') {
                        //         $('.msg').addClass('fade');
                        //         $('.msgdown').removeClass('fade');
                        //         $('.msg').text(data.newmsg);
                        //         return;
                        //     }
                        //     $('.msg').removeClass('fade');
                        //     $('.msgdown').addClass('fade');
                        //     $('.msg').text(data.newmsg);
                        //     audio.play();
                        // }
                        // if (isFinance != data.newfinance) {
                        //     isFinance = data.newfinance;
                        //     if (isFinance == '0') {
                        //         $('.finance-msg').addClass('fade');
                        //         //$('.msgdown').removeClass('fade');
                        //         $('.finance-msg').text(data.newfinance);
                        //         return;
                        //     }
                        //     $('.finance-msg').removeClass('fade');
                        //     //$('.msgdown').addClass('fade');
                        //     $('.finance-msg').text(data.newfinance);
                        //     audio.play();
                        // }
                    }
                },
                error: function () {

                }
            });

            setTimeout(function () {
                getNewMsg()
            }, 5000);
        }

        function addData(type, data) {
            switch (type) {
                case 1:
                    var str = "";
                    for(var i=0;i<data.length;i++){
                        str += '<a href="zb9n8rUvp0.php?m=finance&r==up"><div class="pull-left"><img src="' + data[i].headimg + '" class="img-circle" alt="User Image"></div><h4>' + data[i].nickname + '<small><i class="fa fa-clock-o"></i> ' + data[i].time + '</small></h4><p>收到一笔提交上分' + data[i].money + '元的订单</p></a>';
                        showMsgNotification('收到一笔上分请求', data[i].nickname + "请求上分" + data[i].money + "元", data[i].headimg);
                    }
                    $('#sfdata').html(str);

                    $('#callout').addClass('callout-success');
                    $('#callout #title').text('收到一笔上分请求');
                    $('#callout #cont').text('来自'+data[0].nickname+'的上分消息');
                    $('#callout').fadeIn(1500);
                    setTimeout(function() {
                        $('#callout').removeClass('callout-success').hide();
                    }, 3000);
                    break;
                case 2:
                    var str = "";
                    for(var i=0;i<data.length;i++){
                        str += '<a href="zb9n8rUvp0.php?m=finance&r=down"><div class="pull-left"><img src="' + data[i].headimg + '" class="img-circle" alt="User Image"></div><h4>' + data[i].nickname + '<small><i class="fa fa-clock-o"></i> ' + data[i].time + '</small></h4><p>收到一笔提交下分' + data[i].money + '元的订单</p></a>';
                        showMsgNotification('收到一笔下分请求', data[i].nickname + "请求下分" + data[i].money + "元", data[i].headimg);
                    }
                    $('#xfdata').html(str);

                    $('#callout').addClass('callout-info');
                    $('#callout #title').text('收到一笔下分请求');
                    $('#callout #cont').text('来自'+data[0].nickname+'的下分消息');
                    $('#callout').fadeIn(1500);
                    setTimeout(function() {
                        $('#callout').removeClass('callout-info').hide();
                    }, 3000);
                    break;
                case 3:
                    var str = '<a href="#"><div class="pull-left"><img src="' + data.headimg + '" class="img-circle" alt="User Image"></div><h4>' + data.nickname + '<small><i class="fa fa-clock-o"></i> ' + data.time + '</small></h4><p>收到一笔提交上分' + data.money + '元的订单</p></a>';
                    $('#paydata').html(str);
                    showMsgNotification('收到一笔充值订单', data.nickname + "充值" + data.money + "元", data.headimg)
                    break;
            }
        }

        function showMsgNotification(title, msg, icon) {
            var Notification = window.Notification || window.mozNotification || window.webkitNotification;

            if (Notification && Notification.permission === "granted") {
                var instance = new Notification(
                    title, {
                        body: msg,
                        icon: icon
                    }
                );

                instance.onclick = function () {
                    window.focus();
                    instance.close;
                };
                instance.onerror = function () {
                    // Something to do
                };
                instance.onshow = function () {
                    // Something to do
                    // console.log(instance.close);
                    setTimeout(instance.close, 3000);
                };
                instance.onclose = function () {
                    // Something to do
                };
            } else if (Notification && Notification.permission !== "denied") {
                Notification.requestPermission(function (status) {
                    if (Notification.permission !== status) {
                        Notification.permission = status;
                    }
                    // If the user said okay
                    if (status === "granted") {
                        var instance = new Notification(
                            title, {
                                body: msg,
                                icon: icon
                            }
                        );

                        instance.onclick = function () {
                            // Something to do
                        };
                        instance.onerror = function () {
                            // Something to do
                        };
                        instance.onshow = function () {
                            // Something to do
                            setTimeout(instance.close, 3000);
                        };
                        instance.onclose = function () {
                            // Something to do
                        };

                    } else {
                        return false
                    }
                });
            } else {
                return false;
            }
        }
    </script>
</html>