<?php

include_once("../Public/config.php");
//增加预测概率
function kj_number(){
    $number_arr = [
        '01','02','03','04','05','06','07','08','09','10',
        '11','12','13','14','15','16','17','18','19','20',
        '21','22','23','24','25','26','27','28','29','30',
        '31','32','33','34','35','36','37','38','39','40',
        '41','42','43','44','45','46','47','48','49'
    ];

    return $number_arr;
}

//增加预测概率
function get_yuce_number($kj_number=[]){
    $number_arr = kj_number();
    //获取差集
    $cha_arr = array_diff($number_arr,$kj_number);
    //差集中获取7个预测号码作为测试号码
    shuffle($cha_arr);
    //获取前面7个和开奖号码组成预测号码
    $yuce_ma = array_merge($kj_number,array_slice($cha_arr,0,7));
    shuffle($yuce_ma);
    //后面20个码和开奖号码组成杀码
    $sha_ma = array_merge($kj_number,array_slice($cha_arr,7,20));
    shuffle($sha_ma);
    //组成新的号码
    $new_yuce_number = array_merge($yuce_ma,$sha_ma);

    return $new_yuce_number;
}

function get_kj_number(){
    $number_arr = kj_number();
    //
    shuffle($number_arr);
    //$new_number_arr = array_slice($number_arr,0,7);
    $data['kj_number'] = array_slice($number_arr,0,6);
    $data['kj_number_te'] = array_slice($number_arr,6,1)[0];
    return $data;
}


function bo($name='red'){
    $number_arr = [
        'red'=> ['01','02','07','08','12','13','18','19','23','24','29','30','34','35','40','45','46'],
        'blue'=> [ '03','04','09','10','14','15','20','25','26','31','36','37','41','42','47','48'],
        'green'=> ['05','06','11','16','17','21','22','27','28','32','33','38','39','43','44','49'],
    ];
    return $number_arr[$name];
}

function bo_en_name_arr(){
    $number_arr = [
        'red'=> "红波",
        'blue'=> "蓝波",
        'green'=> "绿波"
    ];
    return $number_arr;
}

function bo_yuce($number=1){
    $number_arr = bo_en_name_arr();
    //
    $number_arr = retain_key_shuffle($number_arr);
    //$new_number_arr = array_slice($number_arr,0,7);
    $data = array_slice($number_arr,0,$number);
    return $data;
}


function bo_name($name='red'){
    $number_arr = bo_en_name_arr();
    return $number_arr[$name];
}

function bo_name_by_number($number){
    $bo_name_arr = ['red','blue','green'];
    $bo_name = '';
    foreach($bo_name_arr as $name){
        $bo_number = bo(trim($name));
        foreach($bo_number as $v){
            if($v == $number){
                $bo_name = $name;
                break;
            }
        }
    }
    $data = [
        'name'=>$bo_name,
        'value'=>bo_name($bo_name)
    ];
    return $data;
}

//判断波段是否正确

function bo_number_is_true($number_arr=[],$bo_en_name='red'){
    $bo_number_arr = bo($bo_en_name);
    $intersection = array_intersect($number_arr, $bo_number_arr);//交集
    $data = [];
    if($intersection){
        //猜对了
        $data['right'] = $intersection;
        $data['wrong'] = array_diff($number_arr, $intersection);
        $data['res'] = true;
    }else{
        //猜对了
        $data['right'] = $intersection;
        $data['wrong'] = array_diff($number_arr, $intersection);
        $data['res'] = false;
    }
    //var_dump($data);
    return $data;
}



function wuxing($name='jin'){
    $number_arr = [
        'jin'=>['01','02','09','10','23','24','31','32','39','40'],
        'mu'=>['05','06','13','14','21','22','35','36','43','44'],
        'shui'=>['11','12','19','20','27','28','41','42','49'],
        'huo'=>['07','08','15','16','29','30','37','38','45','46'],
        'tu'=>['03','04','17','18','25','26','33','34','47','48']
    ];
    return $number_arr[$name];
}

function wuxing_yuce($number=1){
    $number_arr = wuxing_en_name_arr();
    //
    $number_arr = retain_key_shuffle($number_arr);
    //$new_number_arr = array_slice($number_arr,0,7);
    $data = array_slice($number_arr,0,$number);
    return $data;
}


function wuxing_en_name_arr(){
    $number_arr = [
        'jin'=> "金",
        'mu'=> "木",
        'shui'=> "水",
        'huo'=> "火",
        'tu'=> "土"
    ];
    return $number_arr;
}

function wuxing_name($name='jin'){
    $number_arr = wuxing_en_name_arr();
    return $number_arr[$name];
}


function wuxing_name_by_number($number){
    $bo_name_arr = wuxing_en_name_arr();//['red','lan','lv'];
    $bo_name = '';
    foreach($bo_name_arr as $name=>$wu_name){
        $bo_number = wuxing(trim($name));
        foreach($bo_number as $v){
            if($v == $number){
                $bo_name = $name;
                break;
            }
        }
    }
    $data = [
        'name'=>$bo_name,
        'value'=>wuxing_name($bo_name)
    ];
    return $data;
}

function wuxing_number_is_true($number_arr=[],$bo_en_name='jin'){
    $bo_number_arr = wuxing($bo_en_name);
    $intersection = array_intersect($number_arr, $bo_number_arr);//交集
    $data = [];
    if($intersection){
        //猜对了
        $data['right'] = $intersection;
        $data['wrong'] = array_diff($number_arr, $intersection);
        $data['res'] = true;
    }else{
        //猜对了
        $data['right'] = $intersection;
        $data['wrong'] = array_diff($number_arr, $intersection);
        $data['res'] = false;
    }
    //var_dump($data);
    return $data;
}


function shengxiao($name='tu'){
    $number_arr = [
        'tu'=>['01','13','25','37','49'],
        'hu'=>['02','14','26','38'],
        'niu'=>['03','15','27','39'],
        'shu'=>['04','16','28','40'],
        'zhu'=>['05','17','29','41'],
        'gou'=>['06','18','30','42'],
        'ji'=>['07','19','31','43'],
        'hou'=>['08','20','32','44'],
        'yang'=>['09','21','33','45'],
        'ma'=>['10','22','34','46'],
        'she'=>['11','23','35','47'],
        'long'=>['12','24','36','48']
    ];
    return $number_arr[$name];
}


function shengxiao_en_name_arr(){
    $number_arr = [
        'tu'=> "兔",
        'hu'=> "虎",
        'niu'=> "牛",
        'shu'=> "鼠",
        'zhu'=> "猪",
        'gou'=> "狗",
        'ji'=> "鸡",
        'hou'=> "猴",
        'yang'=> "羊",
        'ma'=> "马",
        'she'=> "蛇",
        'long'=> "龙"
    ];
    return $number_arr;
}


function shengxiao_yuce_number_list($number_list=[]){
    $number_arr = [];//猜中率 50%
    foreach($number_list as $v){
        $number_arr[shengxiao_name_by_number($v)['name']] = shengxiao_name_by_number($v)['value'];
    }

    //$number_arr = shengxiao_en_name_arr();
    //
    $number_arr = retain_key_shuffle($number_arr);
    $new_arr = [];
    foreach($number_arr as $k=>$v){
        $new_arr[] = $k;
    }
    //$new_number_arr = array_slice($number_arr,0,7);
    //$data = array_slice($new_arr,0,$number);
    return $new_arr;
}


function shengxiao_yuce($number=1){
    $number_arr = shengxiao_en_name_arr();
    //
    $number_arr = retain_key_shuffle($number_arr);
    $new_arr = [];
    foreach($number_arr as $k=>$v){
        $new_arr[] = $k;
    }
    //$new_number_arr = array_slice($number_arr,0,7);
    $data = array_slice($new_arr,0,$number);
    return $data;
}


function shengxiao_name($name='tu'){
    $number_arr = shengxiao_en_name_arr();
    return $number_arr[$name];
}

function shengxiao_name_by_number($number){
    $bo_name_arr = shengxiao_en_name_arr();
    $bo_name = '';
    foreach($bo_name_arr as $name=>$va_name){
        $bo_number = shengxiao(trim($name));
        foreach($bo_number as $v){
            if($v == $number){
                $bo_name = $name;
                break;
            }
        }
    }
    $data = [
        'name'=>$bo_name,
        'value'=>shengxiao_name($bo_name)
    ];
    return $data;
}

function shengxiao_key_by_name($sx_name){
    $bo_name_arr = shengxiao_en_name_arr();
    $key_str = "";
    foreach($bo_name_arr as $name=>$va_name){
        if($va_name == $sx_name){
            $key_str = $name;
            break;
        }
    }
    return $key_str;
}


//判断生效是否正确

function shengxiao_number_is_true($number_arr=[],$bo_en_name='tu'){
    $bo_number_arr = shengxiao($bo_en_name);
    $intersection = array_intersect($number_arr, $bo_number_arr);//交集
    $data = [];
    if($intersection){
        //猜对了
        $data['right'] = $intersection;
        $data['wrong'] = array_diff($number_arr, $intersection);
        $data['res'] = true;
    }else{
        //猜对了
        $data['right'] = $intersection;
        $data['wrong'] = array_diff($number_arr, $intersection);
        $data['res'] = false;
    }
    //var_dump($data);
    return $data;
}

//判断生效是否正确

function more_shengxiao_number_is_true($number_arr=[],$bo_en_name_arr=['tu']){
    $result = false;
    foreach($bo_en_name_arr as $bo_en_name){
        $res = shengxiao_number_is_true($number_arr,$bo_en_name);
        if($res['res']){
            $result = true;
        }
    }
    return $result;
}

//判断平特码是否正确

function pingte_number_is_true($pingma=[],$tema=[],$pingteyiwei_number){
    $result = false;
    $kj_numbers = array_merge($pingma,$tema);
    if(in_array($pingteyiwei_number,$kj_numbers)){
        $result = true;
    }
    return $result;
}

//判断平特一肖是否正确
function pingte_shengxiao_is_true($pingma=[],$tema=[],$pingteyiwei_shengxiao){
    $result = false;
    $kj_numbers = array_merge($pingma,$tema);
    $shengxiao_arr = [];
    foreach($kj_numbers as $v){
        $shengxiao_arr[] = shengxiao_name_by_number($v)['name'];
    }
//    var_dump($pingteyiwei_shengxiao);
//    var_dump($shengxiao_arr);

    if(in_array($pingteyiwei_shengxiao,$shengxiao_arr)){
        $result = true;
    }
    return $result;
}

function getShuangmian(){
    $data=[];
    $data["da"]='大';
    $data["xiao"]='小';
    $data['dan']='单';
    $data['shuang']='双';
    $data['dadan']='大单';
    $data['dashuang']='大双';
    $data['xiaodan']='小单';
    $data['xiaoshuang']='小双';
//    $data['hedan']='合单';
//    $data['heshuang']='合双';
    $data['red']='红波';
    $data['blue']='蓝波';
    $data['green']='绿波';
    return $data;
}
//获取双面的key
function getSmKeyByVal($number){
    $name_arr = getShuangmian();
    $key_name = '';
    foreach($name_arr as $name=>$va_name){
        if($va_name == $number){
            $key_name = $name;
            break;
        }
    }
    $data = [
        'name'=>$key_name,
        'value'=>$number
    ];
    return $data;
}
function getGameList_lhc(){
    $gmidName=[];
    $gmidName[1]='澳洲幸运10';
    $gmidName[2]='幸运飞艇';
    $gmidName[3]='极速飞艇';
    $gmidName[4]='极速赛车';
    $gmidName[5]='加拿大28';
    $gmidName[6]='极速摩托/飞艇';
    $gmidName[9]='六合彩';
    return $gmidName;
}

function getWanfaList(){
	$wanfa = array();
	$wanfa['tema'] = "特码";
	$wanfa['tema_daxiao'] = "特码大小";
	$wanfa['tema_danshuang'] = "特码单双";
	$wanfa['tema_hedanshuang'] = "特码和数单双";
	$wanfa['teme_daxiaodanshuang'] = "特码大小单双组合";
	$wanfa['zhengma'] = "正码";
}
//获取赔率信息
function getUserLhcPeilv($userid,$roomid='666777'){

    $peilv = get_query_vals('fn_lottery9',"*",['roomid'=>$roomid]);
    $user_fandian = userFanDian($userid,$roomid,9);
    $levels = ($peilv['fandian'] - $user_fandian) / 0.01;//返点基点数
//    var_dump($user_fandian);
//    var_dump($levels);
    $peilv['tema_haoma'] = $peilv['tema_haoma'] - $peilv['tema_haoma_step'] * $levels;
    $peilv['tema_shuangmian_da'] = $peilv['tema_shuangmian_daxiao'] - $peilv['tema_shuangmian_daxiao_step'] * $levels;
    $peilv['tema_shuangmian_xiao'] = $peilv['tema_shuangmian_daxiao'] - $peilv['tema_shuangmian_daxiao_step'] * $levels;
    $peilv['tema_shuangmian_dan'] = $peilv['tema_shuangmian_danshuang'] - $peilv['tema_shuangmian_danshuang_step'] * $levels;
    $peilv['tema_shuangmian_shuang'] = $peilv['tema_shuangmian_danshuang'] - $peilv['tema_shuangmian_danshuang_step'] * $levels;
    $peilv['tema_shuangmian_dadan'] = $peilv['tema_shuangmian_dxds'] - $peilv['tema_shuangmian_dxds_step'] * $levels;
    $peilv['tema_shuangmian_dashuang'] = $peilv['tema_shuangmian_dxds'] - $peilv['tema_shuangmian_dxds_step'] * $levels;
    $peilv['tema_shuangmian_xiaodan'] = $peilv['tema_shuangmian_dxds'] - $peilv['tema_shuangmian_dxds_step'] * $levels;
    $peilv['tema_shuangmian_xiaoshuang'] = $peilv['tema_shuangmian_dxds'] - $peilv['tema_shuangmian_dxds_step'] * $levels;

    $peilv['tema_shuangmian_dadan_step'] = $peilv['tema_shuangmian_dxds_step'];
    $peilv['tema_shuangmian_dashuang_step'] = $peilv['tema_shuangmian_dxds_step'];
    $peilv['tema_shuangmian_xiaodan_step'] = $peilv['tema_shuangmian_dxds_step'];
    $peilv['tema_shuangmian_xiaoshuang_step'] = $peilv['tema_shuangmian_dxds_step'];

    $peilv['tema_shuangmian_da_step'] = $peilv['zhengma_shuangmian_daxiao_step'];
    $peilv['tema_shuangmian_xiao_step'] = $peilv['zhengma_shuangmian_daxiao_step'];
    $peilv['tema_shuangmian_dan_step'] = $peilv['zhengma_shuangmian_danshuang_step'];
    $peilv['tema_shuangmian_shuang_step'] = $peilv['zhengma_shuangmian_danshuang_step'];

    $peilv['tema_shuangmian_red'] = $peilv['tema_shuangmian_red'] - $peilv['tema_shuangmian_red_step'] * $levels;
    $peilv['tema_shuangmian_blue'] = $peilv['tema_shuangmian_blue'] - $peilv['tema_shuangmian_blue_step'] * $levels;
    $peilv['tema_shuangmian_green'] = $peilv['tema_shuangmian_green'] - $peilv['tema_shuangmian_green_step'] * $levels;
    $peilv['tema_shengxiao'] = $peilv['tema_shengxiao'] - $peilv['tema_shengxiao_step'] * $levels;
    $peilv['tema_shengxiao_tu'] = $peilv['tema_shengxiao_tu'] - $peilv['tema_shengxiao_tu_step'] * $levels;

    //正码赔率
    $peilv['zhengma_haoma'] = $peilv['zhengma_haoma'] - $peilv['zhengma_haoma_step'] * $levels;
    $peilv['zhengma_shuangmian_da'] = $peilv['zhengma_shuangmian_daxiao'] - $peilv['zhengma_shuangmian_daxiao_step'] * $levels;
    $peilv['zhengma_shuangmian_xiao'] = $peilv['zhengma_shuangmian_daxiao'] - $peilv['zhengma_shuangmian_daxiao_step'] * $levels;
    $peilv['zhengma_shuangmian_dan'] = $peilv['zhengma_shuangmian_danshuang'] - $peilv['zhengma_shuangmian_danshuang_step'] * $levels;
    $peilv['zhengma_shuangmian_shuang'] = $peilv['zhengma_shuangmian_danshuang'] - $peilv['zhengma_shuangmian_danshuang_step'] * $levels;
    $peilv['zhengma_shuangmian_dadan'] = $peilv['zhengma_shuangmian_dxds'] - $peilv['zhengma_shuangmian_dxds_step'] * $levels;
    $peilv['zhengma_shuangmian_dashuang'] = $peilv['zhengma_shuangmian_dxds'] - $peilv['zhengma_shuangmian_dxds_step'] * $levels;
    $peilv['zhengma_shuangmian_xiaodan'] = $peilv['zhengma_shuangmian_dxds'] - $peilv['zhengma_shuangmian_dxds_step'] * $levels;
    $peilv['zhengma_shuangmian_xiaoshuang'] = $peilv['zhengma_shuangmian_dxds'] - $peilv['zhengma_shuangmian_dxds_step'] * $levels;

    $peilv['zhengma_shuangmian_dadan_step'] = $peilv['tema_shuangmian_dxds_step'];
    $peilv['zhengma_shuangmian_dashuang_step'] = $peilv['tema_shuangmian_dxds_step'];
    $peilv['zhengma_shuangmian_xiaodan_step'] = $peilv['tema_shuangmian_dxds_step'];
    $peilv['zhengma_shuangmian_xiaoshuang_step'] = $peilv['tema_shuangmian_dxds_step'];

    $peilv['zhengma_shuangmian_da_step'] = $peilv['zhengma_shuangmian_daxiao_step'];
    $peilv['zhengma_shuangmian_xiao_step'] = $peilv['zhengma_shuangmian_daxiao_step'];
    $peilv['zhengma_shuangmian_dan_step'] = $peilv['zhengma_shuangmian_danshuang_step'];
    $peilv['zhengma_shuangmian_shuang_step'] = $peilv['zhengma_shuangmian_danshuang_step'];

    $peilv['zhengma_shuangmian_red'] = $peilv['zhengma_shuangmian_red'] - $peilv['zhengma_shuangmian_red_step'] * $levels;
    $peilv['zhengma_shuangmian_blue'] = $peilv['zhengma_shuangmian_blue'] - $peilv['zhengma_shuangmian_blue_step'] * $levels;
    $peilv['zhengma_shuangmian_green'] = $peilv['zhengma_shuangmian_green'] - $peilv['zhengma_shuangmian_green_step'] * $levels;
    $peilv['zhengma_shengxiao'] = $peilv['zhengma_shengxiao'] - $peilv['zhengma_shengxiao_step'] * $levels;
    $peilv['zhengma_shengxiao_tu'] = $peilv['zhengma_shengxiao_tu'] - $peilv['zhengma_shengxiao_tu_step'] * $levels;

    $peilv['shengxiao'] = $peilv['shengxiao'] - $peilv['shengxiao_step'] * $levels;
    $peilv['shengxiao_tu'] = $peilv['shengxiao_tu'] - $peilv['shengxiao_tu_step'] * $levels;
    //var_dump($peilv);
    return $peilv;
}
//根据用户的返点获取对应的赔率1

function getUserLhcPeilv1($user_rate_arr,$peilv_arr){
    var_dump($user_rate_arr);
//    var_dump($peilv_arr);
    $new_peilv = $peilv_arr;
    return $new_peilv;
}
//获取玩法类别
function getWanfaByType($wanfa,$userid,$roomid){
	$data = array();
//    $userid = 'hulala112';
//    $roomid = '666777';
    $user_peilv = getUserLhcPeilv($userid,$roomid);
    //var_dump($wanfa);
    $wanfa_name = explode("_",$wanfa)[0];
    $type = explode("_",$wanfa)[1];
	if($type == 'haoma'){
		$number_arr = kj_number();
		$new_arr = [];
		foreach($number_arr as $key=>$v){
			$arr = [];
			$arr['id'] = $v;
            $arr['name'] = $v;
            $arr['class'] = bo_name_by_number($v)['name'];
            $arr['rate'] = $user_peilv[$wanfa];
            $arr['check'] = 0;
			$new_arr[] = $arr;
		}
		$data = $new_arr;
	}
	if($type == 'shuangmian'){
        $number_arr = getShuangmian();
        $new_arr = [];
        foreach($number_arr as $key=>$v){
            $arr = [];
            $arr['id'] = $key;
            $arr['name'] = $v;
            $arr['class'] = "black";
            $arr['rate'] = $user_peilv[$wanfa."_".$key];
            $arr['check'] = 0;
            $new_arr[] = $arr;
        }
        $data = $new_arr;
	}
	if($type == 'shengxiao'){
        $number_arr = shengxiao_en_name_arr();
        $new_arr = [];
        foreach($number_arr as $key=>$v){
            $arr = [];
            $arr['id'] = $key;
            $arr['name'] = $v;
            $arr['class'] = "black";
            if($key =='tu'){
                $arr['rate'] = $user_peilv[$wanfa.'_tu'];
            }else{
                $arr['rate'] = $user_peilv[$wanfa];
            }

            $arr['check'] = 0;
            $new_arr[] = $arr;
        }
        $data = $new_arr;
	}
	return($data);
}
//获取所有玩法列表及赔率增长点 20230712
function getWanfaPeilvStepList($type,$userid,$roomid='666777'){
    $lottery = get_query_vals('fn_lottery'.$type,"*",['roomid'=>$roomid]);
    $user_fandian = userFanDian($userid,$roomid,$type);
    $levels = ($lottery['fandian'] - $user_fandian) / 0.01;//返点基点数
    $peilv_list = [];
    $wanfa_list = [];
    foreach($lottery as $key=>$v){
        if(strstr($key,"_step")){
            $arr = [];
            $name = "";
            if(strstr($key,"tema_")){
                $name = "特码";
            }
            if(strstr($key,"zhengma_")){
                $name = "正码";
            }
            if(strstr($key,"shuangmian_")){
                $name = $name."双面";
            }
            if(strstr($key,"shengxiao_")){
                $name = $name."生肖";
            }
            if(strstr($key,"red_")){
                $name = $name."红波";
            }
            if(strstr($key,"blue_")){
                $name = $name."蓝波";
            }
            if(strstr($key,"green_")){
                $name = $name."绿波";
            }
            if(strstr($key,'tu_')){
                $name = $name."兔";
            }
            if(strstr($key,'danshuang_')){
                $name = $name."单双";
            }
            if(strstr($key,'daxiao_')){
                $name = $name."大小";
            }
            if(strstr($key,'dxds_')){
                $name = $name."大小";
            }
            if($name){
                $arr['key'] = $key;
                $arr['name'] = $name;
                $arr['value'] = $v;
                $arr['user_peilv'] = $lottery[str_replace("_step","",$key)] - $lottery[$key] * $levels;
                $arr['peilv_step'] =  $lottery[$key];
                $wanfa_list[$key] = $name;
                $peilv_list[] = $arr;
            }
        }
    }
    return ['peilv'=>$peilv_list,'wanfa_list'=>$wanfa_list];
}
//获取玩法大分类
function getWanfaCate(){
	$wanfa = array();
	$wanfa['tema_haoma'] = "特码";
	$wanfa['tema_shuangmian'] = "特码双面";
	$wanfa['zhengma_haoma'] = "正码";
	$wanfa['zhengma_shuangmian'] = "正码双面";
    $wanfa['zhengma_shengxiao'] = "正码生肖";
    $wanfa['tema_shengxiao'] = "特码生肖";
	return($wanfa);
}
//正码list
function zhengmaCateList(){
    return [
        ['posi'=>1,'name'=>'正一码'],
        ['posi'=>2,'name'=>'正二码'],
        ['posi'=>3,'name'=>'正三码'],
        ['posi'=>4,'name'=>'正四码'],
        ['posi'=>5,'name'=>'正五码'],
        ['posi'=>6,'name'=>'正六码']
    ];
}
//正码list
function shengxiaoCateList(){
    return [
        ['posi'=>1,'name'=>'正一肖'],
        ['posi'=>2,'name'=>'正二肖'],
        ['posi'=>3,'name'=>'正三肖'],
        ['posi'=>4,'name'=>'正四肖'],
        ['posi'=>5,'name'=>'正五肖'],
        ['posi'=>6,'name'=>'正六肖'],
    ];
}
//获取玩法详情
function getWanfaItem($wanfa,$userid,$roonid){
	//$type = explode("_",$wanfa)[1];
	$data = getWanfaByType($wanfa,$userid,$roonid);
	return($data);
}
//获取开奖数组
function kj_number_arr($arr){
    $data = [];
    foreach($arr as $v){
        $arr = [];
        $arr['number'] = $v;
        $arr['bo'] = bo_name_by_number($v)['name'];
        $arr['sx'] = shengxiao_name_by_number($v)['name'];
        $arr['sx_name'] = shengxiao_name_by_number($v)['value'];
        $data[] = $arr;
    }
    return $data;
}

//获取投注的内容
//获取玩法类别
function getPeilvKeyByItemName($wanfa,$bet_name){
    $type = explode("_",$wanfa)[1];
    $peilv_key = '';
    if($type == 'haoma'){
        $peilv_key = $wanfa;//getSmKeyByVal()
    }
    if($type == 'shuangmian'){
        $sm_key = getSmKeyByVal($bet_name)['name'];
        //var_dump($sm_key);
        $peilv_key = $wanfa."_".$sm_key;//getSmKeyByVal()
    }
    if($type == 'shengxiao'){
        $sx_key = shengxiao_key_by_name($bet_name);
        if($sx_key =='tu'){
            $peilv_key = $wanfa.'_tu';
        }else{
            $peilv_key = $wanfa;
        }
    }
    return $peilv_key;
}

//获取投注对应的key
function getBetKeyByItemName($wanfa,$bet_name){
    $type = explode("_",$wanfa)[1];
    $bet_key = '';
    if($type == 'haoma'){
        $bet_key = intval($bet_name)-1;//getSmKeyByVal()
    }
    if($type == 'shuangmian'){
        $bet_key = getSmKeyByVal($bet_name)['name'];
    }
    if($type == 'shengxiao'){
        $bet_key = shengxiao_key_by_name($bet_name);
    }
    return $bet_key;
}





//判断是否中奖
function betIsRight($order_info,$kj_info){
    //$order_info = get_query_vals('fn_order', '*', array('id' => $order_id));
    if($order_info){
        //$kj_info = get_query_vals('fn_open_lhc', '*', array('term' => $order_info['term']));
        if($kj_info){
            $kj_num = $kj_info['code'].",".$kj_info['code_te'];
            $kj_num_arr = explode(',',$kj_num);
            //获取投注的位置
            if($order_info['mingci']){
                $wanfa_info = explode('#',$order_info['mingci']);
                $contents_info = explode('#',$order_info['content']);
                $wanfa_type = explode('_',$wanfa_info[0])[1];//玩法类型
                $bet_posi = intval($wanfa_info[2]);
                $bet_kj_number = $kj_num_arr[$bet_posi-1];
                $bet_kj_number_val = "";
                if($wanfa_type == "haoma"){
                    $bet_kj_number_val = $bet_kj_number;
                }
                if($wanfa_type == "shengxiao"){
                    $bet_kj_number_val = shengxiao_name_by_number($bet_kj_number)['value'];
                }
                if($wanfa_type == "shuangmian"){
                    $bet_key = $wanfa_info[1];
                    if($bet_key == 'da' || $bet_key == 'xiao'){
                        if($bet_kj_number > 24){
                            $bet_kj_number_val = "大";
                        }else{
                            $bet_kj_number_val = "小";
                        }
                    }
                    if($bet_key == 'dan' || $bet_key == 'shuang'){
                        if($bet_kj_number % 2 == 0){
                            $bet_kj_number_val = "双";
                        }else{
                            $bet_kj_number_val = "单";
                        }
                    }

                    if($bet_key == 'dadan' || $bet_key == 'dashuang' || $bet_key == 'xiaodan' || $bet_key == 'xiaoshuang'){

                        if($bet_kj_number > 24){
                            $bet_kj_number_val = "大";
                        }else{
                            $bet_kj_number_val = "小";
                        }

                        if($bet_kj_number % 2 == 0){
                            $bet_kj_number_val .= "双";
                        }else{
                            $bet_kj_number_val .= "单";
                        }
                    }
                    if($bet_key == 'red' || $bet_key == 'blue' || $bet_key == 'green'){
                        $bet_kj_number_val = bo_name_by_number($bet_kj_number)['value'];
                    }
                }
                $user_bet_val = $contents_info[2];
                if($bet_kj_number_val && $user_bet_val){
                    if($bet_kj_number_val == $user_bet_val){
                        return 1;//正确
                    }else{
                        return 2;//错误
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }else{
        return false;
    }
}

?>