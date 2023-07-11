#!/bin/bash  
  
step=10 #间隔的秒数，不能大于60
  
for (( i = 0; i < 60; i=(i+step) )); do  
    curl https://mobile.amg668.top/rebot/bet.php?gameid=4\&roomid=666777 >> /www/wwwroot/log/jssc_robot.log
    curl https://mobile.amg668.top/rebot/bet.php?gameid=3\&roomid=666777 >> /www/wwwroot/log/jsft_robot.log
    curl https://mobile.amg668.top/rebot/bet.php?gameid=1\&roomid=666777 >> /www/wwwroot/log/az10_robot.log
    curl https://mobile.amg668.top/rebot/bet.php?gameid=2\&roomid=666777 >> /www/wwwroot/log/xyft_robot.log


    sleep $step  
done  
  
exit 0
