<?php


if($_FILES){
    $typeArr=['png','jpg','jpeg','gif'];
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $name_tmp = $_FILES['file']['tmp_name'];
    if (empty($name)) {
        echo json_encode(array("error" => "您还未选择图片",'status'=>0));
        exit;
    }
    $type = strtolower(substr(strrchr($name, '.'), 1)); //获取文件类型

    if (!in_array($type, $typeArr)) {
        echo json_encode(array("error" => "请上传png,jpg,jpeg,gif类型的图片！",'status'=>0));
        exit;
    }
    if ($size >5*1024*1024) { //上传大小
        echo json_encode(array("message" => "图片大小已超过5M！",'status'=>0));
        exit;
    }
    $path='Templates/header/';
    $header_name = time() . rand(10000, 99999) . "." . $type; //图片名称
    $hader_url = $path . $header_name; //上传后图片路径+名称
    if (move_uploaded_file($name_tmp, $hader_url)) { //临时文件转移到目标文件夹
        $headimg='/'.$hader_url;
        $_SESSION['headimg']=$headimg;
        update_query("fn_user", array("headimg" => $headimg), array('userid' => $_SESSION['userid']));
        echo json_encode(['header'=>$headimg,'status'=>1]);
    }else{
        echo json_encode(['msg'=>'上传失败','status'=>0]);
    }
}
