<?php
include_once(dirname(dirname(__FILE__)) . "/Public/config.php");
$game = $_COOKIE['game'];

?>

<?php if($game == 'jnd28'){
    $type = '5';
}else{
    echo '<br><br><strong style="font-size:40px;color:red">错误！';
}

select_query("fn_yuce", '*', "`type` = '$type' order by `id` asc");
while($con = db_fetch_array()){
    $cons[] = $con;
}
$cons=array_reverse($cons);

// var_dump($cons);
?>

<div class="forecast-row titlehi">
    <li class="for-title">期号</li>
    <!--<li class="for-title">时间</li>-->
    <li class="for-title">预测</li>
</div>
<?php
foreach ($cons as $s=>$vx):?>
<div class="forecast-row titlehi">
    <li class="for-num"><?php echo $vx['qihao'];?></li>
    <li class="for-dx"><span class="<?php echo $vx['daxiao']=='大'?'red':'blue';?>"><?php echo $vx['daxiao'];?></span>&nbsp;<?php echo $vx['dx_status'];?></li>
    
    <li class="for-ds"><span class="<?php echo $vx['danshuang']=='双'?'red':'blue';?>"><?php echo $vx['danshuang'];?></span>&nbsp;<?php echo $vx['ds_status'];?></li>
    <li class="for-zuhe"><span class="<?php echo $vx['zuhe']=='大单'?'pink':'yellow';?>"><?php echo $vx['zuhe'];?></span>&nbsp;<?php echo $vx['zuhe_status'];?></li>
</div>
<?php endforeach;?>

