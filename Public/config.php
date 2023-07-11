<?php
define('WEB_HOST','https://mobile.amg668.top');

session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
date_default_timezone_set("Asia/Shanghai");
header("Content-type:text/html;charset=utf-8");
$load = 5;
include_once("sql.php");
$console = "AMG娱乐";
include_once("db.config.php");
$dbconn = db_connect($db['host'], $db['user'], $db['pass'], $db['name']);
full_query("set sql_mode=(select replace(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
$gmidAli=[];
$gmidAli[1]='pk10';//澳洲10
$gmidAli[2]='xyft';//幸运飞艇
$gmidAli[3]='cqssc';//
$gmidAli[4]='xy28';
$gmidAli[5]='jnd28';
$gmidAli[6]='jsmt';
$gmidAli[7]='jssc';
$gmidAli[8]='jsssc';
$gmidAli[9]='xglhc';
function aa($val){
    print_r($val);die();
}
if(isset($_SESSION['userid']) && $_SESSION['userid']) {
    setcookie('userid',$_SESSION['userid'] , time() + 3600);
    $_COOKIE['userid'] = $_SESSION['userid'];
    update_query('fn_user' , ['statustime'=>time()],array('userid' => $_SESSION['userid']));
}
if(isset($_COOKIE['userid']) && $_COOKIE['userid'] && empty($_SESSION['userid'])){
    $row = get_query_vals('fn_user','*',"userid='{$_COOKIE['userid']}'");
    if($row){
        $_SESSION['user']=$row;
        $_SESSION['userid']=$row['userid'];
        $_SESSION['username']=$row['username'];
        $_SESSION['headimg']=$row['headimg'];
    }
}
function writeLog($msg , $file_type){
    $file = dirname(getcwd()) . '/logdata/' . $file_type . '/';
    if(!is_dir($file)){
        mkdir($file , 0775 , true);
    }
    file_put_contents($file . date('Y-m-d') . '.log' , $msg.PHP_EOL , FILE_APPEND);
}
function doLog($message , $user = ''){
    $user = $user?$user:$_SESSION['agent_user'];
    $data = [
        'adminuser' => $user,
        'adminid' => get_query_val("fn_admin", "id", array("roomid" => $_SESSION['agent_room'],'roomadmin'=>$user)),
        'url' => $_SERVER['SCRIPT_NAME'],
        'param' => json_encode($_REQUEST),
        'message' => $message,
        'ip' => $_SERVER['REMOTE_ADDR'],
        'create_time' => time()
    ];
    insert_query('fn_admin_log' , $data);
}
function checkLogin(){
    if(!isset($_SESSION['user'])) {
        $_COOKIE['userid'] = null;
        setcookie('userid',null , -time() + 3600);
        session_destroy();
        header('Location:/fn.php');
    }
    $user = get_query_vals('fn_user', 'token,is_black', array('userid' => $_SESSION['userid']));
    if(empty($user) || !isset($_SESSION['token']) || $user['token'] != $_SESSION['token'] || $user['is_black']){
        $_COOKIE['userid'] = null;
        setcookie('userid',null , -time() + 3600);
        session_destroy();
        header('Location:/fn.php');
    }
}
function getInviteCode($leng = 10){
    $leng = $leng < 6 ? 6 :$leng;
    $chars = '0123456789';
    $invite_code = '';
    for ( $i = 0; $i < $leng; $i++ )
    {
        $invite_code .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    if(get_query_val('fn_invite_code','count(id)',['invite_code'=>$invite_code])){
        $invite_code = getInviteCode();
    }
    return $invite_code;
}
function getUserId($leng = 10){
    $leng = $leng < 6 ? 6 :$leng;
    $chars = '0123456789';
    $userid = '';
    for ( $i = 0; $i < $leng; $i++ )
    {
        $userid .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    if(get_query_val('fn_user','count(id)',['id'=>$userid])){
        $userid = getInviteCode();
    }
    return $userid;
}
function getreferer(){
    $list = [
        'Xiaomi_M1808D2TT_TD-LTE/V1 Linux/4.4.78 Android/8.1 Release/10.10.2018 Browser/AppleWebKit537.36 Mobile Safari/537.36 System/Android 8.1 XiaoMi/MiuiBrowser/9.8.3',
        'Xiaomi_M1804C3CT_TD-LTE/V1 Linux/4.9.77 Android/8.1 Release/4.25.2018 Browser/AppleWebKit537.36 Mobile Safari/537.36 System/Android 8.1 XiaoMi/MiuiBrowser/9.5.9',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/71.0.3578.141 Safari/534.24 XiaoMi/MiuiBrowser/11.1.11',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/71.0.3578.141 Safari/534.24 XiaoMi/MiuiBrowser/11.0.10',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/71.0.3578.141 Safari/534.24 XiaoMi/MiuiBrowser/10.9.2',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/537.36(KHTML, like Gecko) Chrome/40.0.2214.89 Safari/537.36',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Safari/537.36 VivoBrowser/7.1.0.1 Chrome/62.0.3202.84',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Safari/537.36 VivoBrowser/7.0.1.2 Chrome/62.0.3202.84',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Safari/537.36 VivoBrowser/6.7.11.3 Chrome/62.0.3202.84',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Safari/537.36 VivoBrowser/6.6.3.0 Chrome/62.0.3202.84',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.108 Safari/537.36 UCBrowser/12.7.9.1059',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.108 Safari/537.36 UCBrowser/12.7.5.1055',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.108 Safari/537.36 UCBrowser/12.7.4.1054',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.108 Safari/537.36 UCBrowser/12.7.2.1052',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.108 Safari/537.36 UCBrowser/12.7.0.1050',
        'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.108 Safari/537.36 UCBrowser/12.6.2.1042',
        'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.108 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3741.400 QQBrowser/10.5.3863.400',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3704.400 QQBrowser/10.4.3587.400',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3947.100 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3314.0 Safari/537.36 SE 2.X MetaSr 1.0',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.26 Safari/537.36 Core/1.63.5977.400 LBBROWSER/10.1.3752.400',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.97 Safari/537.36 huaweioem',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36 QBCore/4.0.1278.400 QQBrowser/9.0.2524.400 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2875.116 Safari/537.36 NetType/WIFI MicroMessenger/7.0.5 WindowsWechat',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36 QBCore/4.0.1258.400 QQBrowser/9.0.2524.400 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2875.116 Safari',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36 QBCore/4.0.1219.400 QQBrowser/9.0.2524.400 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat',
        'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36 QBCore/4.0.1219.400 QQBrowser/9.0.2524.400 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/dd',
    ];
    $randIndex = array_rand($list);
    return $list[$randIndex];
}
function curlFn($url){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
    curl_setopt($ch, CURLOPT_USERAGENT, getreferer());// 模拟用户使用的浏览器

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $res = curl_exec($ch);
    $curl_errno = curl_errno($ch);
    if ($curl_errno) {
        curl_close($ch);
        return false;
    }
    curl_close($ch);
    return $res;
}
function getGameTxtName($gameid=''){
    $gmidName=[];
    $gmidName[1]='澳洲幸运10';
    $gmidName[2]='幸运飞艇';
    $gmidName[3]='极速飞艇';
    $gmidName[4]='极速赛车';
    $gmidName[5]='加拿大28';
    $gmidName[6]='极速摩托/飞艇';
    /*$gmidName[7]='jssc';
    $gmidName[8]='jsssc';*/
    if(empty($gameid)) return $gmidName;
    else return (isset($gmidName[$gameid])?$gmidName[$gameid]:'----');
}
function getGameList(){
    $gmidName=[];
    $gmidName[1]='澳洲幸运10';
    $gmidName[2]='幸运飞艇';
    $gmidName[3]='极速飞艇';
    $gmidName[4]='极速赛车';
    $gmidName[5]='加拿大28';
    $gmidName[6]='极速摩托/飞艇';
    $gmidName[9]='香港六合彩';
    return $gmidName;
}
function getGameTxtNameByCode($code){
    $gimeid=getGameIdByCode($code);
    return getGameTxtName($gimeid);
}

function getGameIdByCode($code=''){
    global $gmidAli;
    $typeNum=$gmidAli;
    $typeNum=array_flip($typeNum);
    if(empty($code)) return $typeNum;
    else return (isset($typeNum[$code])?$typeNum[$code]:'----');
}
function getGameCodeById($id=''){
    global $gmidAli;
    return (isset($gmidAli[$id])?$gmidAli[$id]:'');
}

//$oauth = 'http://jump.0933888.net/?t=' . $_SERVER['HTTP_HOST'];
//$oauth = 'http://' . $_SERVER['HTTP_HOST'].'/?room='.$_SESSION['roomid'];
$oauth = "http://qwe.wqzyh.top/get-weixin-code.html?appid=".$wx["ID"]."&redirect_uri=".urlencode("http://".$_SERVER["HTTP_HOST"]."/qr.php?agent=".$_GET['agent']."&g=".$_GET['g']."&room=".$_GET['room'])."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
function room_isOK($roomid){
    $status = get_query_val('fn_room', 'id', array('roomid' => $roomid));
    if($status == ""){
        return false;
    }
    return true;
}
function wx_gettoken($Appid, $Appkey, $code){
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $Appid . "&secret=" . $Appkey . "&code=" . $code . "&grant_type=authorization_code";
    $html = file_get_contents($url);
    $json = json_decode($html, 1);
    $access_token = $json['access_token'];
    $openid = $json['openid'];
    return array("token" => $access_token, 'openid' => $openid);
}
//2017-10-21 获取全局access token
// function wx_getaccesstoken($Appid, $Appkey){
    // $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=". $Appid ."&secret=". $Appkey;
    // $html = file_get_contents($url);
    // $json = json_decode($html, 1);
    // $access_token = $json['access_token'];
    // $expires = $json['expires_in'];
    // return array("access_token" => $access_token, 'expires_in' => $expires);
// }
//用全局access token 和 openid 获取用户详情
// function wx_getinfo2($token, $openid){
    // $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=". $token ."&openid=". $openid;
    // $html = file_get_contents($url);
    // $json = json_decode($html, 1);
    // $nickname = $json['nickname'];
    // $headhtml = $json['headimgurl'];
    // return array("nickname" => $nickname, 'headimg' => $headhtml);
// }

function wx_getinfo($token, $openid){
    $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $token . "&openid=" . $openid."&lang=zh_CN";
    $html = file_get_contents($url);
    $json = json_decode($html, 1);
    $nickname = $json['nickname'];
    $headhtml = $json['headimgurl'];
    return array("nickname" => $nickname, 'headimg' => $headhtml);
}
function U_create($userid, $username, $headimg, $agent = "null"){
    if($agent == ""){
        $agent = 'null';
    }
    //insert_query("fn_user", array("userid" => $userid, 'username' => $username, 'headimg' => $_SESSION['headimg'], 'money' => '0', 'roomid' => $_SESSION['roomid'], 'statustime' => time(), 'agent' => $agent, 'isagent' => 'false', 'jia' => 'false'));
	insert_query("fn_user", array("userid" => $userid, 'username' => $username, 'headimg' => $headimg, 'money' => '0', 'roomid' => $_SESSION['roomid'], 'statustime' => time(), 'agent' => $agent, 'isagent' => 'false', 'jia' => 'false'));
    return true;
}
function U_isOK($userid, $headimg){
    $status = get_query_val('fn_user', 'id', array('userid' => $userid, 'roomid' => $_SESSION['roomid']));
    if($status == ""){
        return false;
    }
    update_query("fn_user", array("headimg" => $headimg), array('id' => $status));
    return true;
}
function ajaxMsg($msg,$status=1){
    if(is_string($msg)) $data=['msg'=>$msg,'status'=>$status];
    else{
        $data=['status'=>$status];
        $data=array_merge($data,$msg);
    }

    echo json_encode($data);
    exit();
}

function senddatas($roomid, $type, $game)
{

    $term = get_query_val('fn_open', 'next_term', "`type` = {$type} order by `id` desc limit 1");
    select_query('fn_order', '*', "`roomid` = '{$roomid}' and `type` = {$type} and `term`='{$term}'");

    $data = [];
    $array = [];
    while ($con = db_fetch_array()) {
        if ($con['status'] != '已撤单') {
            if (!isset($data[$con['username']]['sum'])) {
                $data[$con['username']]['sum'] = 0;
            }
            $data[$con['username']]['sum'] += $con['money'];
            $con['content'] = ((is_numeric($con['content']) && $con['content']==0)?10:$con['content']);
            $data[$con['username']]['data'][$con['mingci']][] = $con;

            //$array[$con['mingci']][] = $con;
        }
    }
    $txt = "竞猜列表核对:<br>{$term}期有效投注<br>===================<br>";
    foreach ($data as $key => $v) {
        $txt .= "<br>[{$key}]汇总:{$v['sum']}<br>";
        $d = $v['data'];
        $txt .= getcontents(isset($d['1']) ? $d['1'] : [], '冠军') . "
                " . getcontents(isset($d['和']) ? $d['和'] : [], '冠亚') . "
                " . getcontents(isset($d['2']) ? $d['2'] : [], '亚军') . "
                " . getcontents(isset($d['3']) ? $d['3'] : [], '第三名') . "
                " . getcontents(isset($d['4']) ? $d['4'] : [], '第四名') . "
                " . getcontents(isset($d['5']) ? $d['5'] : [], '第五名') . "
                " . getcontents(isset($d['6']) ? $d['6'] : [], '第六名') . "
                " . getcontents(isset($d['7']) ? $d['7'] : [], '第七名') . "
                " . getcontents(isset($d['8']) ? $d['8'] : [], '第八名') . "
                " . getcontents(isset($d['9']) ? $d['9'] : [], '第九名') . "
                " . getcontents(isset($d['0']) ? $d['0'] : [], '第十名');
    }
    管理员喊话($txt, $roomid, $game);
}

function getcontents($data, $name)
{
    $array = [];
    foreach ($data as $val) {
        if (!isset($array[$val['content']])) {
            $array[$val['content']] = 0;
        }
        $array[$val['content']] += $val['money'];
    }
    $text = '';

    foreach ($array as $key => $v) {
        $text .= $key . '/' . $v . ' ';
    }
    if (!empty($text)) {
        $text = $name . "[{$text}]<br>";
    }

    return $text;
}
function getOpenInfo($gameName , $type = 0){
    global $gmidAli;
    $typeNum=$gmidAli;
    $typeNum=array_flip($typeNum);
    $_COOKIE['game']=$gameName;

    $roomid=$_SESSION['roomid'];
    $GameType=$typeNum[$gameName];
    $field = 'term,next_term,next_time,code,iskaijiang';
    $BetTerm = get_query_vals('fn_open', $field, "type = {$GameType} order by `next_time` desc limit 1");
    //$time = (int)get_query_val('fn_lottery'.$GameType, 'fengtime', array('roomid' => $roomid));
    $thisTerm= explode(',',$BetTerm['code']);
    //var_dump($thisTerm);exit;
    if($GameType == 5){
        $h=intval($thisTerm[0])+intval($thisTerm[1])+intval($thisTerm[2]);
    }else{
        $h=intval($thisTerm[0])+intval($thisTerm[1]);
    }
    $rowsend=[];
    $rowsend['current_sn']=$BetTerm['term'];
    $rowsend['next_sn']=$BetTerm['next_term'];
    $rowsend['open_num']=$BetTerm['code'];
    if($BetTerm['iskaijiang'] == 0){

        $last_open = get_query_vals('fn_open', $field, "type = {$GameType} and next_term = '{$BetTerm['term']}' order by `next_time` desc limit 1");
        $thisTerm= explode(',',$last_open['code']);
        //var_dump($thisTerm);exit;
        if($GameType == 5){
            $h=intval($thisTerm[0])+intval($thisTerm[1])+intval($thisTerm[2]);
        }else{
            $h=intval($thisTerm[0])+intval($thisTerm[1]);
        }
        $rowsend['open_num']=$last_open['code'];
        $rowsend['letf_time'] = '正在开奖';
        $rowsend['current_sn']=$last_open['term'];
        $rowsend['next_sn']=$last_open['next_term'];
    }else{
        $game_info = get_query_vals('fn_lottery' . $GameType, 'gameopen,fengtime', array('roomid' => $roomid));
        if($game_info['gameopen'] == 'false'){
            $rowsend['letf_time'] = -100;
        }else{
            $rowsend['letf_time']=strtotime($BetTerm['next_time']) - GetTtime() - $game_info['fengtime'];
        }
        $key = md5($roomid . $GameType . $gameName . $rowsend['current_sn']);
        if($rowsend['letf_time'] < 0 && $type == 0){
            if(!isset($_SESSION[$key]) || empty($_SESSION[$key])){
                $_SESSION[$key] = true;
                senddatas($roomid, $GameType, $gameName);
                管理员喊话("第 " . $BetTerm['next_term'] . " 期已封盘，请等待下期!", $BetTerm['roomid'], $gmidAli[$BetTerm['type']]);
            }
        }
    }

    $rowsend['fp_time']=1;
    $rowsend['gyh']=$h.' '.($h>10?'大':'小').' '.($h%2==0?'双':'单');
    return $rowsend;
}

function GetTtime(){
//    $data=file_get_contents('http://127.0.0.1:1323');
//    if (!empty($data)){
//        return strtotime($data);
//    }
    return time();
}

function getPeilv($userid , $roomid , $gametypeid , $mingci , $content){
    $gametype = $gametypeid;
    $table = 'fn_lottery' . $gametypeid;
    if(getGameCodeById($gametypeid) == 'jnd28') {
        switch ($content){
            case '大':
            case '小':
            case '单':
            case '双':
                $field = 'dxds';
                break;
            case '极大':
                $field = 'jida';
                break;
            case '极小':
                $field = 'jixiao';
                break;
            case '大单':
                $field = 'dadan';
                break;
            case '小单':
                $field = 'xiaodan';
                break;
            case '大双':
                $field = 'dashuang';
                break;
            case '小双':
                $field = 'xiaoshuang';
                break;
            case '豹子':
                $field = 'baozi';
                break;
            case '对子':
                $field = 'duizi';
                break;
            case '顺子':
                $field = 'shunzi';
                break;
            case 0:
            case 27:
                $field = '0027';
                break;
            case 1:
            case 26:
                $field = '0126';
                break;
            case 2:
            case 25:
                $field = '0225';
                break;
            case 3:
            case 24:
                $field = '0324';
                break;
            case 4:
            case 23:
                $field = '0423';
                break;
            case 5:
            case 22:
                $field = '0522';
                break;
            case 6:
            case 21:
                $field = '0621';
                break;
            case 7:
            case 20:
                $field = '0720';
                break;
            case 8:
            case 9:
            case 18:
            case 19:
                $field = '891819';
                break;
            case 10:
            case 11:
            case 16:
            case 17:
                $field = '10111617';
                break;
            case 12:
            case 15:
                $field = '1215';
                break;
            case 13:
            case 14:
                $field = '1314';
                break;
        }

    }else{
        if($mingci == '和'){
            switch ($content){
                case '大':
                    $field = 'heda';
                    break;
                case '小':
                    $field = 'hexiao';
                    break;
                case '单':
                    $field = 'hedan';
                    break;
                case '双':
                    $field = 'heshuang';
                    break;
                case 3:
                case 4:
                case 18:
                case 19:
                    $field = 'he341819';
                    break;
                case 5:
                case 6:
                case 16:
                case 17:
                    $field = 'he561617';
                    break;
                case 7:
                case 8:
                case 14:
                case 15:
                    $field = 'he781415';
                    break;
                case 9:
                case 10:
                case 12:
                case 13:
                    $field = 'he9101213';
                    break;
                case 11:
                    $field = 'he11';
                    break;
            }
        }else{
            switch ($content){
                case '大':
                    $field = 'da';
                    break;
                case '小':
                    $field = 'xiao';
                    break;
                case '单':
                    $field = 'dan';
                    break;
                case '双':
                    $field = 'shuang';
                    break;
                case '大单':
                    $field = 'dadan';
                    break;
                case '大双':
                    $field = 'dashuang';
                    break;
                case '小双':
                    $field = 'xiaoshuang';
                    break;
                case '小单':
                    $field = 'xiaodan';
                    break;
                case '龙':
                    $field = '`long`';
                    break;
                case '虎':
                    $field = 'hu';
                    break;
                default:
                    $field = 'tema';
            }
        }
    }
    return getNewPeilv($userid , $roomid , $gametype , get_query_val($table, $field , "`roomid` = '$roomid'") , $field);
}
/**
 * @param $userid 用户编号
 * @param $roomid 房间编号
 * @param $gametypeid 游戏类型编号
 * @param $peilv 游戏玩法赔率
 * @param $field 游戏玩法字段
 * @return false|float|int|mixed
 */
function getNewPeilv($userid , $roomid , $gametypeid , $peilv , $field){
    $fandian = userFanDian($userid , $roomid , $gametypeid);
    if($fandian !== false){
        $peilv = getManagePeilv($gametypeid , $roomid , $peilv , $fandian , $field);
    }
    return $peilv;
}
function userFanDian($userid , $roomid , $gametypeid){
    $user_info = get_query_vals('fn_user' , '*' , ['userid'=>$userid,'roomid'=>$roomid]);
    $table = 'fn_lottery' . $gametypeid;
    $game_config = get_query_vals($table , 'peilv_step,fandian' , ['roomid'=>$roomid]);
    if($user_info['agent'] == null || empty($user_info['agent'])){
        //一级代理
        return $game_config['fandian'];
    }else{
        $fandian = json_decode($user_info['fandian'] , true);
        if($fandian){
            return $fandian[$gametypeid];
        }
    }
    return $game_config['fandian'];
}
/**
 * @param $roomid 房间编号
 * @param $gametypeid 游戏类型编号
 * @param $peilv 游戏玩法赔率
 * @param $fandian 返点
 * @return false|float|int|mixed
 */
function getManagePeilv($gametypeid , $roomid , $peilv , $fandian , $field){
    $table = 'fn_lottery' . $gametypeid;
    $game_config = get_query_vals($table , 'peilv_step,fandian' , ['roomid'=>$roomid]);
    $levels = ($game_config['fandian'] - $fandian) / 0.01;
    $peilv_step = 0.0002;
    switch ($field){
        case 'da':
        case 'xiao':
        case 'dan':
        case 'shuang':
        case 'long':
        case 'hu':
        case 'heda':
        case 'hexiao':
        case 'heshuang':
        case 'hedang':
        case 'dadan':
        case 'xiaodan':
        case 'dashuang':
        case 'xiaoshuang':
            $peilv_step = 0.0001 * 2;
            break;
        case 'tema':
            $peilv_step = 0.001;
            break;
        case 'he11':
            $peilv_step = 0.0009;
            break;
        case 'he341819':
            $peilv_step = 0.0045;
            break;
        case 'he561617':
            $peilv_step = 0.0023;
            break;
        case 'he781415':
            $peilv_step = 0.0015;
            break;
        case 'he9101213':
            $peilv_step = 0.0011;
            break;
    }
//    $peilv_step = round($peilv / 10000 + $peilv / 1000000 , 7);
    $new_peilv = $peilv - $peilv_step * $levels;
    return $new_peilv > 0 ? round(($new_peilv * 10000) / 10000 , 4) : $peilv;
}
/**
 * @param $userid 中奖用户ID
 * @param $user_id 执行用户ID
 * @param $roomid 房间编号
 * @param $gametypeid 游戏类型编号
 * @param $money 赔率差额
 * @return false|float|int|mixed
 */
function peilvfandian($userid , $user_id , $roomid , $gametypeid , $money){
//    if($money <= 0){
//        return ;
//    }
//    $table = 'fn_lottery' . $gametypeid;
//    $game_config = get_query_vals($table , 'peilv_step,fandian' , ['roomid'=>$roomid]);
//    //获取中奖用户的返点
//    $user_fandian_zj = userFanDian($userid , $roomid , $gametypeid);
//    //获取执行用户用户的返点
//    $user_fandian = userFanDian($user_id , $roomid , $gametypeid);
//    //获取当前用户的推荐者，没有的话，不进行赔率差的返点
//    $agent = get_query_vals('fn_user' , 'agent,jia' , ['userid'=>$user_id,'roomid'=>$roomid]);
//    if($agent['agent']){
//        $agent_fandian = userFanDian($agent['agent'] , $roomid , $gametypeid);
//        $fandian_chae = $agent_fandian - $user_fandian;
//        $fan_money = floor($money  * $fandian_chae / ($game_config['fandian'] - $user_fandian_zj) * 10000) / 10000;
//        $is_jia = get_query_val('fn_user' , 'jia' , ['userid'=>$agent['agent'],'roomid'=>$roomid]);
//        if($fan_money && $is_jia == 'false'){
//            update_query('fn_user', array('money' => '+=' . $fan_money), array('userid' => $agent['agent'], 'roomid' => $roomid));
//            insert_query("fn_marklog", array("userid" => $agent['agent'], 'game'=>getGameCodeById($gametypeid)  ,'type' => '返点', 'content' =>'赔率差返点', 'money' => $fan_money, 'roomid' => $roomid, 'addtime' => 'now()'));
//        }
//        peilvfandian($user_id , $agent['agent'] , $roomid , $gametypeid , $money);
//    }
}
/**
 * @param $userid 执行用户
 * @param $fromuser 投注用户ID
 * @param $roomid 房间编号
 * @param $gametypeid 游戏类型编号
 * @param $money 投注金额
 * @param $child_fandian 已被分出的点位
 * @return false|float|int|mixed
 */
function touzhufandian($userid , $money , $roomid , $gametypeid , $fromuser , $type = '' , &$fandian_list = []){
    if($money <= 0){
        return ;
    }
    $table = 'fn_lottery' . $gametypeid;
    $game_config = get_query_vals($table , 'peilv_step,fandian' , ['roomid'=>$roomid]);
    //获取当前用户的推荐者，没有的话，不进行投注返点
    $agent = get_query_vals('fn_user' , 'agent,jia' , ['userid'=>$userid,'roomid'=>$roomid]);
    if($agent['agent'] != null && $agent['agent'] != ''){
        $user_fandian = userFanDian($userid , $roomid , $gametypeid);
	    $agent_fandian = userFanDian($agent['agent'] , $roomid , $gametypeid);
        $fandian_money = round($money * ($agent_fandian - $user_fandian) / 100 , 4);
        $is_jia = get_query_val('fn_user' , 'jia' , ['userid'=>$agent['agent'],'roomid'=>$roomid]);
        if($fandian_money && $is_jia == 'false'){
            if(isset($fandian_list[$agent['agent']][$roomid])){
                $fandian_list[$agent['agent']][$roomid] += $fandian_money;
            }else{
                $fandian_list[$agent['agent']][$roomid] = $fandian_money;
            }
            //update_query('fn_user', array('money' => '+=' . $fandian_money), array('userid' => $agent['agent'], 'roomid' => $roomid));
            insert_query("fn_marklog", array("userid" => $agent['agent'], 'game'=>getGameCodeById($gametypeid)  ,'type' => '返点', 'content' =>"用户【{$fromuser}】投注{$money}元返点" . round($agent_fandian - $user_fandian , 2) . "%", 'money' => $fandian_money, 'roomid' => $roomid, 'addtime' => 'now()'));
        }
        touzhufandian($agent['agent'] , $money , $roomid , $gametypeid , $fromuser , $type , $fandian_list);
    }
}

/**
 * @param $userid 代理用户id
 * @param int $type 获取类型1表示本级，2表示团队
 */
function getAgentUserList($userid , $type = 1){
    select_query("fn_user", 'userid', "roomid = {$_SESSION['roomid']} and agent = '{$userid}'" ) ;
    $cons = [];
    while($con = db_fetch_array()){
        $cons[] = $con['userid'];
    }
    if($type == 2){
        foreach ($cons as $val){
            $child_list = getAgentUserList($val , $type);
            if($child_list){
                $cons = array_merge($cons , $child_list);
            }
        }
    }
    return $cons;
}


/** 20230711 六合彩投注返点
 * @param $userid 执行用户
 * @param $fromuser 投注用户ID
 * @param $roomid 房间编号
 * @param $gametypeid 游戏类型编号
 * @param $money 投注金额
 * @param $peilv_step 游戏赔率返点基数
 * @param $child_fandian 已被分出的点位
 * @return false|float|int|mixed
 */
function lhctouzhufandian($userid , $money , $roomid , $gametypeid , $fromuser , $peilv_step = ''){
    if($money <= 0){
        return ;
    }
    $table = 'fn_lottery' . $gametypeid;
    $game_config = get_query_vals($table , 'peilv_step,fandian' , ['roomid'=>$roomid]);
    //获取当前用户的推荐者，没有的话，不进行投注返点
    $agent = get_query_vals('fn_user' , 'agent,jia' , ['userid'=>$userid,'roomid'=>$roomid]);
    if($agent['agent'] != null && $agent['agent'] != ''){
        $user_fandian = userFanDian($userid , $roomid , $gametypeid);
        $agent_fandian = userFanDian($agent['agent'] , $roomid , $gametypeid);

        $fandian_money = round($money * ((($agent_fandian - $user_fandian) / 0.01) * $peilv_step) , 4);//返点金额

        $is_jia = get_query_val('fn_user' , 'jia' , ['userid'=>$agent['agent'],'roomid'=>$roomid]);
        if($fandian_money && $is_jia == 'false'){
            if(isset($fandian_list[$agent['agent']][$roomid])){
                $fandian_list[$agent['agent']][$roomid] += $fandian_money;
            }else{
                $fandian_list[$agent['agent']][$roomid] = $fandian_money;
            }
            update_query('fn_user', array('money' => '+=' . $fandian_money), array('userid' => $agent['agent'], 'roomid' => $roomid));//添加金额
            insert_query("fn_marklog", array("userid" => $agent['agent'], 'game'=>getGameCodeById($gametypeid)  ,'type' => '返点', 'content' =>"用户【{$fromuser}】投注{$money}元返点" . round($agent_fandian - $user_fandian , 2) . "%", 'money' => $fandian_money, 'roomid' => $roomid, 'addtime' => 'now()'));
            //lhctouzhufandian($agent['agent'] , $money , $roomid , $gametypeid , $fromuser , $peilv_step , $fandian_list);
            lhctouzhufandian($agent['agent'] , $money , $roomid , $gametypeid , $fromuser , $peilv_step);
        }

    }
}
