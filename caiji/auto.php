<?php

header("Content-type:textml;charset=utf-8");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
include_once "../Public/config.php";

    $countnum = db_query("select count(id) from fn_open where type = 5");
    $countnum = db_fetch_array();
    $idnum = db_query("select * from fn_open where type=5 order by term desc limit 50");
    $idnum = db_fetch_array();
    $idnum1 = $idnum[0];
    $idnum2 = $idnum1 - 300;
    if($countnum[0] >= 100){
        $res = db_query("delete from fn_open where type=5 and id <= $idnum2");
        $res = db_fetch_array();
    }
        // var_dump($idnum2);