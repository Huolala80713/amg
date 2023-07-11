<?php
define('WEB_ROOT_PATH',dirname(__FILE__));

function getImgObj($imgPath){
    $pathInfo = pathinfo($imgPath);
    switch( strtolower($pathInfo['extension']) ) {
        case 'jpg':
        case 'jpeg':
            $imagecreatefromjpeg = 'imagecreatefromjpeg';
            break;
        case 'png':
            $imagecreatefromjpeg = 'imagecreatefrompng';
            break;
        case 'gif':
        default:
            $imagecreatefromjpeg = 'imagecreatefromstring';
            $imgPath = file_get_contents($imgPath);
            break;
    }
    $resource = $imagecreatefromjpeg($imgPath);
    return $resource;
}

function buildBoatImg($bgPath,$openInfo=[]){
    //幸运飞艇 left,top=>210,10   $firstImgPath,$secondImgPath,$thirdImgPath,
    /*
    $rowsend=[];
    $rowsend['current_sn']=$BetTerm['term'];
    $rowsend['next_sn']=$BetTerm['next_term'];
    $rowsend['this_time']=$BetTerm['next_term'];
    $rowsend['next_time']=$BetTerm['next_term'];
    $rowsend['letf_time']=strtotime($BetTerm['next_time'])-time();
    $rowsend['open_num']=$BetTerm['code'];
    $rowsend['fp_time']=$time;
    $rowsend['gyh']=$h.' '.($h>10?'大':'小').' '.($h%2==0?'双':'单');*/

    $openNums=explode(',',$openInfo['open_num']);
    $firstImgPath=WEB_ROOT_PATH.'/openimg/0'.$openNums[0].'.png';
    $secondImgPath=WEB_ROOT_PATH.'/openimg/0'.$openNums[1].'.png';
    $thirdImgPath=WEB_ROOT_PATH.'/openimg/0'.$openNums[2].'.png';
    $hNum=$openNums[0]+$openNums[1];
    $dx=$hNum>11?'大':'小';
    $ds=$hNum%2==0?'双':'单';
    $adb = array();
    $adb[] = ($openNums[0] > $openNums[9])?'龙':'虎';
    $adb[] = ($openNums[1] > $openNums[8])?'龙':'虎';
    $adb[] = ($openNums[2] > $openNums[7])?'龙':'虎';
    $adb[] = ($openNums[3] > $openNums[6])?'龙':'虎';
    $adb[] = ($openNums[4] > $openNums[5])?'龙':'虎';
    $gyhText="{$hNum}     {$dx}     {$ds}";
    $longHuText=implode('     ',$adb);
    $qihaoText=$openInfo['this_time'].' '.$openInfo['current_sn'];



    $oneRate=0.7;
    $otherRate=0.5;
    $backImgObj=getImgObj($bgPath);
    $fBoatImgObj=getImgObj($firstImgPath);
    $sBoatImgObj=getImgObj($secondImgPath);
    $tBoatImgObj=getImgObj($thirdImgPath);
    $pic_w=imagesx($fBoatImgObj);//游艇数字原宽
    $pic_h=imagesy($fBoatImgObj);//游艇数字原长
    imagecopyresized($backImgObj,$fBoatImgObj,290,300,0,0,$pic_w*$oneRate,$pic_h*$oneRate,$pic_w,$pic_h); // 最后两个参数为原始图片宽度和高度，倒数两个参数为copy时的图片宽度和高度
    imagecopyresized($backImgObj,$sBoatImgObj,60,260,0,0,$pic_w*$otherRate,$pic_h*$otherRate,$pic_w,$pic_h);
    imagecopyresized($backImgObj,$tBoatImgObj,590,260,0,0,$pic_w*$otherRate,$pic_h*$otherRate,$pic_w,$pic_h);

    //写入文字
    $yellowColor = imagecolorallocate($backImgObj, 255,255,69);
    imagettftext($backImgObj, 12, 0, 10, 506, $yellowColor,WEB_ROOT_PATH.'/openimg/st.ttf', $qihaoText);
    imagettftext($backImgObj, 12, 0, 335, 506, $yellowColor,WEB_ROOT_PATH.'/openimg/st.ttf', $gyhText);
    imagettftext($backImgObj, 12, 0, 530, 506, $yellowColor,WEB_ROOT_PATH.'/openimg/st.ttf', $longHuText);

    $backImgObj=buildOpenNum($backImgObj,200,10,$openNums);
    return $backImgObj;
}
function buildHistoryPcImg($gameID , $bgPath,$openList=[]){
    $backImgObj = getImgObj($bgPath);
    $bg_w = imagesx($backImgObj);
    $bg_h = imagesy($backImgObj);
    $blackColor = imagecolorallocate($backImgObj, 0,0,0);
    $blueColor = imagecolorallocate($backImgObj, 27,109,244);
    $redColor = imagecolorallocate($backImgObj, 251,14,14);
    foreach ($openList as $key=>$value){
        $openNums = explode(',',$value['code']);
        $hNum=$openNums[0]+$openNums[1]+$openNums[2];
        $hNum = $hNum < 10 ? '0' . $hNum : $hNum;
        $dx=$hNum>13?'大':'小';
        $ds=$hNum%2==0?'双':'单';
        foreach ($openNums as $k => $val){
            $number_img = WEB_ROOT_PATH.'/openimg/'.$val.$val.$val.'.png';
            $number_img_object = getImgObj($number_img);
            $number_img_w = imagesx($number_img_object);
            $number_img_h = imagesy($number_img_object);
            imagecopyresampled($backImgObj,$number_img_object,100 + ($k + 5) * 32 ,63 + $key * 40,0,0,32,32,$number_img_w,$number_img_h);

            imagettftext($backImgObj, 12, 0.8, 15, 85 + $key * 40, $blackColor,WEB_ROOT_PATH.'/openimg/st.ttf', $value['term']);

            imagettftext($backImgObj, 12, 0.8, 548, 85 + $key * 40, $blackColor,WEB_ROOT_PATH.'/openimg/st.ttf', $hNum);
            imagettftext($backImgObj, 12, 0.8, 578, 85 + $key * 40, $dx == '大'?$redColor:$blueColor,WEB_ROOT_PATH.'/openimg/st.ttf', $dx);
            imagettftext($backImgObj, 12, 0.8, 608, 85 + $key * 40, $ds == '双'?$redColor:$blueColor,WEB_ROOT_PATH.'/openimg/st.ttf', $ds);
        }
    }
    return $backImgObj;
}
function buildHistoryImg($gameID , $bgPath,$openList=[]){
    $backImgObj = getImgObj($bgPath);
    $bg_w = imagesx($backImgObj);
    $bg_h = imagesy($backImgObj);
    $blackColor = imagecolorallocate($backImgObj, 0,0,0);
    $blueColor = imagecolorallocate($backImgObj, 27,109,244);
    $redColor = imagecolorallocate($backImgObj, 251,14,14);
    foreach ($openList as $key=>$value){

        $openNums = explode(',',$value['code']);
        $hNum=$openNums[0]+$openNums[1];
        $hNum = $hNum < 10 ? '0' . $hNum : $hNum;
        $dx=$hNum>11?'大':'小';
        $ds=$hNum%2==0?'双':'单';
        $adb = array();
        $adb[] = ($openNums[0] > $openNums[9])?'龙':'虎';
        $adb[] = ($openNums[1] > $openNums[8])?'龙':'虎';
        $adb[] = ($openNums[2] > $openNums[7])?'龙':'虎';
        $adb[] = ($openNums[3] > $openNums[6])?'龙':'虎';
        $adb[] = ($openNums[4] > $openNums[5])?'龙':'虎';
        foreach ($openNums as $k => $val){
            $number_img = WEB_ROOT_PATH.'/openimg/'.$val.$val.$val.'.png';
            $number_img_object = getImgObj($number_img);
            $number_img_w = imagesx($number_img_object);
            $number_img_h = imagesy($number_img_object);
            imagecopyresampled($backImgObj,$number_img_object,100 + $k * 32 ,63 + $key * 40,0,0,32,32,$number_img_w,$number_img_h);
        }
        switch ($gameID){
            case 1:
                imagettftext($backImgObj, 12, 0.8, 20, 85 + $key * 40, $blackColor,WEB_ROOT_PATH.'/openimg/st.ttf', $value['term']);
                break;
            case 2:
                imagettftext($backImgObj, 12, 0.8, 15, 85 + $key * 40, $blackColor,WEB_ROOT_PATH.'/openimg/st.ttf', $value['term']);
                break;
            case 3:
            case 4:
                imagettftext($backImgObj, 12, 0.8, 20, 85 + $key * 40, $blackColor,WEB_ROOT_PATH.'/openimg/st.ttf', substr($value['term'] , 3));
                break;
//                case 4:
//                    imagettftext($backImgObj, 12, 0.8, 20, 85 + $key * 40, $blackColor,WEB_ROOT_PATH.'/openimg/st.ttf', substr($value['term'] , 3));
//                    break;
            case 6:
                imagettftext($backImgObj, 12, 0.8, 20, 85 + $key * 40, $blackColor,WEB_ROOT_PATH.'/openimg/st.ttf', substr($value['term'] , 6));
                break;
        }

        imagettftext($backImgObj, 12, 0.8, 435, 85 + $key * 40, $blackColor,WEB_ROOT_PATH.'/openimg/st.ttf', $hNum);
        imagettftext($backImgObj, 12, 0.8, 465, 85 + $key * 40, $dx == '大'?$redColor:$blueColor,WEB_ROOT_PATH.'/openimg/st.ttf', $dx);
        imagettftext($backImgObj, 12, 0.8, 495, 85 + $key * 40, $ds == '双'?$redColor:$blueColor,WEB_ROOT_PATH.'/openimg/st.ttf', $ds);

        imagettftext($backImgObj, 12, 0.8, 525, 85 + $key * 40, $adb[0] == '龙'?$redColor:$blueColor,WEB_ROOT_PATH.'/openimg/st.ttf', $adb[0]);
        imagettftext($backImgObj, 12, 0.8, 555, 85 + $key * 40, $adb[1] == '龙'?$redColor:$blueColor,WEB_ROOT_PATH.'/openimg/st.ttf', $adb[1]);
        imagettftext($backImgObj, 12, 0.8, 585, 85 + $key * 40, $adb[2] == '龙'?$redColor:$blueColor,WEB_ROOT_PATH.'/openimg/st.ttf', $adb[2]);
        imagettftext($backImgObj, 12, 0.8, 615, 85 + $key * 40, $adb[3] == '龙'?$redColor:$blueColor,WEB_ROOT_PATH.'/openimg/st.ttf', $adb[3]);
        imagettftext($backImgObj, 12, 0.8, 645, 85 + $key * 40, $adb[4] == '龙'?$redColor:$blueColor,WEB_ROOT_PATH.'/openimg/st.ttf', $adb[4]);
    }
    return $backImgObj;
}
function buildCarImg($bgPath,$openInfo=[]){
    $openNums=explode(',',$openInfo['open_num']);
    $firstImgPath=WEB_ROOT_PATH.'/openimg/2'.$openNums[0].'.png';
    $secondImgPath=WEB_ROOT_PATH.'/openimg/2'.$openNums[1].'.png';
    $thirdImgPath=WEB_ROOT_PATH.'/openimg/2'.$openNums[2].'.png';
    $hNum=$openNums[0]+$openNums[1];
    $dx=$hNum>11?'大':'小';
    $ds=$hNum%2==0?'双':'单';
    $adb = array();
    $adb[] = ($openNums[0] > $openNums[9])?'龙':'虎';
    $adb[] = ($openNums[1] > $openNums[8])?'龙':'虎';
    $adb[] = ($openNums[2] > $openNums[7])?'龙':'虎';
    $adb[] = ($openNums[3] > $openNums[6])?'龙':'虎';
    $adb[] = ($openNums[4] > $openNums[5])?'龙':'虎';
    $gyhText="{$hNum}     {$dx}     {$ds}";
    $longHuText=implode('     ',$adb);
    $qihaoText=$openInfo['this_time'].' '.$openInfo['current_sn'];
    $qihaoTopText=$openInfo['current_sn'];
    $qihaoNextTopText=$openInfo['next_sn'];
    $timeNextTopText=date('H:i',$openInfo['next_time']);


    //飞车 num left,top=>150,15
    $backImgObj=getImgObj($bgPath);
    $fBoatImgObj=getImgObj($firstImgPath);
    $sBoatImgObj=getImgObj($secondImgPath);
    $tBoatImgObj=getImgObj($thirdImgPath);
    $congrImgObj=getImgObj(WEB_ROOT_PATH.'/openimg/34.png');
    $botnImgObj=getImgObj(WEB_ROOT_PATH.'/openimg/33.png');
    $bg_w=imagesx($backImgObj);
    $bg_h=imagesx($backImgObj);

    $congr_w=imagesx($congrImgObj);
    $congr_h=imagesx($congrImgObj);
    $botn_w=imagesx($botnImgObj);
    $botn_h=imagesx($botnImgObj);
    $pic_w=imagesx($fBoatImgObj);//游艇数字原宽
    $pic_h=imagesy($fBoatImgObj);//游艇数字原长
    $oneRate=1;
    $otherRate=0.6;

    imagecopyresampled($backImgObj,$fBoatImgObj,290,260,0,0,$pic_w*$oneRate,$pic_h*$oneRate,$pic_w,$pic_h); // 最后两个参数为原始图片宽度和高度，倒数两个参数为copy时的图片宽度和高度
    imagecopyresampled($backImgObj,$sBoatImgObj,60,260,0,0,$pic_w*$otherRate,$pic_h*$otherRate,$pic_w,$pic_h);
    imagecopyresampled($backImgObj,$tBoatImgObj,590,260,0,0,$pic_w*$otherRate,$pic_h*$otherRate,$pic_w,$pic_h);
    imagecopyresampled($backImgObj,$botnImgObj,0,450,0,0,$bg_w,$bg_h,$botn_w,$botn_h);
    imagecopyresampled($backImgObj,$congrImgObj,0,0,0,0,$congr_w,$congr_h,$congr_w,$congr_h);
    //写入文字
    $yellowColor = imagecolorallocate($backImgObj, 83,240,253);
    $redColor = imagecolorallocate($backImgObj, 255,0,0);

    imagettftext($backImgObj, 12, 0, 10, 496, $yellowColor,WEB_ROOT_PATH.'/openimg/st.ttf', $qihaoText);
    imagettftext($backImgObj, 12, 0, 335, 496, $yellowColor,WEB_ROOT_PATH.'/openimg/st.ttf', $gyhText);
    imagettftext($backImgObj, 12, 0, 530, 496, $yellowColor,WEB_ROOT_PATH.'/openimg/st.ttf', $longHuText);
    imagettftext($backImgObj, 12, 0, 61, 72, $yellowColor,WEB_ROOT_PATH.'/openimg/st.ttf', $qihaoTopText);
    imagettftext($backImgObj, 12, 0, 733, 31, $yellowColor,WEB_ROOT_PATH.'/openimg/st.ttf', $qihaoNextTopText);
    imagettftext($backImgObj, 12, 0, 733, 64, $redColor,WEB_ROOT_PATH.'/openimg/st.ttf', $timeNextTopText);

    $backImgObj=buildOpenNum($backImgObj,150,15,$openNums);
    return $backImgObj;
}

function buildOpenNum($backImgObj,$left,$top,$openNum){
    // $numArr=['111.png','222.png','333.png','444.png','555.png','666.png','777.png','888.png','999.png'];
    $pic_w=89;//游艇数字原宽
    $pic_h=89;//游艇数字原长
    foreach ($openNum as $numImgName){
        $path=WEB_ROOT_PATH.'/openimg/'.str_repeat($numImgName,3).'.png';
        $numObj=getImgObj($path);
        $targetWH=45;
        imagecopyresized($backImgObj,$numObj,$left,$top,0,0,$targetWH,$targetWH,$pic_w,$pic_h);
        $left=$left+$targetWH+5;
    }
    return $backImgObj;
}

function doSaveOpenImg($gameID,$rowsend,$fileName){
    if($gameID==1||$gameID==4) $imgObj=buildCarImg(WEB_ROOT_PATH.'/openimg/1-'.$gameID.'.png',$rowsend);
    else $imgObj=buildBoatImg(WEB_ROOT_PATH.'/openimg/1-'.$gameID.'.png',$rowsend);
    /*
    $rowsend=[];
    $rowsend['current_sn']='1007';
    $rowsend['next_sn']='1007';
    $rowsend['this_time']=strtotime('2021-01-22 20:52:14');
    $rowsend['next_time']=strtotime('2021-01-22 20:52:14')+120;
    $rowsend['letf_time']=strtotime($rowsend['next_time'])-time();
    $rowsend['open_num']='9,8,3,1,4,5,6,2,10,7';
    $rowsend['fp_time']=10;*/
    $file = WEB_ROOT_PATH.'/openhistory/'.$gameID.'/'.$fileName.'_'.$rowsend['current_sn'].'.jpg';
    if(!file_exists(dirname($file))){
        mkdir(dirname($file) , 0775 , true);
    }
    imagejpeg($imgObj,$file,100);
    //imagejpeg($imgObj,WEB_ROOT_PATH.'/openhistory/'.$gameType.'/'.$fileName.'_'.$rowsend['current_sn'].'.jpg',100);
}
function doSaveOpenHistoryImg($gameID,$rowsend,$openList,$fileName){
    if($gameID == 5){
        $imgObj = buildHistoryPcImg($gameID ,WEB_ROOT_PATH.'/openimg/history_pc.png',$openList);
    }else{
        $imgObj = buildHistoryImg($gameID ,WEB_ROOT_PATH.'/openimg/history.png',$openList);
    }
    $file = WEB_ROOT_PATH.'/openhistory/list/'.$gameID.'/'.$fileName.'_'.$rowsend['term'].'.jpg';
    if(!file_exists(dirname($file))){
        mkdir(dirname($file) , 0775 , true);
    }
    imagejpeg($imgObj,$file,100);
}
?>