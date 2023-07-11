<?php
include_once(dirname(dirname(__FILE__)) . "/Public/config.php");
$game = $_COOKIE['game'];
$type = '';
if ($game == 'pk10') {
    $type = '1';
} elseif ($game == 'xyft') {
    $type = '2';
} elseif ($game == 'cqssc') {
    $type = '3';
} elseif ($game == 'xy28') {
    $type = '4';
} elseif ($game == 'jnd28') {
    $type = '5';
} elseif ($game == 'jsmt') {
    $type = '6';
}
$info = get_query_vals('fn_lottery' . $type , '*' , ['roomid'=>$_SESSION['roomid']]);
$user = get_query_vals('fn_user' , '*' , ['roomid'=>$_SESSION['roomid'],'userid'=>$_SESSION['userid']]);
if(empty($user['agent'])){
    $fandian = $info['fandian'];
}else{
    $fandian = json_decode($user['fandian'] , true)[$type];
}
$mingci_list = [
    1 => '冠军',
    2 => '亚军',
    3 => '第三名',
    4 => '第四名',
    5 => '第五名',
    6 => '第六名',
    7 => '第七名',
    8 => '第八名',
    9 => '第九名',
    10 => '第十名',
];
?>
<div class="touzhu-panel">
    <div class="touzhu-panel-left">
        <ul>
            <li data-id="kuaijie" class="checked">快捷</li>
            <li data-id="mingci">1~10名</li>
            <li data-id="liangmian">两面</li>
            <li data-id="guanyahe">冠亚和</li>
        </ul>
    </div>
    <div class="touzhu-panel-right">
        <div id="kuaijie">
            <ul class="mingci">
                <?php foreach ($mingci_list as $key => $value):?>
                    <li data-mingci="<?php echo $key == 10?0:$key;?>"><?php echo $value;?></li>
                <?php endforeach;?>
            </ul>
            <ul class="content">
                <?php for($i=1;$i<=10;$i++):?>
                    <li data-content="<?php echo $i;?>">
                        <span class="n<?php echo $i;?>" style="color: #fff;"><?php echo $i;?></span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['tema'] , $fandian , 'tema');?></font>
                    </li>
                <?php endfor;?>
                <li data-content="大"><span>大</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['da'] , $fandian , 'da');?></font></li>
                <li data-content="小"><span>小</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['xiao'] , $fandian , 'xiao');?></font></li>
                <li data-content="单"><span>单</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['dan'] , $fandian , 'dan');?></font></li>
                <li data-content="双"><span>双</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['shuang'] , $fandian , 'shuang');?></font></li>
            </ul>
        </div>
        <div id="mingci" style="display: none">
            <?php foreach ($mingci_list as $key => $value):?>
                <p data-mingci="<?php echo $key == 10?0:$key;?>">
                    <?php echo $value;?>
                    <i class="icon-img"></i>
                </p>
                <ul class="content mingci<?php echo $key == 10?0:$key;?>">
                    <?php for($i=1;$i<=10;$i++):?>
                        <li data-type="mingci" data-mingci="<?php echo $key == 10?0:$key;?>" data-content="<?php echo $i;?>">
                            <span class="n<?php echo $i;?>" style="color: #fff;"><?php echo $i;?></span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['tema'] , $fandian , 'tema');?></font>
                        </li>
                    <?php endfor;?>
                    <div style="clear: both;height: 0;"></div>
                </ul>
            <?php endforeach;?>
        </div>
        <div id="liangmian" style="display: none">
            <p data-mingci="he">
                <?php echo '冠亚和';?>
                <i class="icon-img"></i>
            </p>
            <ul class="content mingci<?php echo 'he';?>">
                <li data-type="liangmian" data-mingci="冠亚和" data-content="大"><span style="width: 1rem">冠亚大</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['heda'] , $fandian , 'heda');?></font></li>
                <li data-type="liangmian" data-mingci="冠亚和" data-content="小"><span style="width: 1rem">冠亚小</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['hexiao'] , $fandian , 'hexiao');?></font></li>
                <li data-type="liangmian" data-mingci="冠亚和" data-content="单"><span style="width: 1rem">冠亚单</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['hedan'] , $fandian , 'hedan');?></font></li>
                <li data-type="liangmian" data-mingci="冠亚和" data-content="双"><span style="width: 1rem">冠亚双</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['heshuang'] , $fandian , 'heshuang');?></font></li>
                <div style="clear: both;height: 0;"></div>
            </ul>
            <?php foreach ($mingci_list as $key => $value):?>
                <p data-mingci="<?php echo $key == 10?0:$key;?>">
                    <?php echo $value;?>
                    <i class="icon-img"></i>
                </p>
                <ul class="content mingci<?php echo $key == 10?0:$key;?>">
                    <li data-type="liangmian" data-mingci="<?php echo $key == 10?0:$key;?>" data-content="大"><span>大</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['da'] , $fandian , 'da');?></font></li>
                    <li data-type="liangmian" data-mingci="<?php echo $key == 10?0:$key;?>" data-content="小"><span>小</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['xiao'] , $fandian , 'xiao');?></font></li>
                    <li data-type="liangmian" data-mingci="<?php echo $key == 10?0:$key;?>" data-content="单"><span>单</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['dan'] , $fandian , 'dan');?></font></li>
                    <li data-type="liangmian" data-mingci="<?php echo $key == 10?0:$key;?>" data-content="双"><span>双</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['shuang'] , $fandian , 'shuang');?></font></li>
                    <?php if($key <= 5):?>
                    <li data-type="liangmian" data-mingci="<?php echo $key == 10?0:$key;?>" data-content="龙"><span>龙</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['long'] , $fandian , '`long`');?></font></li>
                    <li data-type="liangmian" data-mingci="<?php echo $key == 10?0:$key;?>" data-content="虎"><span>虎</span><font class="peilv"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['hu'] , $fandian , 'hu');?></font></li>
                    <?php endif;?>
                    <div style="clear: both;height: 0;"></div>
                </ul>
            <?php endforeach;?>
        </div>
        <div id="guanyahe" style="display: none">
            <div class="item">
                <p>
                    赔率：<font style="color: red;"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['he341819'] , $fandian , 'he341819');?></font>
                </p>
                <ul class="content">
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="3">3</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="4">4</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="18">18</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="19">19</li>
                    <div style="clear: both;height: 0;"></div>
                </ul>
            </div>
            <div class="item">
                <p>
                    赔率：<font style="color: red;"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['he561617'] , $fandian , 'he561617');?></font>
                </p>
                <ul class="content">
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="5">5</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="6">6</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="16">16</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="17">17</li>
                    <div style="clear: both;height: 0;"></div>
                </ul>
            </div>
            <div class="item">
                <p>
                    赔率：<font style="color: red;"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['he781415'] , $fandian , 'he781415');?></font>
                </p>
                <ul class="content">
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="7">7</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="8">8</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="14">14</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="15">15</li>
                    <div style="clear: both;height: 0;"></div>
                </ul>
            </div>
            <div class="item">
                <p>
                    赔率：<font style="color: red;"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['he9101213'] , $fandian , 'he9101213');?></font>
                </p>
                <ul class="content">
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="9">9</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="10">10</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="12">12</li>
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="13">13</li>
                    <div style="clear: both;height: 0;"></div>
                </ul>
            </div>
            <div class="item">
                <p>
                    赔率：<font style="color: red;"><?php echo getManagePeilv($type , $_SESSION['roomid'] , $info['he11'] , $fandian , 'he11');?></font>
                </p>
                <ul class="content">
                    <li data-type="guanyahe" data-mingci="冠亚和" data-content="11">11</li>
                    <div style="clear: both;height: 0;"></div>
                </ul>
            </div>
        </div>
    </div>
    <div class="touzhu-panel-bottom">
        <ul class="money_panel">
            <li>5</li>
            <li>10</li>
            <li>50</li>
            <li>100</li>
            <li>500</li>
        </ul>
        <div style="line-height: 0.5rem;font-size: 0.23rem;color: #999;padding: 0 0.1rem;"><span style="padding-right: 30px;">下注总额：<span id="money">0</span></span> 共<span id="count">0</span>注单</div>
        <div class="btn-panel">
            <input id="money_input" placeholder="请输入下注金额！">
            <p class="btn" data-username="<?php echo $_SESSION['username'];?>" data-headimg="<?php echo $_SESSION['headimg'];?>" id="xiazhu">下注</p>
            <p class="btn" id="chongzhi">重置</p>
            <p class="btn" id="cancel">取消</p>
        </div>
    </div>
</div>

