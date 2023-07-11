#!/bin/bash  
  
step=2 #间隔的秒数，不能大于60  
  
for (( i = 0; i < 60; i=(i+step) )); do  
    curl http://182.16.20.50/caiji/jssc.php >> /home/wwwroot/log/jssc.log
    curl http://182.16.20.50/caiji/az10.php >> /home/wwwroot/log/az10.log
    curl http://182.16.20.50/caiji/xyft.php >> /home/wwwroot/log/xyft.log
    curl http://182.16.20.50/caiji/jsft.php >> /home/wwwroot/log/jsft.log


  
    sleep $step  
done  
  
exit 0
