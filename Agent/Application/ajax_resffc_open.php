<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$arr = array();
$action = $_POST['action'];
switch ($action){
	case 'add' :
		add();
		break;
}
function add(){
	global $mydb;
    if($_POST['cat_id']==''){
        $arr['success'] = false;
        $arr['msg'] = '参数错误：游戏不能为空(-9999)';
        echo json_encode($arr);exit();
    }
	if($_POST['term']==''){
	    $arr['success'] = false;
	    $arr['msg'] = '参数错误：期号不能为空(-9999)';
	    echo json_encode($arr);exit();
	}
	if($_POST['res_code']==''){
	    $arr['success'] = false;
	    $arr['msg'] = '参数错误：预置号码不能为空(-9999)';
	    echo json_encode($arr);exit();
	}
	$open = $mydb->table('fn_open')->where(array('type'=>$_POST['cat_id'],'term'=>$_POST['term']))->find();
	if($open){
	    $arr['success'] = false;
	    $term = $_POST['term'];
	    $arr['msg'] = '期号：'.$term.'已经开奖了(-9999)';
	    echo json_encode($arr);exit();
	}
	$old = $_POST['term']-1;
	$resopen = $mydb->table('fn_open')->where(array('type'=>$_POST['cat_id'],'term'=>$old))->find();//上一期
	$rtime = strtotime($resopen['next_time'])-time();
	if($rtime<=10){
	    $arr['success'] = false;
	    $term = $_POST['term'];
	    $arr['msg'] = '期号：'.$term.'即将开奖或已开奖，请在开奖前10秒以上预置开奖号码';
	    echo json_encode($arr);exit();
	}
	//计算间隔多久开一期
    $jtime = strtotime($resopen['next_time'])-strtotime($resopen['time']);

	$data['type'] = $_POST['cat_id'];
	$data['term'] = $_POST['term'];
	$data['code'] = $_POST['res_code'];
	
	$data['time'] = date('Y-m-d H:i:s', strtotime($resopen['next_time']));
	$data['next_term'] = $_POST['term']+1;
	$data['next_time'] = date('Y-m-d H:i:s', strtotime($resopen['next_time']) + $jtime);
	
	$data['change'] = 1;
	$data['create_time'] = time();

	$r = $mydb->table('fn_open')->data($data)->insert();
	if($r){
		$arr['success'] = true;
	}else{
		$arr['success'] = false;
    	$arr['msg'] = '参数错误..Err(-9999)';
	}
	echo json_encode($arr);
}
?>