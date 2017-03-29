<?php
header("Content-Type: text/html;charset=utf-8");
// if(isset($_FILES['file'])){
// 	$file_name = $_POST['file_name'].'.'.$_POST['file_type'];
// 	move_uploaded_file($_FILES['file']['tmp_name'],dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'uploads_pd' . DIRECTORY_SEPARATOR . $file_name);
// 	echo $file_name;
// }
print_r($_FILES);
$filename = $_FILES['file']['name'];
$type = $_FILES['file']['type'];
$tmp_name = $_FILES['file']['tmp_name'];
$name=iconv("UTF-8","gb2312", $filename);
echo $filename;
// move_uploaded_file($tmp_name,"uploads_pd".$filename);
move_uploaded_file($tmp_name,'uploads_pd/'.$name);