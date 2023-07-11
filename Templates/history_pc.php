<?php
include_once(dirname(dirname(__FILE__)) . "/Public/config.php");
$game = $_COOKIE['game'];
?>

<?php if($game == 'jnd28'){
    $type = '5';
}else{
    echo '<br><br><strong style="font-size:40px;color:red">该房间已经封盘啦！';
}
?>

<div class="history-row titlehi">
    <li class="col-qh">期号</li>
    <li class="col-num numrow"><span>开</span><span>奖</span><span>号</span><span>码</span></li>
    <li class="col-gy">大小单双</li>
    <!--<li class="col-lh">1-5龙虎</li>-->
</div>
<?php select_query("fn_open", '*', "`type` = '$type' order by `next_time` desc limit 10");
while($con = db_fetch_array()){
    $cons[] = $con;
}
$ay = true;
foreach($cons as $con){
    if($ay){
        $line_row = "";
        $ay = false;
    }else{
        $line_row = "line_row";
        $ay = true;
    }
    $g = explode(",", $con['code']);
     $ys['小'] = 'blue';
    // $ys['大'] = 'gray';
    // $ys['单'] = 'blue';
    // $ys['双'] = 'gray';
    // $ys['豹子'] = 'gray';
    // $ys['顺子'] = 'blue';
    // $ys['对子'] = 'gray';
    // $ys['大单'] = 'blue';
    // $ys['大双'] = 'gray';
    // $ys['小单'] = 'blue';
    // $ys['小双'] = 'gray';
    $count = $g[0] + $g[1] + $g[2];
    
    $n1 = $g[0];
    $n2 = $g[1];
    $n3 = $g[2];
    if($n1+2 && $n2+1 == $n3 && $n1+1 == $n3){
        $shunzi = 1;
    }elseif(preg_match('/^(9(?=0)|0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){2}\d$/',$n1.$n2.$n3)){
        $shunzi = 1;
    }elseif ($n1 - 1 == $n2 && $n2 - 1 == $n3) {
        $shunzi = 1;
    }elseif(($n2 - $n3 == 2 && $n1 - 1 == $n3) || ($n1 - 1 == $n3 && $n2 - 1 == $n1) || ($n3 - 1 == $n1 && $n1 - 1 == $n2) || ($n3 - 1 == $n1 && $n1 - 1 == $n2) || ($n1 - 1 == $n3 && $n2 - 1 == $n1) || ($n1 - 1 == $n3 && $n3 - 1 == $n1) || ($n3 - 1 == $n1 && $n2 - 1 == $n3)) {
        $shunzi = 1;
    }elseif($n1 + $n2 == $n3 && $n3 - $n2 == $n1) {
        $shunzi = 1;
    }elseif($n1 == '8' && $n2 == '9' && $n3 == '0') {
        $shunzi = 1;
    }elseif(($n1 == '8' && $n2 == '9' && $n3 == '0') || ($n1 == '8' && $n2 == '0' && $n3 == '9') 
    || ($n1 == '9' && $n2 == '8' && $n3 == '0') || ($n1 == '9' && $n2 == '0' && $n3 == '8') ||  ($n1 == '9' && $n2 == '7' && $n3 == '8') 
    || ($n1 == '9' && $n2 == '0' && $n3 == '1') || ($n1 == '9' && $n2 == '1' && $n3 == '0') 
    || ($n1 == '0' && $n2 == '8' && $n3 == '9') || ($n1 == '0' && $n2 == '9' && $n3 == '8') 
    || ($n1 == '0' && $n2 == '1' && $n3 == '9') || ($n1 == '0' && $n2 == '9' && $n3 == '1') 
    || ($n1 == '1' && $n2 == '0' && $n3 == '9') || ($n1 == '1' && $n2 == '9' && $n3 == '0') || ($n1 == '1' && $n2 == '9' && $n3 == '2')
    || ($n1 == '1' && $n2 == '0' && $n3 == '2') || ($n1 == '1' && $n2 == '2' && $n3 == '0')
    || ($n1 == '2' && $n2 == '0' && $n3 == '1')
    || ($n1 == '3' && $n2 == '1' && $n3 == '2')
    || ($n1 == '4' && $n2 == '2' && $n3 == '3')
    || ($n1 == '5' && $n2 == '3' && $n3 == '4')
    || ($n1 == '6' && $n2 == '4' && $n3 == '5')
    || ($n1 == '7' && $n2 == '5' && $n3 == '6')
    || ($n1 == '8' && $n2 == '6' && $n3 == '7')
    ) {
        $shunzi = 1;
    }else{
        $shunzi = 0;
    }
    if($n1 == $n2 && $n1 == $n3){
	    $baozi = '豹子';
	    $bao = 1;
	}else{
	    $bao = 0;
	}
    if(($n1 == $n2 && $n1 != $n3) || ($n2 == $n3 && $n2 != $n1) || ($n3 == $n2 && $n1 != $n2)){
        $dui = 1;  
        $duizi = '对子';
    }elseif($n1 == 9 && $n2 == 0 && $n3 == 9){
        $dui = 1;  
        $duizi = '对子';
    }elseif($n1 == 1 && $n2 == 3 && $n3 == 1){
        $dui = 1;  
        $duizi = '对子';
    }else{
        $dui = 0;
    }

		if($count %2 == 1){//判断单双
		    $dans = '单';
		} else{
		    $dans = '双';
		} 
		if($count < 14){
		    $daxiao = '小';
		}else{
		    $daxiao = '大';
		}

		if($shunzi == 1){
		    $shun = '顺子';
		}
		if($count <= 22){
		    $jidax = "极大";
		}elseif($count >= 5){
		    $jidax = "极小";
		}
// 		 return $count;
    ?>
    <div class="history-row">
        <li class="col-qh"><?php echo $con['term'];?></li>
        <li class="col-num numrow"><span class="ball_pks_  n<?php echo (int)$g[0];
            ?> ball_lenght10" title="<?php echo (int)$g[0];
            ?>"><?php echo (int)$g[0];?></span>&nbsp;
            <span class="ball_pks_  n<?php echo (int)$g[1];
            ?> ball_lenght10" title="<?php echo (int)$g[1];
            ?>"><?php echo (int)$g[1];?></span>&nbsp;
            <span class="ball_pks_  n<?php echo (int)$g[2];
            ?> ball_lenght10" title="<?php echo (int)$g[2];
            ?>"><?php echo (int)$g[2];?></span>
            
        </li>
        <li class="col-gy">
            <span class="count"><?php echo $count; ?></span>
            <span class="gray"><?php echo $daxiao.' '.$dans;?></span>
        </li>

    </div>
   
<?php }
?>