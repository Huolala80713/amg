<?php
include_once(dirname(dirname(__FILE__)) . "/Public/config.php");
$game = $_COOKIE['game'];
$type = '';
?>

<?php if ($game == 'pk10') {
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
} else {
    echo '<p style="font-size:13px;color:#666;text-align: center;padding: 5px;">该房间已经封盘啦！</p>';
}
if($type){
    $open = select_query("fn_order", 'term', "`roomid` = '{$_SESSION['roomid']}' and `userid` = '{$_SESSION['userid']}' and`type` = '{$type}' group by term",['id desc'],'5');
    $term = [];
    while ($con = db_fetch_array()) {
        $term[] = $con['term'];
    }
    $sql = "`roomid` = '{$_SESSION['roomid']}' and `userid` = '{$_SESSION['userid']}' and `type` = '{$type}' and term in ('" . implode("','" , $term) . "')";
    select_query("fn_order", '*', $sql ,['id desc']);// and `addtime` like '" . date('Y-m-d') . "%'

while ($con = db_fetch_array()) {
    $cons[] = $con;
}
$cons = array_reverse($cons);
$allZD = [];
foreach ($cons as $item) {
    if ($item['status'] == '已撤单') continue;
    $row = isset($allZD[$item['term']]) ? $allZD[$item['term']] : ['sn' => $item['term'], 'count' => 0, 'money' => 0, 'earn' => '结算中'];
    $row['count']++;
    $mingci = '';
    $item['mingci'] = ((is_numeric($item['mingci']) && $item['mingci']==0)?10:$item['mingci']);
    $item['content'] = ((is_numeric($item['content']) && $item['content']==0)?10:$item['content']);
    switch ($item['mingci']){
        case 1:
            $mingci = '冠军';
            break;
        case 2:
            $mingci = '亚军';
            break;
        case 3:
            $mingci = '第三名';
            break;
        case 4:
            $mingci = '第四名';
            break;
        case 5:
            $mingci = '第五名';
            break;
        case 6:
            $mingci = '第六名';
            break;
        case 7:
            $mingci = '第七名';
            break;
        case 8:
            $mingci = '第八名';
            break;
        case 9:
            $mingci = '第九名';
            break;
        case 10:
            $mingci = '第十名';
            break;
        case '和':
            $mingci = '冠亚和';
            break;
    }
    if(isset($row['list'][$mingci . $item['content']])){
        $row['list'][$mingci . $item['content']]['money'] += $item['money'];
        if(is_numeric($item['status'])){
            $row['list'][$mingci . $item['content']]['status'] += $item['status'] > 0 ? $item['status'] - $item['money']:$item['status'];
        }
    }else{
        $row['list'][$mingci . $item['content']] = [
            'mingci' => $mingci,
            'content' => $item['content'],
            'money' => $item['money'],
            'peilv' => $item['peilv'],
            'addtime' => date('m-d H:i' , strtotime($item['addtime'])),
            'status' => is_numeric($item['status'])?($item['status'] > 0 ? $item['status'] - $item['money']:$item['status']):$item['status']
        ];
    }
    if (is_numeric($item['status'])) {
        if (!is_numeric($row['earn'])) $row['earn'] = 0;
        $row['earn'] += $item['status'];
        //$row['money'] += floatval($item['money']);
        $row['money'] +=  $item['status']>0?floatval($item['money']):0;
        //print_r($item['status']);
        //$row['money'] += $item['status']>0?floatval($item['money']):-floatval($item['money']);
    }
    $allZD[$item['term']] = $row;
}

if(empty($allZD)){
    echo '<p style="font-size:13px;color:#666;text-align: center;padding: 5px;">暂无记录！</p>';

}else{
    krsort($allZD);
    foreach ($allZD as $item){
?>
    <div class="zhudanwrap">
        <div class="zhudan-row titlehi" style="padding: 0 10px;">
            <li class="for-sn">第<?php echo  $item['sn']; ?>期</li>
            <li class="for-title">收益</li>

        </div>
        <div class="zhudan-row infozhu" style="padding: 0 10px;">
            <li class="for-sn">注单总数：<?php echo  $item['count']; ?></li>
            <li class="for-title"><?php echo  round($item['earn']-$item['money'] , 4); ?></li>
        </div>
        <?php foreach ($item['list'] as $ord){?>
        <dl style="display: flex;padding: 0 10px;line-height: 35px;text-align: center;justify-content: space-between;border-bottom: 1px solid #dfdfdf;background: #efefef;">
            <dd style="font-size: 0.23rem;text-align: left;display: flex;"><?php echo $ord['mingci'] . '【' . $ord['content']  . ' / ' . $ord['money']  . '】';?></dd>
            <dd style="font-size: 0.23rem;text-align: left;display: flex;"><?php echo $ord['peilv'];?></dd>
            <dd style="font-size: 0.23rem;display: flex;"><?php echo $ord['addtime'];?></dd>
            <dd style="font-size: 0.23rem;text-align: right;display: flex;"><?php echo is_numeric($ord['status'])?($ord['status'] > 0?"<span style='color: red;font-weight: bold;'>{$ord['status']}</span>":$ord['status']):$ord['status'];?></dd>
        </dl>
        <?php }?>
    </div>
<?php
    }
}
?>

<?php }?>
