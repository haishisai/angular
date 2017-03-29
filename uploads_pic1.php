<?php
session_start();
header("Content-Type: text/html;charset=utf-8");
require_once 'PdoMySQL.class.php';
$db = new PdoMySQL();
$user_id = $_SESSION['user_id'];

// print_r($_FILES);
// die;

// 接收上传的文件
if($_FILES){
	$tmp_name = $_FILES['file']['tmp_name'];

	$type =	$_FILES['file']['type'];
	switch ($type) {
             case 'image/jpeg': $ext = 'jpg';
               	break;
             case 'image/png': $ext = 'png';
                break;
             case 'image/gif': $ext = 'gif';
             	break;
             default:
                $ext = '';
                echo "wrong!!!";
                break;
    }
	//echo $type;
	$pic_name = date('Ymdhisa',time()).rand(1000000,9999999).'.'.$ext;
	$name = 'uploads_pd/'.$pic_name;
	$name = iconv("UTF-8","gb2312", $name);
	//将上传的文件移动到新位置
	move_uploaded_file($tmp_name, $name);
	//
	$sql = "INSERT INTO pic_repo (user_id,pic_name) VALUES ('{$user_id}','{$pic_name}')";
	//$res = $db->getAll($sql);
	$res = $db->execute($sql);

}

