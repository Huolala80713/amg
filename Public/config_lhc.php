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

function getWeishu(){
    $data=[];
    for($i=0;$i<10;$i++){
        $data[$i] = $i.'尾';
    }
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
//获取双面的key
function getWeishuByVal($number){
    $name_arr = getWeishu();
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

//获取子玩法
function getWanfaSonlistByType($wanfa){
    $list = [];
    if ($wanfa == "zhengma_haoma" || $wanfa == "zhengma_shuangmian") {
        $list = zhengmaCateList();
    }
    if ($wanfa == "lianma_haoma") {
        $list = lianmaCateList();
    }
    if ($wanfa == "zhengma_shengxiao") {
        $list = shengxiaoCateList();
    }
    return $list;
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
            $arr['rate'] = sprintf("%.3f",$user_peilv[$wanfa]);//round($user_peilv[$wanfa],3);
            $arr['check'] = 0;
			$new_arr[] = $arr;
		}
		$data = $new_arr;
	}
    if($type == 'weishu'){
        $number_arr = getWeishu();
        $new_arr = [];
        foreach($number_arr as $key=>$v){
            $arr = [];
            $arr['id'] = $v;
            $arr['name'] = $v;
            $arr['class'] = 'black';
            $arr['rate'] = sprintf("%.3f",$user_peilv[$wanfa]);//round($user_peilv[$wanfa],3);
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
            $arr['rate'] = sprintf("%.3f",$user_peilv[$wanfa."_".$key]);//$user_peilv[$wanfa."_".$key];
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
                $arr['rate'] = sprintf("%.3f",$user_peilv[$wanfa.'_tu']);//$user_peilv[$wanfa.'_tu'];
            }else{
                $arr['rate'] = sprintf("%.3f",$user_peilv[$wanfa]);//$user_peilv[$wanfa];
            }

            $arr['check'] = 0;
            $new_arr[] = $arr;
        }
        $data = $new_arr;
	}
	return($data);
}

//获取所有玩法 20230718
function getWanfaListByType($wanfa){
    $data = array();
    //var_dump($wanfa);
    if(strstr($wanfa,"_")){
        $type = explode("_",$wanfa)[1];
    }else{
        $type = $wanfa;
    }
    if($type == 'haoma'){
        $number_arr = kj_number();
        $new_arr = [];
        foreach($number_arr as $key=>$v){
            $arr = [];
            $arr['id'] = intval($v-1);
            $arr['name'] = $v;
            $arr['class'] = bo_name_by_number($v)['name'];
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
            $arr['check'] = 0;
            $new_arr[] = $arr;
        }
        $data = $new_arr;
    }
    if($type == 'weishu'){
        $number_arr = getWeishu();
        $new_arr = [];
        foreach($number_arr as $key=>$v){
            $arr = [];
            $arr['id'] = $key;
            $arr['name'] = $v;
            $arr['class'] = "black";
            $arr['check'] = 0;
            $new_arr[] = $arr;
        }
        $data = $new_arr;
    }
    return($data);
}
//获取所有的玩法 20230718
function getAllWanfaItem(){
    $wanfa_one_cate = getWanfaCate();
    $wanfa_list = [];
    foreach($wanfa_one_cate as $key=>$value){
        $arr = [];
        $arr['id'] = $key;
        $arr['name'] = $value;
        $son_list = getWanfaSonlistByType($key);
        if(!$son_list){
            $son_list =[['posi'=>7,'name'=>'特码']];
        }
        //获取子类的各类玩法
        foreach($son_list as $sonkey=>&$sv){
            $item_list =  getWanfaListByType($key);
            $son_item = [];
            foreach($item_list as $wf_key=>$wf_val){
                $item = [];
                $item = $wf_val;
                $item['wanfa_name'] = $value;
                $item['son_name'] = $sv['name'];
                $item['mingci'] = $key."#".$wf_val['id']."#".$sv['posi'];
                $item['class'] = $key."-".$wf_val['id']."-".$sv['posi'];
                $item['bg_class'] = $wf_val['class'];
                $son_item[] = $item;
            }
            $sv['son_item'] = $son_item;
        }
        $arr['list'] = $son_list;
        $wanfa_list[] = $arr;
    }
    return $wanfa_list;
}

//获取所有的玩法 20230718
function getAllWanfaItemByWanfa($wanfa,$posi){
    $wanfa_one_cate = getWanfaCate();
    $wanfa_name = $wanfa_one_cate[$wanfa];
    $item_list =  getWanfaListByType($wanfa);
    $son_item = [];
    foreach($item_list as $wf_key=>$wf_val){
        $item = [];
        $item = $wf_val;
        $item['wanfa_name'] = $wanfa_name;
        $item['mingci'] = $wanfa."#".$wf_val['id']."#".$posi;
        $item['class'] = $wanfa."-".$wf_val['id']."-".$posi;
        $item['bg_class'] = $wf_val['class'];
        $son_item[] = $item;
    }
    return $son_item;
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
    $wanfa['lianma_haoma'] = "连码";
	$wanfa['zhengma_shuangmian'] = "正码双面";
    $wanfa['zhengma_shengxiao'] = "正码生肖";
    $wanfa['tema_shengxiao'] = "特码生肖";
	return($wanfa);
}
//正码list
function zhengmaCateList(){
    return [
        ['id'=>11,'posi'=>'1,2,3,4,5,6','name'=>'正码','zj_num'=>'1','bet_number'=>1],
        ['id'=>12,'posi'=>1,'name'=>'正1特','zj_num'=>'1','bet_number'=>1],
        ['id'=>13,'posi'=>2,'name'=>'正2特','zj_num'=>'1','bet_number'=>1],
        ['id'=>14,'posi'=>3,'name'=>'正3特','zj_num'=>'1','bet_number'=>1],
        ['id'=>15,'posi'=>4,'name'=>'正4特','zj_num'=>'1','bet_number'=>1],
        ['id'=>16,'posi'=>5,'name'=>'正5特','zj_num'=>'1','bet_number'=>1],
        ['id'=>17,'posi'=>6,'name'=>'正6特','zj_num'=>'1','bet_number'=>1]
    ];
}

//正码list
function shengxiaoCateList(){
    return [
        ['id'=>1,'posi'=>1,'name'=>'正一肖','zj_num'=>'1','bet_number'=>1],
        ['id'=>2,'posi'=>2,'name'=>'正二肖','zj_num'=>'1','bet_number'=>1],
        ['id'=>3,'posi'=>3,'name'=>'正三肖','zj_num'=>'1','bet_number'=>1],
        ['id'=>4,'posi'=>4,'name'=>'正四肖','zj_num'=>'1','bet_number'=>1],
        ['id'=>5,'posi'=>5,'name'=>'正五肖','zj_num'=>'1','bet_number'=>1],
        ['id'=>6,'posi'=>6,'name'=>'正六肖','zj_num'=>'1','bet_number'=>1],
    ];
}
//连码 玩法 20230724
/*
 * zj_num 中奖号码的数量
 * bet_number 每组号码的数量
 */
function lianmaCateList(){
    return [
        ['id'=>21,'posi'=>'1,2,3,4,5,6','name'=>'三中二','zj_num'=>'2,3','bet_number'=>3],
        ['id'=>22,'posi'=>'7','name'=>'二中特','zj_num'=>'1,2','bet_number'=>2],
        ['id'=>23,'posi'=>'1,2,3,4,5,6','name'=>'三全中','zj_num'=>'3','bet_number'=>3],
        ['id'=>24,'posi'=>'1,2,3,4,5,6','name'=>'二全中','zj_num'=>'2','bet_number'=>2],
        ['id'=>25,'posi'=>'7','name'=>'特串','zj_num'=>'2','bet_number'=>2],
        ['id'=>26,'posi'=>'1,2,3,4,5,6','name'=>'四中一','zj_num'=>'1','bet_number'=>4],
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
                $bet_kj_number = $kj_num_arr[$bet_posi-1];//开奖号码
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



function lhcorderlist($userid , $day1 , $day2 , $gamename , $jiesuan = '' , $page = 1 , $limit = 10){

    $day1 = date('Y-m-d 06:00:00' , strtotime($day1));
    $day2 = date('Y-m-d 05:59:59' , strtotime($day2 . ' + 1day'));

    $ordersql = " and (`addtime` between '{$day1}' and '{$day2}')";
    if($gamename){
        $ordersql .= " and type = '" . $gamename . "'";
    }
    $ordertable = 'fn_order';
    $order = ['addtime desc'];
    if($jiesuan == 'zj'){
        $sql = "roomid = {$_SESSION['roomid']} and `status` > 0 and status != '未结算'  and status != '已撤单' " . $ordersql ;
    }elseif($jiesuan == 'wzj'){
        $sql = "roomid = {$_SESSION['roomid']} and `status` < 0 and status != '未结算'  and status != '已撤单' " . $ordersql ;
    }elseif($jiesuan == 'dkj' || $jiesuan === false){
        $sql = "roomid = {$_SESSION['roomid']} and `status` = '未结算' " . $ordersql ;
    }elseif($jiesuan && $jiesuan != 'all'){
        $sql = "roomid = {$_SESSION['roomid']} and (`status` > 0 or `status` < 0) and status != '未结算'  and status != '已撤单' " . $ordersql ;
    }else{
        $sql = "roomid = {$_SESSION['roomid']} " . $ordersql ;
    }
    if(is_array($userid)){
        $sql .= " and userid in ('" . implode("','" , $userid) . "') ";
    }else{
        $sql .= " and userid = '{$userid}' ";
    }
   // var_dump($sql);
    select_query($ordertable, '*', $sql , $order , (($page - 1) * $limit) . ',' . $limit);
    $order_list = $cons = [];
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }

    //var_dump($cons);
    foreach ($cons as $order) {
        $wanfa_name = explode("#",$order['content']);
        $mingci = $wanfa_name[0]."[".$wanfa_name[1]."]";
        $order_list[] = [
            'gamename' => getGameTxtName($order['type']),
            'add_time' => date('m-d' , strtotime($order['addtime'])) . '<br>' . date('H:i:s' , strtotime($order['addtime'])),
            'term' => $order['term'],
            'userid' => $order['userid'],
            'wanfa' => $mingci,
            'content' => $wanfa_name[2],
            'peilv' => $order['peilv'],
            'type' => $order['type'],
            'money' => $order['money'],
            'status' => $order['status'],
        ];
    }
    $all_touzhu = get_query_val($ordertable , 'sum(money)' , $sql);
    $all_paijian = get_query_val($ordertable , 'sum(status)' , $sql . " and status != '未结算'  and status != '已撤单'");
    $count = get_query_val($ordertable , 'count(id)' , $sql);
    $page = ceil($count / $limit);
    $yingkui = round($all_paijian - $all_touzhu , 4);
    return ['list'=>$order_list,'count'=>$count,'all_touzhu'=>$all_touzhu,'all_paijian'=>$all_paijian,'page'=>$page,'yingkui'=>$yingkui];
}


//连码获取组数
function lianma($data, $len){
    $data = array_values($data);//充值key值
    $boxData = [];
    $n = count($data);
    if ($len <= 0 || $len > $n) {
        return $boxData;
    }
    for ($i = 0; $i < $n; $i++) {
        $tempData = [$data[$i]];
        if ($len == 1) {
            $boxData[] = $tempData;
        } else {
            $b = array_slice($data, $i + 1);
            $c = lianma($b, $len - 1);
            foreach ($c as $v) {
                $boxData[] = array_merge($tempData, $v);
            }
        }
    }
    return $boxData;
}


//获取投注的号码数组列表 20230725
/*
 * $param $number_arr 选中的号码
 * param $bet_number 每组号码的数量
 */
function getBetNunber($number_arr,$bet_number){
    return lianma($number_arr,$bet_number);
}


/////////////////////////////////////////////20230726 最新逻辑////////////////////////////////////////////////////////////////////////////////////////////////////////////
//获取一级玩法分类
function getFirstCateList($game_id){
    $sql = "parent_id = 0 and is_show = 1 and game_id = {$game_id}";
    $order = "sort desc,id asc";
    select_query("fn_lhc_wanfa", 'id,name,zj_num,wanfa_key,peilv,max_peilv,bet_price_max,bet_price_min,peilv_step', $sql , $order);
    $wanfa_list = $cons = [];
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    // var_dump($cons);
    return $cons;
}
function getFirstCateAllList($game_id){
    $sql = "parent_id = 0 and admin_is_show=1 and game_id = {$game_id}";
    $order = "sort desc,id asc";
    select_query("fn_lhc_wanfa", 'id,name,zj_num,wanfa_key,peilv,max_peilv,bet_price_max,bet_price_min,peilv_step', $sql , $order);
    $wanfa_list = $cons = [];
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    // var_dump($cons);
    return $cons;
}

function getWanfaSonListById($id,$userid=0,$roomid=0){
    $wanfa_info = get_query_vals('fn_lhc_wanfa','id,name,wanfa_type,wanfa_key,peilv,zj_num,bet_price_max,bet_price_min,peilv_step,game_id',['id'=>$id]);
    $peilv = get_query_vals('fn_lottery'.$wanfa_info['game_id'],"*",['roomid'=>$roomid]);
    $user_fandian = userFanDian($userid,$roomid,$wanfa_info['game_id']);
    $levels = ($peilv['fandian'] - $user_fandian) / 0.01;//返点基点数
    $sql = "parent_id = {$id} and is_show = 1";
    $order = "sort desc,id asc";
    select_query("fn_lhc_wanfa", 'id,name,zj_num,wanfa_key,bet_posi,peilv,peilv_step,parent_id', $sql , $order);
    $wanfa_list = $cons = [];
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    $data = [];
    foreach($cons as $k=>$v){
        $arr = [];
        $arr = $v;
        $rate = $v['peilv'] - $v['peilv_step'] * $levels;
        $arr['rate'] = $rate;
        $arr['show_rate'] = sprintf("%.3f",$rate);
        $data[] = $arr;
    }
    // var_dump($cons);
    return $data;
}
function getWanfaAllSonListById($id,$userid=0,$roomid=0){
    $wanfa_info = get_query_vals('fn_lhc_wanfa','id,name,wanfa_type,wanfa_key,peilv,zj_num,bet_price_max,bet_price_min,peilv_step,game_id',['id'=>$id]);
    $peilv = get_query_vals('fn_lottery'.$wanfa_info['game_id'],"*",['roomid'=>$roomid]);
    $user_fandian = userFanDian($userid,$roomid,$wanfa_info['game_id']);
    $levels = ($peilv['fandian'] - $user_fandian) / 0.01;//返点基点数
    $sql = "parent_id = {$id} and  admin_is_show = 1";
    $order = "sort desc,id asc";
    select_query("fn_lhc_wanfa", 'id,name,zj_num,wanfa_key,peilv,max_peilv,bet_price_max,bet_price_min,peilv_step', $sql , $order);
    $wanfa_list = $cons = [];
    while ($con = db_fetch_array()) {
        $cons[] = $con;
    }
    $data = [];
    foreach($cons as $k=>$v){
        $arr = [];
        $arr = $v;
        $rate = $v['peilv'] - $v['peilv_step'] * $levels;
        $arr['rate'] = $rate;
        $arr['show_rate'] = sprintf("%.3f",$rate);
        $data[] = $arr;
    }
    // var_dump($cons);
    return $data;
}

//根据玩法和ID获取对应的赔率
function getUserPeilvByWanfaId($id,$userid,$roomid,$number_key=''){
    $wanfa_info = get_query_vals('fn_lhc_wanfa','id,name,wanfa_type,wanfa_key,peilv,peilv_step,game_id',['id'=>$id]);
    if($wanfa_info['wanfa_type'] == 'shuangmian'){
        $rate = getUserPeilvByWanfaIdShuangmian($id,$userid,$roomid,$number_key);
    }elseif($wanfa_info['wanfa_type'] == 'shengxiao'){
        $rate = getUserPeilvByWanfaIdShengxiao($id,$userid,$roomid,$number_key);
    }else{
        $peilv = get_query_vals('fn_lottery'.$wanfa_info['game_id'],"*",['roomid'=>$roomid]);
        $user_fandian = userFanDian($userid,$roomid,$wanfa_info['game_id']);
        $levels = ($peilv['fandian'] - $user_fandian) / 0.01;//返点基点数
        $rate = round($wanfa_info['peilv'] - $wanfa_info['peilv_step'] * $levels,3);
    }

    // var_dump($cons);
    return $rate;
}
//获取双面玩法的赔率 20230729
function getUserPeilvByWanfaIdShuangmian($id,$userid,$roomid,$number_key){
    $wanfa_info = get_query_vals('fn_lhc_wanfa','id,name,wanfa_type,wanfa_key,peilv,peilv_step,parent_id,game_id',['id'=>$id]);
    $peilv = get_query_vals('fn_lottery'.$wanfa_info['game_id'],"*",['roomid'=>$roomid]);
    $user_fandian = userFanDian($userid,$roomid,$wanfa_info['game_id']);
    $levels = ($peilv['fandian'] - $user_fandian) / 0.01;//返点基点数
    $shuangmian_list = getShuangmian();
    $number_wanfa = [];
    $number_wanfa = get_query_vals('fn_lhc_wanfa','id,name,wanfa_type,wanfa_key,peilv,peilv_step,game_id',['game_id'=>$wanfa_info['game_id'],'wanfa_key'=>$number_key]);

    if($number_key == "dadan" || $number_key == "dashuang" || $number_key == "xiaodan" || $number_key == "xiaoshuang"){
        $number_wanfa = get_query_vals('fn_lhc_wanfa','id,name,wanfa_type,wanfa_key,peilv,peilv_step,game_id',['game_id'=>$wanfa_info['game_id'],'wanfa_key'=>'dxds']);
    }
//    var_dump($number_key);
//    var_dump($number_wanfa);
    $rate = round($number_wanfa['peilv'] - $number_wanfa['peilv_step'] * $levels,3);
    // var_dump($cons);
    return $rate;
}
//获取生肖赔率 20230731
function getUserPeilvByWanfaIdShengxiao($id,$userid,$roomid,$number_key){
    $wanfa_info = get_query_vals('fn_lhc_wanfa','id,name,wanfa_type,wanfa_key,peilv,peilv_step,parent_id,game_id',['id'=>$id]);
    $peilv = get_query_vals('fn_lottery'.$wanfa_info['game_id'],"*",['roomid'=>$roomid]);
    $user_fandian = userFanDian($userid,$roomid,$wanfa_info['game_id']);
    $levels = ($peilv['fandian'] - $user_fandian) / 0.01;//返点基点数
    if($number_key == 'tu'){
        $wanfa_info_tu = get_query_vals('fn_lhc_wanfa','id,name,wanfa_type,peilv,peilv_step,game_id','parent_id = '.$wanfa_info['parent_id'].' and wanfa_key LIKE "%_tu%"');
        $rate = $wanfa_info_tu['peilv'] - $wanfa_info_tu['peilv_step'] * $levels;
    }else{
        $rate = $wanfa_info['peilv'] - $wanfa_info['peilv_step'] * $levels;
    }
    $rate = round($rate,3);
    return $rate;
}

//获取玩法类别
function getWanfaItemById($wanfa_id,$userid,$roomid){
    $data = array();
//    $userid = 'hulala112';
//    $roomid = '666777';
    $wanfa_info = get_query_vals('fn_lhc_wanfa','id,name,wanfa_type,peilv,peilv_step,game_id,parent_id',['id'=>$wanfa_id]);
    $peilv = get_query_vals('fn_lottery'.$wanfa_info['game_id'],"*",['roomid'=>$roomid]);
    $user_fandian = userFanDian($userid,$roomid,$wanfa_info['game_id']);
    //var_dump($wanfa_info);
    $levels = ($peilv['fandian'] - $user_fandian) / 0.01;//返点基点数
    //var_dump($wanfa);
    $type = $wanfa_info['wanfa_type'];
    if($type == 'haoma' || $type == 'buzhong'){
        $number_arr = kj_number();
        $new_arr = [];
        foreach($number_arr as $key=>$v){
            $arr = [];
            $arr['id'] = $v;
            $arr['name'] = $v;
            $arr['class'] = bo_name_by_number($v)['name'];
            $rate = $wanfa_info['peilv'] - $wanfa_info['peilv_step'] * $levels;
            $arr['rate'] = sprintf("%.3f",$rate);//round($user_peilv[$wanfa],3);$peilv['tema_shuangmian_dxds'] - $peilv['tema_shuangmian_dxds_step'] * $levels
            $arr['check'] = 0;
            $new_arr[] = $arr;
        }
        $data = $new_arr;
    }
    if($type == 'weishu'){
        $number_arr = getWeishu();
        $new_arr = [];
        foreach($number_arr as $key=>$v){
            $arr = [];
            $arr['id'] = $v;
            $arr['name'] = $v;
            $arr['class'] = 'black';
            $rate = $wanfa_info['peilv'] - $wanfa_info['peilv_step'] * $levels;
            $arr['rate'] = sprintf("%.3f",$rate);//round($user_peilv[$wanfa],3);$peilv['tema_shuangmian_dxds'] - $peilv['tema_shuangmian_dxds_step'] * $levels
            $arr['check'] = 0;
            $new_arr[] = $arr;
        }
        $data = $new_arr;
    }
    if($type == 'temabanbo'){
        $number_arr = getTemaBanbo();
        $new_arr = [];
        foreach($number_arr as $key=>$v){
            $arr = [];
            $arr['id'] = $v;
            $arr['name'] = $v;
            $arr['class'] = 'black';
            $rate = $wanfa_info['peilv'] - $wanfa_info['peilv_step'] * $levels;
            $arr['rate'] = sprintf("%.3f",$rate);//round($user_peilv[$wanfa],3);$peilv['tema_shuangmian_dxds'] - $peilv['tema_shuangmian_dxds_step'] * $levels
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
            $rate = getUserPeilvByWanfaIdShuangmian($wanfa_id,$userid,$roomid,$key);
            //$rate = $wanfa_info['peilv'] - $wanfa_info['peilv_step'] * $levels;
            $arr['rate'] = sprintf("%.3f",$rate);//round($user_peilv[$wanfa],3);$peilv['tema_shuangmian_dxds'] - $peilv['tema_shuangmian_dxds_step'] * $levels
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
            if($key == 'tu'){
                $wanfa_info_tu = get_query_vals('fn_lhc_wanfa','id,name,wanfa_type,peilv,peilv_step,game_id','parent_id = '.$wanfa_info['parent_id'].' and wanfa_key LIKE "%_tu%"');
                $rate = $wanfa_info_tu['peilv'] - $wanfa_info_tu['peilv_step'] * $levels;
            }else{
                $rate = $wanfa_info['peilv'] - $wanfa_info['peilv_step'] * $levels;
            }

            $arr['rate'] = sprintf("%.3f",$rate);//round($user_peilv[$wanfa],3);$peilv['tema_shuangmian_dxds'] - $peilv['tema_shuangmian_dxds_step'] * $levels
            $arr['check'] = 0;
            $new_arr[] = $arr;
        }
        $data = $new_arr;
    }
    return($data);
}

//获取投注的信息 20230727
function get_bet_info($wanfa_id,$numbers=[]){
    $wanfa_info = get_query_vals('fn_lhc_wanfa','*',['id'=>$wanfa_id]);
    //获取投注数组
    $bet_number_list = lianma($numbers,$wanfa_info['bet_number']);
    $data = [];
    $data['list'] = $bet_number_list;

}

//获取投注对应的key
function getBetNumberKeyByType($type,$bet_name){
    $bet_key = '';
    if($type == 'haoma'){
        $bet_key = intval($bet_name)-1;//getSmKeyByVal()
    }
    if($type == 'shuangmian'){
        $bet_key = getSmKeyByVal($bet_name)['name'];
    }
    if($type == 'weishu'){
        $bet_key = getWeishuByVal($bet_name)['name'];
    }
    if($type == 'shengxiao'){
        $bet_key = shengxiao_key_by_name($bet_name);
    }
    if($type == 'temabanbo'){
        $bet_key = getBanboKeyByVal($bet_name)['name'];
    }

    return $bet_key;
}

function getTemaBanbo(){
    $data=[];
    $color = [
        'red'=>"红",
        'blue'=>"蓝",
        'green'=>"绿"
    ];
    $shuangmian = [
        'da'=>"大",
        'xiao'=>"小",
        'dan'=>"单",
        'shuang'=>"双",
        'hedan'=>"合单",
        'heshuang'=>"合双",
    ];
    foreach($color as $key=>$v){
        foreach($shuangmian as $sk=>$sv){
            $data[$key.'_'.$sk] = $v.$sv;
        }
    }
    return $data;
}

function getBanboKeyByVal($number){
    $name_arr = getTemaBanbo();
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

//多号码判断 20230725 连码
function lianmaBetIsRight($order_info,$kj_info){
    //$order_info = get_query_vals('fn_order', '*', array('id' => $order_id));
    $user_peilv = $order_info['peilv'];
    $bet_right_wanfa = [];
    if($order_info){
        //$kj_info = get_query_vals('fn_open_lhc', '*', array('term' => $order_info['term']));
        if($kj_info){
            $kj_num = $kj_info['code'].",".$kj_info['code_te'];
            $kj_num_arr = explode(',',$kj_num);
            $zhengma_arr = explode(',',$kj_info['code']);
            //获取投注的位置
            if($order_info['mingci']){
                $order_wanfa_info = explode('#',$order_info['mingci']);
                $wanfa_info = get_query_vals('fn_lhc_wanfa','name,id,wanfa_type,wanfa_key,bet_posi,zj_num,parent_id',['id'=>$order_info['bet_wanfa_id']]);
                $wanfa_type = $wanfa_info['wanfa_type'];//玩法类型
                $bet_posi = $wanfa_info['bet_posi'];
                $bet_zj_num = $wanfa_info['zj_num'];
                $wanfa_key = $wanfa_info['wanfa_key'];
                $bet_kj_number = [];//开奖号码
                $bet_posi_arr = explode(',',$bet_posi);
                //var_dump("bet_posi_arr",$bet_posi_arr);
                foreach($bet_posi_arr as $k=>$v){
                    $kj_number = $kj_num_arr[intval($v-1)];
                    if($wanfa_type == 'haoma'){
                        $bet_kj_number[] = $kj_number;
                    }
                    if($wanfa_type == 'shuangmian'){//获取双面玩法结果
                        $bet_kj_number[] = getShuangmianValByNumber($order_wanfa_info[1],$kj_number);
                    }
                    if($wanfa_type == 'temabanbo'){//获取特码半波名称
                        $temabanbo = getTemaBanboByNumber($kj_number);
                        foreach($temabanbo as $tb_key=>$tb_val){
                            $bet_kj_number[] = $tb_val;
                        }
                    }
                    if($wanfa_type == 'shengxiao'){
                        $bet_kj_number[] = shengxiao_name_by_number($kj_number)['value'];
                    }
                    if($wanfa_type == 'weishu'){//获取双面玩法结果
                        $bet_kj_number[] = $kj_number[1]."尾";
                    }
                }
//                var_dump("开奖结果",$bet_kj_number);
                $contents_info = explode('#',$order_info['content']);
                $user_bet_arr = explode(",",$contents_info[2]);
//                var_dump("投注号码",$user_bet_arr);
//                var_dump("投注方式",$wanfa_type);
                if($bet_kj_number && $user_bet_arr){
                    $bet_res = 2;//1 正确，2 错误

                    $bet_res_arr = array_intersect($bet_kj_number,$user_bet_arr);
                    if(($wanfa_type == 'temabanbo')){//特码半波
                        if(count($bet_res_arr) > 0){
                            $bet_res =  1;//正确
                        }
                    }
                    if(($wanfa_type == 'haoma' || $wanfa_type == 'shuangmian' || $wanfa_type == 'shengxiao' || $wanfa_type == 'weishu')){//单号
//                        var_dump($bet_zj_num);
                        if(count($bet_kj_number) == 1 || $bet_zj_num == 1){// 中奖一个号码
                            if(count($bet_res_arr) > 0){
                                $bet_res =  1;//正确
                            }
                        }
                        //多号码开奖 连码
                        $bet_zj_num_arr = explode(',',$bet_zj_num);
                        //连码 单玩法
//                        var_dump($wanfa_info);
//                        var_dump("bet_zj_num",$bet_zj_num);
                        if(count($bet_zj_num_arr) == 1 && $bet_zj_num > 1){
                            $bet_res_count = count($bet_res_arr);
                            if($bet_res_count >= $bet_zj_num){
//                                var_dump("投注中".$wanfa_info['name']);
                                $bet_res =  1;//正确
                            }
                            //特串
                            if($wanfa_key == 'techuan'){
                                $bet_res = 2;
                                $bet_tema_res = 0;
                                $bet_zhengma_res = 0;
                                if(in_array($kj_num_arr[6],$user_bet_arr)){
                                    $bet_tema_res =  1;//正确
                                }
                                if(array_intersect($zhengma_arr,$user_bet_arr)){
                                    $bet_zhengma_res = 1;
                                }
                                if($bet_tema_res && $bet_zhengma_res){
                                    $bet_res = 1;
                                }
                            }
                        }
                        if(count($bet_zj_num_arr) > 1){//多号码判断 中二，中三，二中特
                            $bet_res_arr = array_intersect($bet_kj_number,$user_bet_arr);
                            $bet_res_count = count($bet_res_arr);
                            $son_wanfa = getWanfaSonListById($wanfa_info['id'],$order_info['user_id'],$order_info['roomid']);
//                            var_dump("bet_res_arr",$bet_res_arr);
//                            var_dump("bet_zj_num_arr",$bet_zj_num_arr);
//                            var_dump("bet_res_count",strval($bet_res_count));
                            if(in_array(strval($bet_res_count),$bet_zj_num_arr)){
                                $bet_res =  1;//正确
                                //获取赔率
                                if($wanfa_key == '3zhong2'){
                                    //三中二 中三
                                    foreach($son_wanfa as $bk=>$bv){
                                        if( $bet_res_count >= $bv['zj_num']){
                                            var_dump("投注中".$bv['name']);
                                            $user_peilv = $bv['rate'];
                                        }
                                    }
                                }
                                //二中特
                                if($wanfa_key == '2zhongte'){
                                    //三中二 中三
                                    $bet_res =  2;//错误
                                    foreach($son_wanfa as $bk=>$bv){
                                        if($bv['wanfa_key'] == 'erzhongte'){//
                                            if(in_array($kj_num_arr[6],$user_bet_arr)){
                                                var_dump("投注中".$bv['name']);
                                                $user_peilv = $bv['rate'];
                                                $bet_res =  1;//正确
                                            }
                                        }
                                        if($bv['wanfa_key'] == 'erzhonger') {//
                                            if ($bet_res_count >= $bv['zj_num']) {
                                                var_dump("投注中" . $bv['name']);
                                                $user_peilv = $bv['rate'];
                                                $bet_res =  1;//正确
                                            }
                                        }
                                    }
                                }
                                //
                            }

                        }
//                        var_dump("user_peilv",$user_peilv);
                    }
                    return $bet_res;
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

//根据号码获取对应的双面值
function getShuangmianValByNumber($bet_key,$bet_kj_number){
        //$bet_key = $wanfa_info[1];
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
    return $bet_kj_number_val;
}

//根据号码获取对应的特码半波值
function getTemaBanboByNumber($bet_kj_number){
    //$bet_key = $wanfa_info[1];
    $color = [
        'red'=>"红",
        'blue'=>"蓝",
        'green'=>"绿"
    ];
    $shuangmian = [
        'da'=>"大",
        'xiao'=>"小",
        'dan'=>"单",
        'shuang'=>"双",
        'hedan'=>"合单",
        'heshuang'=>"合双",
    ];
    $number_color = bo_name_by_number($bet_kj_number)['name'];
    $number_color_name = $color[$number_color];

    if($bet_kj_number > 24){
        $bet_kj_number_val[] = $number_color_name."大";
    }else{
        $bet_kj_number_val[] = $number_color_name."小";
    }
    if($bet_kj_number % 2 == 0){
        $bet_kj_number_val[] = $number_color_name."双";
    }else{
        $bet_kj_number_val[] = $number_color_name."单";
    }
    $he_number = $bet_kj_number[0] + $bet_kj_number[1];
    if($he_number % 2 == 0){
        $bet_kj_number_val[] = $number_color_name."合双";
    }else{
        $bet_kj_number_val[] = $number_color_name."合单";
    }
    return $bet_kj_number_val;
}
//后台专用
function getAllWanfaItemByWanfaId($wanfa_id){
    $wanfa_info = get_query_vals('fn_lhc_wanfa','*',['id'=>$wanfa_id]);
    $item_list =  getWanfaListByType($wanfa_info['wanfa_type']);
    //var_dump($item_list);
    $son_item = [];
    foreach($item_list as $wf_key=>$wf_val){
        $item = [];
        $item = $wf_val;
        $item['wanfa_name'] = $wanfa_info['name'];
        $item['mingci'] = $wanfa_info['wanfa_key']."#".$wf_val['id']."#".$wanfa_info['bet_posi'];
        $item['class'] = $wanfa_info['wanfa_key']."-".$wf_val['id']."-". str_replace(',','_',$wanfa_info['bet_posi']);
        $item['bg_class'] = $wf_val['class'];
        $son_item[] = $item;
    }
    return $son_item;
}

?>