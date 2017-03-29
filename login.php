<?php
session_start();
require_once 'PdoMySQL.class.php';
$db = new PdoMySQL();
// 登入
if(isset($_POST['user_id'])){
	$username = $_POST['user_id'];
	$password = $_POST['user_pwd'];
	$username = str_replace('\'','', $username);
	$password = str_replace('\'','', $password);
	$sql = "select * from pic_save where user_id='{$username}' and password='{$password}';";
	$res = $db->getAll($sql);
	if(empty($res)){
		echo 0;
		return false;
	}else{
		//查询员工姓名和工号
		$sql = "select * from pic_save where user_id='{$username}';";
		$res = $db->getAll($sql);
		foreach($res as $value);
		$user_id = $value['user_id'];		
		$_SESSION['user_id'] = $user_id;
		echo "go" ;
		return false;
		//写入日志！！！
	}	
	return false;
}
// 注册
if(isset($_POST['new_user_id'])){
	$new_user_id = $_POST['new_user_id'];
	$user_pwd = $_POST['user_pwd'];
	$user_mail= $_POST['user_mail'];
	$user_phone = $_POST['user_phone'];
	$sql = "insert into pic_save (user_id,password,mail,telephone) values ('{$new_user_id}','{$user_pwd}','{$user_mail}','{$user_phone}');";
	$res = $db->execute($sql);
	if($res==1){
		echo "ok";
	}
	return false;
}
// 检测-用户名是否已存在
if(isset($_POST['check_newuser'])){
	$new_user = $_POST['check_newuser'];
	$sql = "select * from anto_school where name='{$new_user}';";
	$res = $db->getAll($sql);
	if(empty($res)){
		echo "ok";
		return false;
	}else{
		echo 0;
		return false;
	}
}
// 检测-是否登录
if(isset($_POST['login_show'])){
	$id = $_SESSION['user_id'];
	echo $id;	
}
// 退出
if(isset($_POST['logout'])){
	session_destroy();
	echo "out";
}




if(isset($_POST['aaa'])){
	echo "out";
}





?>