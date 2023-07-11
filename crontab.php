<?php
$command = "ps aux |grep 'amg/caiji/jsft.php' | grep -v 'grep' |wc -l";
exec($command, $out);
if ($out[0] == 0) {
    exec('nohup php /www/wwwroot/amg/caiji/jsft.php >/dev/null 2>&1 &');
}else{
    echo "急速飞艇采集任务已开启" . PHP_EOL;
}
unset($out);
$command = "ps aux |grep 'amg/caiji/jssc.php' | grep -v 'grep' |wc -l";
exec($command, $out);
if ($out[0] == 0) {
    exec('nohup php /www/wwwroot/amg/caiji/jssc.php >/dev/null 2>&1 &');
}else{
    echo "急速赛车采集任务已开启" . PHP_EOL;
}
unset($out);
$command = "ps aux |grep 'amg/caiji/xyft.php' | grep -v 'grep' |wc -l";
exec($command, $out);
if ($out[0] == 0) {
    exec('nohup php /www/wwwroot/amg/caiji/xyft.php >/dev/null 2>&1 &');
}else{
    echo "幸运飞艇采集任务已开启" . PHP_EOL;
}
unset($out);
$command = "ps aux |grep 'amg/caiji/az10.php' | grep -v 'grep' |wc -l";
exec($command, $out);
if ($out[0] == 0) {
    exec('nohup php /www/wwwroot/amg/caiji/az10.php >/dev/null 2>&1 &');
}else{
    echo "澳洲10采集任务已开启" . PHP_EOL;
}
unset($out);

//香港六合彩采集 20230711
$command = "ps aux |grep 'amg/caiji/xglhc.php' | grep -v 'grep' |wc -l";
exec($command, $out);
if ($out[0] == 0) {
    exec('nohup php /www/wwwroot/amg/caiji/xglhc.php >/dev/null 2>&1 &');
}else{
    echo "香港集任务已开启" . PHP_EOL;
}
unset($out);


$command = "ps aux |grep 'amg/caiji/fandian.php' | grep -v 'grep' |wc -l";
exec($command, $out);
if ($out[0] == 0) {
    exec('nohup php /www/wwwroot/amg/caiji/fandian.php >/dev/null 2>&1 &');
}else{
    echo "返点任务已开启" . PHP_EOL;
}
unset($out);
$command = "ps aux |grep 'amg/caiji/kaijiang.php' | grep -v 'grep' |wc -l";
exec($command, $out);
if ($out[0] == 0) {
    exec('nohup php /www/wwwroot/amg/caiji/kaijiang.php >/dev/null 2>&1 &');
}else{
    echo "开奖任务已开启" . PHP_EOL;
}
unset($out);