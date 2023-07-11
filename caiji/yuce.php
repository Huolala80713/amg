<?php

function randaxds(){

$data = rand(0,27);
if($data%2 == 0){
	$d['ds'] = '双';
}else{
    $d['ds']  = '单';
}
if($data >= 13){
    $d['dx']  = '大';
}else{
    $d['dx']  = '小';
}
return $d;

}

function randzuhe(){


$da = array('大单','大双','小单','小双');
shuffle($da);

return  $da;
}
