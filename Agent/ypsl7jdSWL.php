<?php
if(stripos($_SERVER['HTTP_USER_AGENT'], "micromessenger")==true){
require "../Templates/error.php";
exit;
}
include(dirname(dirname((preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $console;?>L版系统 | 登录</title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="dist/css/font-awesome.min.css">
<link rel="stylesheet" href="dist/css/ionicons.min.css">
<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="plugins/iCheck/square/blue.css">
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="hold-transition login-page" style="background-size:100%;background:url(zye.jpg); background-size:cover;">
<br/>
<center>
<div align="left" id="error" class="alert alert-warning alert-dismissible" style="width:80%;display:none">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<h4><i class="icon fa fa-warning"></i> 警告</h4>
<span id="errorcontent"></span>
</div>
</center>
<div class="login-box">
<div class="login-logo">
<a href="#" style="color:#fff"><b><?php echo $console;?></b></a>
</div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">登录开始使用您的娱乐系统</p>
    <form action="" method="post">
      <div class="form-group has-feedback">
        <input id="user" name="user" type="text" class="form-control" placeholder="账号" value="<?php if($_COOKIE['agentuser'] != "")echo $_COOKIE['agentuser'];
?>">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input id="pass" name="pass" type="password" class="form-control" placeholder="密码" value="<?php if($_COOKIE['agentpass'] != "")echo $_COOKIE['agentpass'];
?>">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input id="room" name="room" type="number" class="form-control" placeholder="房间号" value="<?php if($_COOKIE['agentroom'] != "")echo $_COOKIE['agentroom'];
?>">
        <span class="glyphicon glyphicon-home form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck" data-toggle="tooltip" data-original-title="我们将保存您的Cookies10天">
            <label>
              <input name="check" type="checkbox"> 记住我的登录
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" id="loginbtn" class="btn btn-primary btn-block btn-flat">登录</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="plugins/iCheck/icheck.min.js"></script>
<script src="dist/js/app.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
  $('#loginbtn').click(function(){
      var user = $('#user').val();
      var pass = $('#pass').val();
      var room = $('#room').val();

      if(user == "" || pass == "" || room == ""){
          alert('请填写完登录表单');
          return;
      }else{
          $('#loginbtn').submit();
      }
  })
  function displayerror() {
      $('#error').fadeOut(1500);
  }
</script>
</body>
<?php if ($_SERVER["REQUEST_METHOD"] == 'POST'){
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $room = $_POST['room'];
    $check = $_POST['check'];
    if(get_query_val("fn_room", "roomid", array("roomid" => $room)) == ""){
        ?>
        <script>
            $('#error').fadeIn(1500)
            $('#errorcontent').html('您输入的房间ID不存在');
            setTimeout(function() {
                displayerror();
            }, 4000);
        </script>
<?php return;
    }elseif(!get_query_val("fn_admin", "roomadmin", array("roomid" => $room,'roomadmin'=>$username,'roompass'=>md5($password)))){

        ?>
        <script>
            $('#error').fadeIn(1500)
            $('#errorcontent').html('您的账号或者密码不正确');
            setTimeout(function() {
                displayerror();
            }, 2000);
        </script>

<?php }else{
        //更新token
        $token = md5($username.time().rand(100,999));
        doLog('管理员登录' , $username);
        $_SESSION['agent_user'] = $username;
        $_SESSION['agent_pass'] = md5($password);
        $_SESSION['agent_room'] = $room;
        $_SESSION['roomid'] = $room;
        $_SESSION['token'] = $token;
        update_query('fn_admin' , ['token'=>$token] , ['roomadmin'=>$username,'roomid'=>$room]);
        if($check == 'on'){
            setcookie('agentuser', $username, time() + 864000);
            setcookie("agentpass", $password, time() + 864000);
            setcookie("agentroom", $room, time() + 864000);
        }
        header("Location:zb9n8rUvp0.html");
        return;
    }
}
?>
</html>