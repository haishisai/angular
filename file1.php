<?php
session_start();
header("Content-Type: text/html;charset=utf-8");
require_once 'PdoMySQL.class.php';
$db = new PdoMySQL();
$user_id = $_SESSION['user_id'];

//接收上传的图片
//print_r($_FILES);
if($_FILES) {
    //遍历所有照片的类型，判断上传的类型是否是常用的照片类型
    foreach($_FILES['images']['type'] as $key=>$value) {
         switch ($value) {
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
        if($ext) {
            //设置照片的存放相对路径和命名。命名照片例：20161226_2.png，下划线后跟遍历的键值区分照片，可在此处自行设置规则！！
            // $name = 'uploads_pd/'.date('Ymd',time()).'_'."$key.$ext";
            $pic_name = date('Ymdhisa',time()).rand(1000000,9999999).'.'.$ext;
            $name = 'uploads_pd/'.$pic_name;
            $name = iconv("UTF-8","gb2312", $name);
            //将上传的文件移动到新位置
            move_uploaded_file($_FILES['images']['tmp_name'][$key], $name);
            $sql = "insert into pic_repo (user_id,pic_name) values ('{$user_id}','{$pic_name}' )";
            $res = $db->getAll($sql);
            //echo "<script>window.history.go(-1)</script>";
        }
    }
}


// 展示图片
if(isset($_POST['pic_show'])){
    $sql ="select pic_name from pic_repo where user_id = '{$user_id}' order by id desc;";
    $res = $db->getAll($sql);
    echo json_encode($res);
    //echo $res;
    
}

