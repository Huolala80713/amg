<?php
include_once(dirname(dirname(__FILE__)) . "/Public/config.php");
$game = $_COOKIE['game'];
?>

<?php if($game == 'pk10'){
    $type = '1';
}elseif($game == 'xyft'){
    $type = '2';
}elseif($game == 'cqssc'){
    $type = '3';
}elseif($game == 'xy28'){
    $type = '4';
}elseif($game == 'jsmt'){
    $type = '6';
}else{
    echo '<br><br><strong style="font-size:40px;color:red">该房间已经封盘啦！';
}
?>

    <div class="history-row titlehi">
        <li class="col-qh">期号</li>
        <li class="col-num numrow"><span>一</span><span>二</span><span>三</span><span>四</span><span>五</span><span>六</span><span>七</span><span>八</span><span>九</span><span>十</span></li>
        <li class="col-gy">冠亚和</li>
        <li class="col-lh">1-5龙虎</li>
    </div>
<?php
select_query("fn_open", '*', "`type` = '$type' order by `next_time` desc limit 10");
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
    $ys['大'] = 'gray';
    $ys['单'] = 'blue';
    $ys['双'] = 'gray';
    $ys['龙'] = 'gray';
    $ys['虎'] = 'blue';
    $h['冠亚'] = $g[0] + $g[1];
    $h['冠亚大小'] = ($h['冠亚'] > 11)?'大':'小';
    $h['冠亚单双'] = (($h['冠亚'] % 2) == 0)?'双':'单';
    $adb = array();
    $adb[] = ($g[0] > $g[9])?'龙':'虎';
    $adb[] = ($g[1] > $g[8])?'龙':'虎';
    $adb[] = ($g[2] > $g[7])?'龙':'虎';
    $adb[] = ($g[3] > $g[6])?'龙':'虎';
    $adb[] = ($g[4] > $g[5])?'龙':'虎';
    ?>
    <div class="history-row">
        <li class="col-qh">
            <?php if(in_array($type , [3,4,6])):?>
                <?php if($type ==6):?>
                <?php echo substr($con['term'] , 3);?>
                
                <?php else:?>
                <?php echo substr($con['term'] , 3);?>
                <?php endif;?>
            <?php else:?>
                <?php echo $con['term'];?>
            <?php endif;?>
        </li>
        <li class="col-num numrow"><span class="ball_pks_  n<?php echo (int)$g[0];
            ?> ball_lenght10" title="<?php echo (int)$g[0];
            ?>"><?php echo (int)$g[0];?></span>
            <span class="ball_pks_  n<?php echo (int)$g[1];
            ?> ball_lenght10" title="<?php echo (int)$g[1];
            ?>"><?php echo (int)$g[1];?></span>
            <span class="ball_pks_  n<?php echo (int)$g[2];
            ?> ball_lenght10" title="<?php echo (int)$g[2];
            ?>"><?php echo (int)$g[2];?></span>
            <span class="ball_pks_  n<?php echo (int)$g[3];
            ?> ball_lenght10" title="<?php echo (int)$g[3];
            ?>"><?php echo (int)$g[3];?></span>
            <span class="ball_pks_  n<?php echo (int)$g[4];
            ?> ball_lenght10" title="<?php echo (int)$g[4];
            ?>"><?php echo (int)$g[4];?></span>
            <span class="ball_pks_  n<?php echo (int)$g[5];
            ?> ball_lenght10" title="<?php echo (int)$g[5];
            ?>"><?php echo (int)$g[5];?></span>
            <span class="ball_pks_  n<?php echo (int)$g[6];
            ?> ball_lenght10" title="<?php echo (int)$g[6];
            ?>"><?php echo (int)$g[6];?></span>
            <span class="ball_pks_  n<?php echo (int)$g[7];
            ?> ball_lenght10" title="<?php echo (int)$g[7];?>"><?php echo (int)$g[7];?></span>
            <span class="ball_pks_  n<?php echo (int)$g[8];
            ?> ball_lenght10" title="<?php echo (int)$g[8];
            ?>"><?php echo (int)$g[8];?></span>
            <span class="ball_pks_  n<?php echo (int)$g[9];
            ?> ball_lenght10" title="<?php echo (int)$g[9];
            ?>"><?php echo (int)$g[9];?></span>
        </li>
        <li class="col-gy">
            <span class="count"><?php echo $h['冠亚'];?></span>
            <span class="<?php echo $ys[$h['冠亚大小']];?>"><?php echo $h['冠亚大小'];?></span>
            <span class="<?php echo $ys[$h['冠亚单双']];?> g_r_line g_td_p_right"><?php echo $h['冠亚单双'];?></span>
        </li>
        <li class="col-lh">
            <span class="<?php echo $ys[$adb[0]];?>"><?php echo $adb[0];?></span>
            <span class="<?php echo $ys[$adb[1]];?>"><?php echo $adb[1];?></span>
            <span class="<?php echo $ys[$adb[2]];?>"><?php echo $adb[2];?></span>
            <span class="<?php echo $ys[$adb[3]];?>"><?php echo $adb[3];?></span>
            <span class="<?php echo $ys[$adb[4]];?>"><?php echo $adb[4];?></span>
        </li>

    </div>
<?php }
?>