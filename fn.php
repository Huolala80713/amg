<?php
include_once("Public/config.php");
$row=get_query_vals('fn_room','roomid' , []);
if($row){
    $_SESSION['roomdefault_id'] = $row['roomid'];
}
header('Location:/action.php?do=login');
?>